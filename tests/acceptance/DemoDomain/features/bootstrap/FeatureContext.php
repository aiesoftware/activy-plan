<?php

namespace Tests\Acceptance\DemoDomain\features\bootstrap;

use App\DemoDomain\Service\DemoDomainService;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private DemoDomainService $demoService;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct(DemoDomainService $demoService)
    {
        $this->demoService = $demoService;
    }

    /**
     * @Given there is a service with a dependency
     */
    public function thereIsAServiceWithADependency()
    {
        print_r($this->demoService);
    }
}
