<?php declare(strict_types=1);

namespace App\Booking\Entity;

use App\Booking\Exception\CouldNotMakeBooking;
use App\Booking\Entity\Collection\ActivityParticipantCollection;
use App\Booking\Repository\BookingRepositoryInterface;
use App\Shared\Domain\EntityId;
use App\Shared\Domain\EntityIdTrait;
use Assert\Assertion;
use DateTimeImmutable;

class Guest
{
    use EntityIdTrait;

    private string $firstName;
    private string $lastName;
    private DateTimeImmutable $dob;
    private DateTimeImmutable $arrivalDateTime;
    private DateTimeImmutable $checkoutDateTime;

    private function __construct(
        EntityId $id,
        string $firstName, // todo change to VO
        string $lastName, // todo change to VO
        DateTimeImmutable $dob, // todo change to VO
        DateTimeImmutable $arrivalDateTime,
        DateTimeImmutable $checkoutDateTime
    ) {
        Assertion::greaterThan($checkoutDateTime, $arrivalDateTime, 'Departure date must be after arrival date'); // todo unit test

        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->dob = $dob;
        $this->arrivalDateTime = $arrivalDateTime;
        $this->checkoutDateTime = $checkoutDateTime;
    }

    public static function visitingBetween(
        EntityId $id,
        string $firstName, // todo change to VO
        string $lastName, // todo change to VO
        DateTimeImmutable $dob, // todo change to VO
        DateTimeImmutable $arrivalDateTime,
        DateTimeImmutable $checkoutDateTime
    ): self
    {
        return new self($id, $firstName, $lastName, $dob, $arrivalDateTime, $checkoutDateTime);
    }

    public function makeBookingFor(ActivitySlot $activitySlot, ActivityParticipantCollection $activityParticipants, BookingRepositoryInterface $bookingRepository): void
    {
        $booking = Booking::create($activitySlot, $this, $activityParticipants);
        $bookingRepository->store($booking);
    }

    public function age(): int
    {
        $today = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (new DateTimeImmutable())->format('Y-m-d 00:00:00'));
        return $this->dob->diff($today)->y;
    }

    public function arrivesAt(): DateTimeImmutable
    {
        return $this->arrivalDateTime;
    }

    public function checksOutAt(): DateTimeImmutable
    {
        return $this->checkoutDateTime;
    }
}
