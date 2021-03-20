<?php declare(strict_types=1);

namespace App\Booking\Entity;

use App\Booking\Entity\Collection\ActivityParticipantCollection;

class Booking
{
    private ActivitySlot $activitySlot;
    private Guest $leadGuest;
    private ActivityParticipantCollection $activityParticipants;

    private function __construct(
        ActivitySlot $activitySlot,
        Guest $leadGuest,
        ActivityParticipantCollection $activityParticipants
    )
    {
        $this->activitySlot = $activitySlot;
        $this->leadGuest = $leadGuest;
        $this->activityParticipants = $activityParticipants;}

    public static function create(
        ActivitySlot $activitySlot,
        Guest $leadGuest,
        ActivityParticipantCollection $activityParticipants
    ): self
    {
        return new self($activitySlot, $leadGuest, $activityParticipants);
    }

    public function leadGuest(): Guest
    {
        return $this->leadGuest;
    }

    public function beginsAt(): \DateTimeImmutable
    {
        return $this->activitySlot->beginsAt();
    }

    public function activityIdAsString(): string
    {
        return $this->activitySlot->activityIdAsString();
    }
}
