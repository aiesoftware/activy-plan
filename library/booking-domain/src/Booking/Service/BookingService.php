<?php declare(strict_types=1);

namespace App\Booking\Service;

use App\Booking\Command\CreateBookingCommand;
use App\Booking\Repository\BookingRepositoryInterface;

class BookingService
{
    private BookingRepositoryInterface $bookingRepository;

    public function __construct(
        BookingRepositoryInterface $bookingRepository
    )
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function bookParticipantsOnToActivitySlot(CreateBookingCommand $command)
    {
        $command->guest->makeBookingFor($command->activitySlot, $command->activityParticipants, $this->bookingRepository);
    }
}
