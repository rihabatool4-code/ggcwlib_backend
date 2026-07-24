<?php

namespace App\Console\Commands;

use App\Services\ReservationExpiryService;
use Illuminate\Console\Command;

class ExpireReservations extends Command
{
    protected $signature = 'bookings:expire-reservations';

    protected $description = '24 hours se purani "reserved" bookings ko "expired" mark karta hai';

    public function handle()
    {
        $count = ReservationExpiryService::expireOldReservations();

        if ($count === 0) {
            $this->info('Koi reservation expire nahi hui.');
        } else {
            $this->info("$count reservation(s) expired successfully.");
        }
    }
}