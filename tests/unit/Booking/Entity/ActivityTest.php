<?php declare(strict_types=1);

namespace Tests\Unit\Booking\Entity;

use App\Booking\Collection\GuestCollection;
use App\Booking\Entity\Activity;
use App\Booking\Entity\Guest;
use App\Booking\Entity\ActivitySlot;
use App\Shared\Domain\EntityId;
use App\Shared\Infrastructure\Uuid4Generator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ActivityTest extends TestCase
{
    private Activity $sut;

    /**
     * @dataProvider availabilityVariations
     */
    public function testHasPlacesAvailable(
        int $requiredPlaces,
        int $capacity,
        int $placesAlreadyReserved,
        bool $shouldHavePlacesAvailable
    ): void
    {
        $slotStartDate = new DateTimeImmutable();
        $this->sut = Activity::initialise(
            'rock climbing',
            16,
            ActivitySlot::initialiseFromDuration($slotStartDate, '1 hour', $capacity)
        );

        $leadGuest = $this->createGuest();

        if ($placesAlreadyReserved > 0) {
            $this->ensureActivityHasExistingReservations($placesAlreadyReserved, $slotStartDate, $leadGuest);
        }

        $this->assertEquals($shouldHavePlacesAvailable, $this->sut->hasPlacesAvailableOnSlot($slotStartDate, $requiredPlaces));
    }

    public function availabilityVariations(): array
    {
        return [
            [
                'requiredPlaces' => 3,
                'capacity' => 5,
                'placesAlreadyReserved' => 1,
                'shouldHaveAvailablePlaces' => true
            ],
            [
                'requiredPlaces' => 3,
                'capacity' => 3,
                'placesAlreadyReserved' => 0,
                'shouldHaveAvailablePlaces' => true
            ],
            [
                'requiredPlaces' => 3,
                'capacity' => 3,
                'placesAlreadyReserved' => 1,
                'shouldHaveAvailablePlaces' => false
            ],
            [
                'requiredPlaces' => 3,
                'capacity' => 2,
                'placesAlreadyReserved' => 0,
                'shouldHaveAvailablePlaces' => false
            ]
        ];
    }

    private function createGuest(): Guest
    {
        return Guest::visitingBetween(
            EntityId::generate(new Uuid4Generator()),
            'Alice',
            new DateTimeImmutable('- 34 years'),
            new DateTimeImmutable(),
            new DateTimeImmutable('+ 1 week')
        );
    }

    private function ensureActivityHasExistingReservations(
        int $placesAlreadyReserved,
        DateTimeImmutable $slotStartDate,
        Guest $leadGuest
    ): void
    {
        $guests = new GuestCollection(Guest::class);
        for ($i = 1; $i < $placesAlreadyReserved; $i++) {
            $guests->add($this->createGuest());
        }

        $this->sut->reservePlacesOnSlot($slotStartDate, $leadGuest, $guests);
    }
}
