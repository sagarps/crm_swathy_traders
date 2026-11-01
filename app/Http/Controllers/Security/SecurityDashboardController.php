<?php

namespace App\Http\Controllers\Security;

use App\ActivityLog;
use App\Http\Controllers\Controller;
use App\SecurityAlert;
use App\Services\Security\ActivityLogger;
use App\Services\Security\AnomalyDetectionService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class SecurityDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Security & Monitoring Overview Dashboard.
     */
    public function index(): View
    {
        $today = Carbon::today();

        $stats = [
            'total_today' => ActivityLog::where('created_at', '>=', $today)->count(),
            'failed_logins_today' => ActivityLog::where('action', 'login_failed')->where('created_at', '>=', $today)->count(),
            'suspicious_today' => ActivityLog::where('is_suspicious', true)->where('created_at', '>=', $today)->count(),
            'open_alerts_count' => SecurityAlert::open()->count(),
            'critical_alerts_count' => SecurityAlert::open()->critical()->count(),
        ];

        $recentAlerts = SecurityAlert::with('user')
            ->open()
            ->latest()
            ->limit(6)
            ->get();

        $recentActivities = ActivityLog::with('user')
            ->latest('created_at')
            ->limit(12)
            ->get();

        $topFailedIps = ActivityLog::where('action', 'login_failed')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('ip_address, COUNT(*) as count')
            ->groupBy('ip_address')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        return view('security.index', compact('stats', 'recentAlerts', 'recentActivities', 'topFailedIps'));
    }

    /**
     * Full Searchable & Filterable Activity Explorer.
     */
    public function activities(Request $request): View
    {
        $query = ActivityLog::with(['user', 'office'])->latest('created_at');

        if ($request->filled('search')) {
            $term = trim($request->search);
            $query->where(function ($q) use ($term) {
                $q->where('description', 'LIKE', "%{$term}%")
                    ->orWhere('ip_address', 'LIKE', "%{$term}%")
                    ->orWhere('entity_type', 'LIKE', "%{$term}%")
                    ->orWhere('action', 'LIKE', "%{$term}%");
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('suspicious_only')) {
            $query->where('is_suspicious', true);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $activities = $query->paginate(25)->appends($request->all());
        $users = User::select('id', 'username')->orderBy('username')->get();
        $actions = ActivityLog::distinct()->pluck('action')->filter()->values();

        return view('security.activities', compact('activities', 'users', 'actions'));
    }

    /**
     * JSON Detail for specific activity (used by modal inspector).
     */
    public function activityDetail($id): JsonResponse
    {
        $activity = ActivityLog::with(['user', 'office'])->findOrFail($id);

        return response()->json([
            'id' => $activity->id,
            'action' => $activity->action,
            'description' => $activity->description,
            'user' => $activity->user?->username ?? 'Guest / System',
            'office' => $activity->office?->office_name ?? 'N/A',
            'ip_address' => $activity->ip_address,
            'user_agent' => $activity->user_agent,
            'request_url' => $activity->request_url,
            'request_method' => $activity->request_method,
            'severity' => $activity->severity,
            'is_suspicious' => $activity->is_suspicious,
            'changes' => $activity->changes,
            'created_at' => $activity->created_at?->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Security Incident / Anomaly Alerts View.
     */
    public function alerts(Request $request): View
    {
        $query = SecurityAlert::with(['user', 'resolver'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['open', 'investigating']);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        $alerts = $query->paginate(20)->appends($request->all());

        return view('security.alerts', compact('alerts'));
    }

    /**
     * Resolve or update a Security Alert.
     */
    public function resolveAlert(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:investigating,resolved,false_positive',
            'resolution_notes' => 'nullable|string|max:1000',
        ]);

        $alert = SecurityAlert::findOrFail($id);
        $alert->update([
            'status' => $request->status,
            'resolved_by' => Auth::id(),
            'resolved_at' => now(),
            'resolution_notes' => $request->resolution_notes,
        ]);

        ActivityLogger::log(
            action: 'alert_resolution',
            description: "Security Alert #{$alert->id} marked as '{$request->status}' by " . (Auth::user()?->username ?? 'Admin'),
            entity: $alert,
            changes: [
                'status' => $request->status,
                'notes' => $request->resolution_notes,
            ],
            severity: 'info'
        );

        return redirect()->back()->with('success', "Security Alert #{$alert->id} status updated to {$request->status}.");
    }

    /**
     * System Health & Log Monitoring View.
     */
    public function health(): View
    {
        // 1. Database Connectivity & Ping
        $dbStart = microtime(true);
        $dbStatus = 'OK';
        $dbLatency = 0;
        try {
            DB::select('SELECT 1');
            $dbLatency = round((microtime(true) - $dbStart) * 1000, 2);
        } catch (\Throwable $e) {
            $dbStatus = 'ERROR: ' . $e->getMessage();
        }

        // 2. Log Files Status
        $logPath = storage_path('logs');
        $logFiles = [];
        if (File::exists($logPath)) {
            $files = File::files($logPath);
            foreach ($files as $file) {
                $logFiles[] = [
                    'name' => $file->getFilename(),
                    'size' => round($file->getSize() / 1024, 2) . ' KB',
                    'modified' => Carbon::createFromTimestamp($file->getMTime())->format('Y-m-d H:i:s'),
                ];
            }
        }

        // 3. Disk Space
        $freeDisk = round(disk_free_space(base_path()) / (1024 * 1024 * 1024), 2);
        $totalDisk = round(disk_total_space(base_path()) / (1024 * 1024 * 1024), 2);
        $usedPercent = $totalDisk > 0 ? round((($totalDisk - $freeDisk) / $totalDisk) * 100, 1) : 0;

        // 4. Anomaly scan stats
        $scanStats = AnomalyDetectionService::scanRecentActivity(24);

        return view('security.health', compact(
            'dbStatus',
            'dbLatency',
            'logFiles',
            'freeDisk',
            'totalDisk',
            'usedPercent',
            'scanStats'
        ));
    }
}
