<?php

namespace App\Services\Support;

use App\SupportTicket;
use Illuminate\Support\Facades\DB;

class TicketNumberService
{
    /**
     * Generate the next concurrency-safe unique ticket number for the given calendar year.
     * Format: TKT-YYYY-000001
     *
     * @param int|null $year
     * @return string
     */
    public static function generate(?int $year = null): string
    {
        $year = $year ?: (int) date('Y');

        return DB::transaction(function () use ($year) {
            // Fetch the highest existing ticket for the calendar year with row-level locking
            $latestTicket = SupportTicket::whereYear('created_at', $year)
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();

            $nextSequence = 1;

            if ($latestTicket && $latestTicket->ticket_number) {
                if (preg_match('/TKT-\d{4}-(\d{6})/', $latestTicket->ticket_number, $matches)) {
                    $nextSequence = (int) $matches[1] + 1;
                }
            }

            $ticketNumber = sprintf('TKT-%04d-%06d', $year, $nextSequence);

            // Double check uniqueness just in case
            while (SupportTicket::where('ticket_number', $ticketNumber)->exists()) {
                $nextSequence++;
                $ticketNumber = sprintf('TKT-%04d-%06d', $year, $nextSequence);
            }

            return $ticketNumber;
        });
    }
}

