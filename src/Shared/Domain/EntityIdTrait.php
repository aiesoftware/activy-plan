<?php declare(strict_types=1);

namespace App\Shared\Domain;

trait EntityIdTrait
{
    private EntityId $id;

    public function idAsEntity(): EntityId
    {
        return $this->id;
    }

    public function idAsString(): string
    {
        return $this->id->value();
    }
}
