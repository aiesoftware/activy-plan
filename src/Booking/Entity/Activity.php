<?php declare(strict_types=1);

namespace App\Booking\Entity;

use App\Booking\Entity\Collection\ActivitySlotCollection;
use App\Shared\Domain\EntityId;
use App\Shared\Domain\EntityIdTrait;

class Activity
{
    use EntityIdTrait;

    private string $name;

    private ActivitySlotCollection $assignedActivitySlots;

    private function __construct(EntityId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->assignedActivitySlots = new ActivitySlotCollection(ActivitySlot::class);
    }

    public static function named(EntityId $id, string $name): self
    {
         return new self($id, $name);
    }

    public function assignActivitySlot(ActivitySlot $activitySlot)
    {
        if (!$this->assignedActivitySlots->contains($activitySlot)) {
            $this->assignedActivitySlots->add($activitySlot);
        }
    }





//    public function reservePlacesOnSlot(DateTimeImmutable $slotStartDate, Guest $leadGuest, ?GuestCollection $additionalGuests = null): void
//    {
//        $additionalGuests = ($additionalGuests === null) ? new GuestCollection(Guest::class) : $additionalGuests;
//        $allGuests = clone $additionalGuests;
//        $allGuests->add($leadGuest);
//
//        if ($this->guestViolatesAgeRestriction($allGuests)) {
//            $exception = CouldNotReserveBooking::guestBelowMinimumAgeLimit($this->minimumAgeLimit);
//            throw new $exception;
//        }
//
//        $this->findSlotForDate($slotStartDate)->reservePlaces($leadGuest, $additionalGuests);
//    }

//    private function guestViolatesAgeRestriction(GuestCollection $guests): bool
//    {
//        /** @var Guest $guest */
//        foreach ($guests as $guest) {
//            if ($guest->age() < $this->minimumAgeLimit) {
//                return true;
//            }
//        }
//
//        return false;
//    }
//
//    public function confirmBookingOnSlot(DateTimeImmutable $slotStartDate, Guest $leadGuest): void
//    {
//        $this->findSlotForDate($slotStartDate)->confirmBooking($leadGuest);
//    }
//
//    public function hasPlacesAvailableOnSlot(DateTimeImmutable $slotStartDate, int $requiredPlaces): bool
//    {
//        return $this->findSlotForDate($slotStartDate)->hasPlacesAvailable($requiredPlaces);
//    }
//
//    public function numPlacesAvailableOnSlot(DateTimeImmutable $startDate): int
//    {
//        return $this->findSlotForDate($startDate)->numPlacesAvailable();
//    }
//
//    private function findSlotForDate(DateTimeImmutable $startDate): ActivitySlot
//    {
//        $matchingSlots = array_filter($this->slots, function (ActivitySlot $slot) use ($startDate) {
//            return Equality::datesAreEqual($slot->beginsAt(), $startDate);
//        });
//
//        if (empty($matchingSlots)) {
//            throw new \Exception('Could not find matching activity slot for given state date');
//        }
//
//        if (count($matchingSlots) > 1) {
//            throw new \Exception('Activity contains two slots with exactly the same start date');
//        }
//
//        return $matchingSlots[0];
//    }
}
