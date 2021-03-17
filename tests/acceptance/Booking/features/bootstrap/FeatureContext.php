<?php

namespace Tests\Acceptance\Booking\features\bootstrap;

use App\Booking\Command\CreateBookingCommand;
use App\Booking\Entity\Activity;
use App\Booking\Entity\ActivityParticipant;
use App\Booking\Entity\Collection\ActivityParticipantCollection;
use App\Booking\Entity\Guest;
use App\Booking\Entity\Park;
use App\Booking\Exception\CouldNotMakeBooking;
use App\Booking\Entity\ActivitySlot;
use App\Booking\Infrastructure\Repository\InMemoryBookingRepository;
use App\Booking\Repository\BookingRepositoryInterface;
use App\Booking\Service\BookingService;
use App\Shared\Domain\EntityId;
use App\Shared\Infrastructure\Uuid4Generator;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use DateTimeImmutable;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private array $parks;
    private Activity $activity;
    private ActivitySlot $activitySlot;

    private Guest $guest;
    private string $guestFirstName;
    private string $guestLastName;
    private string $guestAge;

    private BookingRepositoryInterface $bookingRepository;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->bookingRepository = new InMemoryBookingRepository();
    }

    /**
     * @Given a Park named :parkName
     */
    public function createPark($parkName)
    {
        $this->parks[$parkName][] = Park::named($parkName);
    }

    /**
     * @Given the Park has a :activityName Activity
     */
    public function createActivityWithAgeRestriction($activityName)
    {
        $this->activity = Activity::named(EntityId::generate(new Uuid4Generator()), $activityName);
    }

    /**
     * @Given the Activity has an Activity Slot at :activityDateTime lasting :activityDuration hour with a minimum age limit of :minimumAgeLimit and :activityCapacity places available
     */
    public function createActivitySlotFromDuration($activityDateTime, $activityDuration, $activityCapacity, $activityMinimumAgeLimit)
    {
        $durationInMinutes = (int) $activityDuration * 60;

        $this->activitySlot = ActivitySlot::fromDurationWithAgeRestriction(
            $this->activity->idAsEntity(),
            new DateTimeImmutable($activityDateTime),
            $durationInMinutes,
            $activityCapacity,
            $activityMinimumAgeLimit
        );

        $this->activity->assignActivitySlot($this->activitySlot);
    }

    /**
     * @Given :guestName is a :guestAge year old Guest staying at the :parkName Park between :arrivalDateTime and :checkoutDateTime
     */
    public function createGuestVisitingBetween($guestName, $guestAge, $parkName, $arrivalDateTime, $checkoutDateTime)
    {
        $nameAsArray = explode(' ', $guestName);
        $this->guestFirstName = $nameAsArray[0];
        $this->guestLastName = $nameAsArray[1];
        $this->guestAge = $guestAge;

        $dob = DateTimeImmutable::createFromFormat(
            'Y-m-d H:i:s',
            (new DateTimeImmutable(sprintf('- %s years', $guestAge)))->format('Y-m-d 00:00:00')
        );

        $this->guest = Guest::visitingBetween(
            EntityId::generate(new Uuid4Generator()),
            $this->guestFirstName,
            $this->guestLastName,
            $dob,
            new DateTimeImmutable($arrivalDateTime),
            new DateTimeImmutable($checkoutDateTime)
        );
    }

    /**
     * @When he makes a Booking for himself on that Activity Slot
     */
    public function bookParticipantsOnToSlot()
    {
        $bookingService = new BookingService($this->bookingRepository);


        $participant = ActivityParticipant::create($this->guestFirstName, $this->guestLastName, (int) $this->guestAge);
        $participants = new ActivityParticipantCollection(ActivityParticipant::class, [$participant]);
        $createBookingCommand = new CreateBookingCommand(
            $this->guest,
            $this->activitySlot,
            $participants
        );

        $bookingService->bookParticipantsOnToActivitySlot($createBookingCommand);
    }

    /**
     * @Then the Booking should be added to his Planner
     */
    public function theBookingShouldBeAddedToHisPlanner()
    {
        $booking = $this->bookingRepository->findOneForGuestByActivityAtSpecificTime($this->guest, $this->activity, $this->activitySlot->beginsAt());

        if ($booking === null) {
            throw new \Exception();
        }
    }

    /**
     * @Then that Activity Slot should have :numPlaces places remaining
     */
    public function thatActivitySlotShouldHavePlacesRemaining($numPlaces)
    {
        $placesRemaining = $this->activitySlot->numPlacesAvailable();

        if ($placesRemaining !== (int) $numPlaces) {
            throw new \Exception();
        }
    }

    /**
     * @When he attempts to make a Booking on that Activity Slot
     */
    public function attemptToBookParticipantsOnToSlot()
    {
        try {
            $this->bookParticipantsOnToSlot();
        } catch (CouldNotMakeBooking $e) {
            return;
        }

        throw new \Exception(sprintf('Expected to catch %s Exception', CouldNotMakeBooking::class));
    }

    /**
     * @Then the Booking should not be added to his Planner
     */
    public function theBookingShouldNotBeAddedToHisPlanner()
    {
        $booking = $this->bookingRepository->findOneForGuestByActivityAtSpecificTime($this->guest, $this->activity, $this->activitySlot->beginsAt());

        if ($booking !== null) {
            throw new \Exception();
        }
    }
}
