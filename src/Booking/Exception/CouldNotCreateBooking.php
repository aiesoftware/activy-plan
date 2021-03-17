<?php declare(strict_types=1);

namespace App\Booking\Exception;

class CouldNotCreateBooking extends \RuntimeException
{
    public static function participantBelowMinimumAgeLimit(int $minimumAgeLimit): self
    {
        return new self(sprintf('Minimum age limit for activity is %s.', $minimumAgeLimit));
    }

    public static function insufficientPlacesAvailable(): self
    {
        return new self('Insufficient places available on activity slot.');
    }
}
