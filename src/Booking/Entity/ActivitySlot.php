<?php declare(strict_types=1);

namespace App\Booking\Entity;

use App\Booking\Entity\Collection\ActivityParticipantCollection;
use App\Booking\Exception\CouldNotCreateBooking;
use App\Shared\Domain\EntityId;
use Assert\Assertion;
use DateTimeImmutable;

class ActivitySlot
{
    private EntityId $activityId;
    private DateTimeImmutable $startDateTime;
    private DateTimeImmutable $endDateTime;
    private bool $hasAgeRestriction;
    private int $minimumAgeLimit;
    private int $capacity;
    private ActivityParticipantCollection $activityParticipants;

    private function __construct(
        EntityId $activityId,
        DateTimeImmutable $startDateTime,
        DateTimeImmutable $endDateTime,
        int $capacity
    )
    {
        Assertion::greaterThan($capacity, 0);
        $this->activityId = $activityId;
        $this->startDateTime = $startDateTime;
        $this->endDateTime = $endDateTime;
        $this->capacity = $capacity;

        $this->minimumAgeLimit = 120;
        $this->hasAgeRestriction = false;
        $this->activityParticipants = new ActivityParticipantCollection(ActivityParticipant::class);
    }

    public static function fromDurationWithAgeRestriction(
        EntityId $activityId,
        DateTimeImmutable $startDateTime,
        int $duration,
        int $capacity,
        int $minimumAgeLimit
    ): self
    {
        Assertion::greaterThan($duration, 0);

        $endDateTime = $startDateTime->modify(sprintf('+ %s minutes', $duration));
        if ($endDateTime === false) {
            throw new \InvalidArgumentException('Duration given does not yield a valid date modifier string');
        }

        $entity = new self($activityId, $startDateTime, $endDateTime, $capacity);
        $entity->minimumAgeLimit = $minimumAgeLimit;

        return $entity;
    }

    public function beginsAt(): DateTimeImmutable
    {
        return $this->startDateTime;
    }

    public function bookParticipantsOnToSlot(Guest $guest, ActivityParticipantCollection $activityParticipants): void
    {
        if ($this->hasAgeRestriction) {
            $this->verifyParticipantAges($activityParticipants);
        }

        if (!$this->hasPlacesAvailable($activityParticipants->count())) {
            $exception = CouldNotCreateBooking::insufficientPlacesAvailable();
            throw new $exception;
        }

        $this->doAssign($activityParticipants);
    }

    private function verifyParticipantAges(ActivityParticipantCollection $activityParticipants): void
    {
        foreach ($activityParticipants as $activityParticipant) {
            if ($this->participantViolatesAgeRestriction($activityParticipant)) {
                $exception = CouldNotCreateBooking::participantBelowMinimumAgeLimit($this->minimumAgeLimit);
                throw new $exception;
            }
        }
    }

    public function participantViolatesAgeRestriction(ActivityParticipant $activityParticipant): bool
    {
        return $activityParticipant->age() < $this->minimumAgeLimit;
    }

    public function hasPlacesAvailable(int $requiredPlaces): bool
    {
        return $this->numPlacesAvailable() >= $requiredPlaces;
    }

    public function numPlacesAvailable(): int
    {
        return $this->capacity - count($this->activityParticipants);
    }

    private function doAssign(ActivityParticipantCollection $activityParticipants): void
    {
        foreach ($activityParticipants as $activityParticipant) {
            $this->activityParticipants->add($activityParticipant);
        }
    }

    public function activityIdAsString(): string
    {
        return $this->activityId->value();
    }
//
//    public function confirmBooking(Guest $leadGuest)
//    {
//        $requiredReservations = count($this->reservations[$leadGuest->idAsString()]);
//
//        if ($requiredReservations === 0) {
//            $exception = CouldNotConfirmBooking::reservationNotFound($leadGuest->idAsEntity());
//            throw new $exception;
//        }
//
//        if ($requiredReservations > $this->numPlacesAvailable()) {
//            $exception = CouldNotConfirmBooking::insufficientPlacesAvailable();
//            throw new $exception;
//        }
//
//        foreach ($this->reservations[$leadGuest->idAsString()] as $index => $guest) {
//            $this->confirmedBookings[] = $guest;
//            unset($this->reservations[$leadGuest->idAsString()][$index]);
//        }
//
//        unset($this->reservations[$leadGuest->idAsString()]);
//    }
//

//


}
