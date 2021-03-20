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

    public function assignActivitySlot(ActivitySlot $activitySlot): void
    {
        if (!$this->assignedActivitySlots->contains($activitySlot)) {
            $this->assignedActivitySlots->add($activitySlot);
        }
    }
}
