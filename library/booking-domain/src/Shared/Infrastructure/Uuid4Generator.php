<?php

namespace App\Shared\Infrastructure;

use App\Shared\Domain\UuidGeneratorInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Uuid4Generator implements UuidGeneratorInterface
{
    public function generate(): UuidInterface
    {
        return Uuid::uuid4();
    }
}
