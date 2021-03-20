<?php declare(strict_types=1);

namespace App\Booking\Repository;

use App\Booking\Entity\Booking;

interface BookingRepositoryInterface
{
    public function store(Booking $booking): void;
}
