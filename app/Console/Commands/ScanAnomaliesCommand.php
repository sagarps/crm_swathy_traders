<?php

namespace App\Console\Commands;

use App\Services\Security\AnomalyDetectionService;
use Illuminate\Console\Command;

class ScanAnomaliesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:scan-anomalies {--period=24 : Hours to scan back}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan recent CRM activity logs for anomalous usage patterns and security threats';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $hours = (int)$this->option('period');
        $this->info("Scanning CRM activity for anomalous patterns across the past {$hours} hours...");

        $results = AnomalyDetectionService::scanRecentActivity($hours);

        $this->newLine();
        $this->table(
            ['Metric', 'Value'],
            [
                ['Period Analyzed', "{$results['period_hours']} hours"],
                ['Total Activity Events', $results['total_events']],
                ['Successful Logins', $results['successful_logins']],
                ['Failed Logins', $results['failed_logins']],
                ['Flagged Suspicious Events', $results['suspicious_events']],
                ['Open Security Incidents', $results['open_alerts']],
            ]
        );

        if (!empty($results['top_failed_ips'])) {
            $this->newLine();
            $this->warn('Top IP Addresses with Failed Logins:');
            $rows = [];
            foreach ($results['top_failed_ips'] as $ip => $count) {
                $rows[] = [$ip, $count];
            }
            $this->table(['IP Address', 'Failed Attempts'], $rows);
        }

        $this->info('Anomaly scan completed successfully.');
        return 0;
    }
}
