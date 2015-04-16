<?php

namespace PVLog\Json\Tests;

use PVLog\Classes\Json\Json;
use PVLog\Classes\Json\Instance;
use PVLog\Classes\Json\FeedIn;

/**
 *
 */
class EnergyMeterTest extends \PHPUnit_Framework_TestCase {

    protected $em;

    protected function setUp() {
        // Use a non abstract direct successor of EnergyMeter
        $this->em = new FeedIn;
    }

    /**
     *
     */
    public function testTotalWattHoursCalculation() {
        $this->em->addPowerAcWatts('2000-01-01 00:05:00', 0)
                 ->addPowerAcWatts('2000-01-01 01:00:00', 1000)
                 ->interpolate();

        $array = $this->em->asArray(FeedIn::EXPORT_POWER);
        $this->assertEquals(500, $array['totalWattHours']);
    }

    /**
     *
     */
    public function testPowerAcWattsCalculation() {
        $this->em->addTotalWattHours('2000-01-01 00:00:00', 0)
                   // 10 min 100 Wh > 600 W
                 ->addTotalWattHours('2000-01-01 00:10:00', 100)
                   // 10 min 200 Wh > 1200 W
                 ->addTotalWattHours('2000-01-01 00:20:00', 300);

        // Force interpolation manually
        $this->em->interpolate();

        $result = $this->em->asArray(Json::EXPORT_POWER | FeedIn::DATETIME);

        $expected = array (
            // 10 min 100 Wh > 600 W
            '2000-01-01 00:05:00' => 600,
            '2000-01-01 00:10:00' => 600,
            // 10 min 200 Wh > 1200 W
            '2000-01-01 00:15:00' => 1200,
            '2000-01-01 00:20:00' => 1200,
        );

        $this->assertEquals($expected, $result['powerAcWatts']);
    }

    /**
     *
     */
    public function testAllEnergyMeterSuccessors() {
        $instance = new Instance;

        $names = array('feedIn', 'gridConsumption', 'totalConsumption', 'selfConsumption');

        foreach ($names as $name) {

            $obj = Json::factory($name);

            // Test Setter/Getter
            $method = 'set'.ucwords($name);
            $instance->$method($obj);

            $method = 'get'.ucwords($name);
            $this->assertInstanceOf('PVLog\Classes\Json\\'.$name, $instance->$method());

            $instance->set($name, $obj);
            $this->assertInstanceOf('PVLog\Classes\Json\\'.$name, $instance->get($name));

            // Test magic __set()/__get()
            $instance->$name = $obj;
            $this->assertInstanceOf('PVLog\Classes\Json\\'.$name, $instance->$name);

            $this->assertEquals($obj, $obj->setToInstance($instance));
        }
    }


}
