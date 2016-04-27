<?php

namespace PVLog\Json\Tests;

use PVLog\Classes\Json\Properties;
use PVLog\Classes\Json\Json;
use PVLog\Classes\Json\GridConsumption;

/**
 *
 */
class GridConsumptionTest extends \PHPUnit_Framework_TestCase {

    protected $gc;

    /**
     *
     */
    protected function setUp() {
        $this->gc = new GridConsumption;
    }

    /**
     *
     */
    public function testPowerAcWatts() {
        $this->gc->addPowerAcWatts('2000-01-01 00:00:00', 36);
        $this->gc->addPowerAcWatts('2000-01-01 00:05:00', 36);
        $this->gc->addPowerAcWatts('2000-01-01 00:10:00', 0);
        $this->gc->interpolate();

        // Remove trailing 0 from powerAcWatts
        $expected = array (
                        Properties::ENERGY => 3,
                        Properties::POWER => array (
                            '2000-01-01 00:00:00' => 36,
                            '2000-01-01 00:05:00' => 36,
                            '2000-01-01 00:10:00' => 0
                        ),
                    );
        $this->assertEquals($expected, $this->gc->asArray(Json::EXPORT_POWER | Json::DATETIME));
    }
}
