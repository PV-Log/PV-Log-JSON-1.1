<?php

namespace PVLog\Json\Tests;

use PVLog\Classes\Json\Properties;
use PVLog\Classes\Json\Instance;
use PVLog\Classes\Json\FeedIn;

/**
 * Use feedIn property for test of EnergyMater properties of an Instance
 */
class EnergyMeterInInstanceTest extends \PHPUnit_Framework_TestCase {

    protected $instance;

    /**
     *
     */
    protected function setUp() {
        $this->instance = new Instance;
    }

    /**
     *
     */
    public function testEnergyMaterIsInitialEmpty() {
        $this->assertNull($this->instance->getFeedIn());
    }

    /**
     *
     */
    public function testEnergyMaterCreateFromDataArray() {
        $feedin = new FeedIn(array(
            Properties::POWER => array(
                '2000-01-01 00:00:00' => 1000
            ),
            Properties::ENERGY => array(
                '2000-01-01 00:00:00' => 1000
            )
        ));
        $this->instance->setFeedIn($feedin);
        $this->assertInstanceOf('PVLog\Classes\Json\FeedIn', $this->instance->getFeedIn());
    }

    /**
     *
     */
    public function testEnergyMaterCreatedManually() {
        $feedin = new FeedIn;
        $feedin->addPowerAcWatts('2000-01-01 00:00:00', 1000);
        $feedin->addTotalWattHours('2000-01-01 00:00:00', 1000);

        $this->instance->setFeedIn($feedin);
        $this->assertInstanceOf('PVLog\Classes\Json\FeedIn', $this->instance->getFeedIn());

        $feedin->setToInstance($this->instance);
        $this->assertInstanceOf('PVLog\Classes\Json\FeedIn', $this->instance->getFeedIn());

        $this->instance->feedIn = $feedin;
        $this->assertInstanceOf('PVLog\Classes\Json\FeedIn', $this->instance->getFeedIn());
        $this->assertInstanceOf('PVLog\Classes\Json\FeedIn', $this->instance->feedIn);
    }

}
