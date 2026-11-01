<?php

namespace App\Console\Commands;

use App\ActivityLog;
use App\SecurityAlert;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PurgeSecurityLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:purge-logs 
                            {--days=90 : Number of days of logs to retain}
                            {--force : Force purge without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge activity logs older than retention period for compliance and disk management';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int)$this->option('days');
        $cutoff = Carbon::now()->subDays($days);

        $count = ActivityLog::where('created_at', '<', $cutoff)->count();

        if ($count === 0) {
            $this->info("No activity logs older than {$days} days found.");
            return 0;
        }

        if (!$this->option('force') && !$this->confirm("Are you sure you want to delete {$count} activity records older than {$days} days?")) {
            $this->warn('Purge cancelled.');
            return 0;
        }

        $deleted = ActivityLog::where('created_at', '<', $cutoff)->delete();
        $this->info("Successfully purged {$deleted} activity log records.");

        return 0;
    }
}
