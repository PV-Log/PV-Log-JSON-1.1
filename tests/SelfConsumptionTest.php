<?php

namespace PVLog\Json\Tests;

use PVLog\Classes\Json\Properties;
use PVLog\Classes\Json\Json;
use PVLog\Classes\Json\SelfConsumption;

/**
 *
 */
class SelfConsumptionTest extends \PHPUnit_Framework_TestCase {

    protected $sc;

    /**
     *
     */
    protected function setUp() {
        $this->sc = new SelfConsumption;
    }

    /**
     *
     */
    public function testPowerAcWatts() {
        $this->sc->addPowerAcWatts('2000-01-01 00:00:00', 0);
        $this->sc->addPowerAcWatts('2000-01-01 00:05:00', 36);
        $this->sc->addPowerAcWatts('2000-01-01 00:10:00', 36);
        $this->sc->interpolate();

        // Remove leading 0 from powerAcWatts
        $expected = array (
                        Properties::ENERGY => 6,
                        Properties::POWER => array (
                            '2000-01-01 00:05:00' => 36,
                            '2000-01-01 00:10:00' => 36,
                        ),
                    );
        $this->assertEquals($expected, $this->sc->asArray(Json::EXPORT_POWER | Json::DATETIME));
    }
}
