<?php declare(strict_types=1);

namespace App\Booking\Command;

use App\Booking\Entity\Activity;
use App\Booking\Entity\ActivitySlot;
use App\Booking\Entity\Collection\ActivityParticipantCollection;
use App\Booking\Entity\Guest;
use DateTimeImmutable;

class CreateBookingCommand
{
    public Guest $guest;
    public ActivitySlot $activitySlot;
    public ActivityParticipantCollection $activityParticipants;

    public function __construct(
        Guest $guest,
        ActivitySlot $activitySlot,
        ActivityParticipantCollection $activityParticipants
    )
    {
        $this->guest = $guest;
        $this->activitySlot = $activitySlot;
        $this->activityParticipants = $activityParticipants;
    }
}
