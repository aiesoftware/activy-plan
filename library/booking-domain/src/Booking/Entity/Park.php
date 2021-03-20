<?php declare(strict_types=1);

namespace App\Booking\Entity;

use App\Booking\Entity\Collection\ActivityCollection;

class Park
{
    private string $name;
    private ActivityCollection $activitiesOnOffer;

    private function __construct()
    {
        $this->activitiesOnOffer = new ActivityCollection(Activity::class);
    }

    public static function named(string $name): self
    {
        $entity = new self();
        $entity->name = $name;
        return $entity;
    }

    public function offerActivity(Activity $activity): void
    {
        if (!$this->activitiesOnOffer->contains($activity)) {
            $this->activitiesOnOffer->add($activity);
        }
    }
}
