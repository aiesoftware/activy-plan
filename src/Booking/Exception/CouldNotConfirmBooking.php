<?php declare(strict_types=1);

namespace App\Booking\Exception;

use App\Shared\Domain\EntityId;

class CouldNotConfirmBooking extends \RuntimeException
{
    public static function reservationNotFound(EntityId $guestId): self
    {
        return new self(sprintf('Unable to confirm booking for guest id: %s as no reservation exists for guest', $guestId->value()));
    }

    public static function insufficientPlacesAvailable(): self
    {
        return new self('Insufficient places available on activity slot.');
    }
}
