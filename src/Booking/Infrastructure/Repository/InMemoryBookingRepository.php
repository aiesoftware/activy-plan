<?php declare(strict_types=1);

namespace App\Booking\Infrastructure\Repository;

use App\Booking\Entity\Activity;
use App\Booking\Entity\Booking;
use App\Booking\Entity\Collection\BookingCollection;
use App\Booking\Entity\Guest;
use App\Booking\Repository\BookingRepositoryInterface;
use App\Shared\Domain\Equality;

class InMemoryBookingRepository implements BookingRepositoryInterface
{
    private BookingCollection $bookings;

    public function __construct()
    {
        $this->bookings = new BookingCollection(Booking::class);
    }

    public function store(Booking $booking): void
    {
        $this->bookings->add($booking);
    }

    public function findOneForGuestByActivityAtSpecificTime(Guest $guest, Activity $activity, \DateTimeImmutable $bookingDateTime): ?Booking
    {
        /** @var Booking $booking */
        foreach ($this->bookings as $booking) {
            if (
                Equality::guestsAreEqual($booking->leadGuest(), $guest) &&
                $booking->activityIdAsString() && $activity->idAsString() &&
                Equality::datesAreEqual($booking->beginsAt(), $bookingDateTime)
            ) {
                return $booking;
            }
        }

        return null;
    }
}
