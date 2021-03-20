<?php

namespace App\Shared\Domain;

use Ramsey\Uuid\UuidInterface;

interface UuidGeneratorInterface
{
    public function generate(): UuidInterface;
}
