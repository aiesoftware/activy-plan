<?php declare(strict_types=1);

namespace App\Booking\Service;

use App\Booking\Command\CreateBookingCommand;
use App\Booking\Entity\ActivitySlot;
use App\Booking\Repository\ActivitySlotRepositoryInterface;
use App\Booking\Repository\BookingRepositoryInterface;
use App\Booking\Repository\GuestRepositoryInterface;

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
//        $guest = $this->guestRepository->find($command->guestId);


//        /** @var ActivitySlot $activitySlot */
//        $activitySlot = $this->activitySlotRepository->findBy([
//            'activityId' => $command->activityId,
//            'startDateTime' => $command->activityStartDateTime
//        ]);

        $command->guest->makeBookingFor($command->activitySlot, $command->activityParticipants, $this->bookingRepository);

        //$activitySlot->bookParticipantsOnToSlot($guest, $command->activityParticipants);
    }
}
