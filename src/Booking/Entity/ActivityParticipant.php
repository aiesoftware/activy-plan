<?php declare(strict_types=1);

namespace App\Booking\Entity;

use Assert\Assertion;

class ActivityParticipant
{
    private string $firstName;
    private string $lastName;
    private int $age;

    private function __construct(string $firstName, string $lastName, int $age)
    {
        Assertion::greaterThan($age, -1);
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->age = $age;
    }

    public static function create(string $firstName, string $lastName, int $age): self
    {
        return new self($firstName, $lastName, $age);
    }

    public function age(): int
    {
        return $this->age;
    }
}
