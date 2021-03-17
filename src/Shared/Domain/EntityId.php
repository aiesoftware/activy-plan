<?php declare(strict_types=1);

namespace App\Shared\Domain;

use Ramsey\Uuid\UuidInterface;

class EntityId
{
    private UuidInterface $uuid;

    public static function generate(UuidGeneratorInterface $uuidGenerator): self
    {
        $object = new self;
        $object->uuid = $uuidGenerator->generate();
        return $object;
    }

    public function value(): string
    {
        return $this->uuid->toString();
    }
}
