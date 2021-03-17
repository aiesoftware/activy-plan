<?php declare(strict_types=1);

namespace Tests\Unit\Booking\ValueObject;

use App\Booking\Entity\ActivitySlot;
use Assert\InvalidArgumentException;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class SlotTest extends TestCase
{
    public function testInvalidDuration(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Duration given is not a valid date modifier string');
        ActivitySlot::initialiseFromDuration(new DateTimeImmutable('2020-04-30'), 'foo', 1);
    }

    /**
     * @dataProvider invalidCapacities
     */
    public function testInvalidCapacity(int $capacity): void
    {
        $this->expectException(InvalidArgumentException::class);
        ActivitySlot::initialiseFromDuration(new DateTimeImmutable('2020-04-30'), '1 hour', $capacity);
    }

    public function invalidCapacities(): array
    {
        return [[0], [-1]];
    }

    public function testStripsLeadingOperatorIfGiven(): void
    {
        $slot = ActivitySlot::initialiseFromDuration(new DateTimeImmutable('2020-04-30'), '+ 1 hour', 1);
        $this->assertInstanceOf(ActivitySlot::class, $slot);

        $slot = ActivitySlot::initialiseFromDuration(new DateTimeImmutable('2020-04-30'), '- 1 hour', 1);
        $this->assertInstanceOf(ActivitySlot::class, $slot);
    }
}
