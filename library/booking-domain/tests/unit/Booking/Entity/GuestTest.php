<?php declare(strict_types=1);

namespace Tests\Unit\Booking\Entity;

use App\Booking\Entity\Guest;
use App\Shared\Domain\EntityId;
use App\Shared\Infrastructure\Uuid4Generator;
use Assert\InvalidArgumentException;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class GuestTest extends TestCase
{
    /**
     * @dataProvider invalidStayDates
     */
    public function testDepartureDateMustBeAfterArrivalDate($arrivalDateTime, $departureDateTime): void
    {
        $this->expectException(InvalidArgumentException::class);

        Guest::visitingBetween(
            EntityId::generate(new Uuid4Generator()),
            'David',
            new DateTimeImmutable('- 37 years'),
            $arrivalDateTime,
            $departureDateTime
        );
    }

    public function invalidStayDates(): array
    {
        return [
            [
                'arrivalDateTime' => new DateTimeImmutable('2025-01-01 16:00:00'),
                'departureDateTime' => new DateTimeImmutable('2025-01-01 16:00:00'),
            ],
            [
                'arrivalDateTime' => new DateTimeImmutable('2025-01-01 16:00:00'),
                'departureDateTime' => new DateTimeImmutable('2025-01-01 15:59:59'),
            ]
        ];
    }
}
