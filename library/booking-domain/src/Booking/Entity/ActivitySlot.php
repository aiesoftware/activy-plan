<?php declare(strict_types=1);

namespace App\Booking\Entity;

use App\Booking\Entity\Collection\ActivityParticipantCollection;
use App\Booking\Exception\CouldNotMakeBooking;
use App\Shared\Domain\EntityId;
use Assert\Assertion;
use DateTimeImmutable;

class ActivitySlot
{
    private EntityId $activityId;
    private DateTimeImmutable $startDateTime;
    private DateTimeImmutable $endDateTime;
    private ?int $minimumAgeLimit = null;
    private int $capacity;
    private ActivityParticipantCollection $activityParticipants;

    private function __construct(
        EntityId $activityId,
        DateTimeImmutable $startDateTime,
        DateTimeImmutable $endDateTime,
        int $capacity
    )
    {
        Assertion::greaterThan($capacity, -1); // todo unit test
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
        Assertion::greaterThan($duration, 0); // todo unit test

        $endDateTime = $startDateTime->modify(sprintf('+ %s minutes', $duration)); // todo unit test
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

    public function bookParticipants(Guest $guest, ActivityParticipantCollection $activityParticipants): void
    {
        if ($this->hasAgeRestriction()) {
            $this->verifyParticipantAges($activityParticipants);
        }

        if (!$this->hasPlacesAvailable($activityParticipants->count())) {
            $exception = CouldNotMakeBooking::insufficientPlacesAvailable(); // todo unit test
            throw new $exception;
        }

        if ($this->activityBeginsOutsideOfGuestsStayWindow($guest)) {
            $exception = CouldNotMakeBooking::activitySlotOutsideOfGuestsStayWindow(); // todo unit test
            throw new $exception;
        }

        $this->doAssign($activityParticipants);
    }

    private function hasAgeRestriction(): bool
    {
        return $this->minimumAgeLimit !== null;
    }

    private function verifyParticipantAges(ActivityParticipantCollection $activityParticipants): void
    {
        foreach ($activityParticipants as $activityParticipant) {
            if ($this->participantViolatesAgeRestriction($activityParticipant)) { // todo unit test
                $exception = CouldNotMakeBooking::participantBelowMinimumAgeLimit($this->minimumAgeLimit);
                throw new $exception;
            }
        }
    }

    private function participantViolatesAgeRestriction(ActivityParticipant $activityParticipant): bool
    {
        return $activityParticipant->age() < $this->minimumAgeLimit;
    }

    private function hasPlacesAvailable(int $requiredPlaces): bool
    {
        return $this->numPlacesAvailable() >= $requiredPlaces;
    }

    public function numPlacesAvailable(): int
    {
        return $this->capacity - count($this->activityParticipants);
    }

    private function activityBeginsOutsideOfGuestsStayWindow(Guest $guest): bool
    {
        $diffInDaysBetweenStartDateAndCheckoutDate = $guest->checksOutAt()->diff($this->beginsAt())->d;
        if ($diffInDaysBetweenStartDateAndCheckoutDate > 0 && $this->beginsAt() > $guest->checksOutAt() ) {
            return true;
        }

        if ($this->beginsAt() < $guest->arrivesAt()) {
            return true;
        }

        return false;
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
}
