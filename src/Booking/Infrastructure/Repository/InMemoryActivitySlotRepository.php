<?php declare(strict_types=1);

namespace App\Booking\Infrastructure\Repository;

use App\Booking\Entity\Guest;
use App\Booking\Repository\ActivitySlotRepositoryInterface;

class InMemoryActivitySlotRepository implements ActivitySlotRepositoryInterface
{
    public function findBy(array $criteria): Guest
    {
        // TODO: Implement findBy() method.
    }
}
