<?php

namespace App\Console\Commands;

use App\Services\StockReservationService;
use Illuminate\Console\Command;

class ExpireReservationsCommand extends Command
{
    protected $signature = 'reservations:expire';

    protected $description = 'Release expired stock reservations and restore stock';

    public function __construct(protected StockReservationService $reservationService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $released = $this->reservationService->releaseExpired();
        $this->info("Released {$released} expired stock reservation(s).");

        return Command::SUCCESS;
    }
}
