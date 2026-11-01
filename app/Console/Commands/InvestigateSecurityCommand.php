<?php

namespace App\Console\Commands;

use App\ActivityLog;
use App\SecurityAlert;
use Carbon\Carbon;
use Illuminate\Console\Command;

class InvestigateSecurityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:investigate 
                            {--hours=24 : Number of hours to search back}
                            {--ip= : Filter by IP address}
                            {--user= : Filter by username or user ID}
                            {--action= : Filter by action name}
                            {--suspicious-only : Only show suspicious records}
                            {--limit=25 : Maximum records to display}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Investigate CRM security logs and audit activity directly from terminal';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $hours = (int)$this->option('hours');
        $ip = $this->option('ip');
        $userFilter = $this->option('user');
        $action = $this->option('action');
        $suspiciousOnly = $this->option('suspicious-only');
        $limit = (int)$this->option('limit');

        $this->info("Investigating activity logs (last {$hours} hours)...");

        $query = ActivityLog::with('user')
            ->where('created_at', '>=', Carbon::now()->subHours($hours))
            ->latest('created_at');

        if ($ip) {
            $query->where('ip_address', $ip);
        }

        if ($userFilter) {
            if (is_numeric($userFilter)) {
                $query->where('user_id', (int)$userFilter);
            } else {
                $query->whereHas('user', function ($q) use ($userFilter) {
                    $q->where('username', 'LIKE', "%{$userFilter}%");
                });
            }
        }

        if ($action) {
            $query->where('action', $action);
        }

        if ($suspiciousOnly) {
            $query->where('is_suspicious', true);
        }

        $records = $query->limit($limit)->get();

        if ($records->isEmpty()) {
            $this->warn('No matching activity logs found for the given criteria.');
            return 0;
        }

        $tableRows = [];
        foreach ($records as $log) {
            $username = $log->user?->username ?? 'Guest / N/A';
            $tableRows[] = [
                $log->id,
                $log->created_at?->format('Y-m-d H:i:s'),
                $log->action,
                $username,
                $log->ip_address,
                $log->severity,
                $log->is_suspicious ? 'YES' : 'No',
                substr($log->description, 0, 50),
            ];
        }

        $this->table(
            ['ID', 'Timestamp', 'Action', 'User', 'IP Address', 'Severity', 'Suspicious', 'Description'],
            $tableRows
        );

        // Show open security alerts
        $alerts = SecurityAlert::open()->latest()->limit(5)->get();
        if ($alerts->isNotEmpty()) {
            $this->newLine();
            $this->error('OPEN SECURITY INCIDENTS:');
            $alertRows = [];
            foreach ($alerts as $alert) {
                $alertRows[] = [
                    $alert->id,
                    $alert->created_at?->format('Y-m-d H:i'),
                    $alert->severity,
                    $alert->alert_type,
                    $alert->title,
                    $alert->ip_address ?? 'N/A',
                    $alert->status,
                ];
            }
            $this->table(['ID', 'Detected At', 'Severity', 'Type', 'Title', 'IP', 'Status'], $alertRows);
        }

        return 0;
    }
}
