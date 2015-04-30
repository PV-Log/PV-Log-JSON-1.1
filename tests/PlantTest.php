<?php

namespace PVLog\Json\Tests;

use PVLog\Classes\Json\Json;
use PVLog\Classes\Json\Instance;
use PVLog\Classes\Json\Plant;
use PVLog\Classes\Json\Inverter;
use PVLog\Classes\Json\Strings;

/**
 *
 */
class PlantTest extends \PHPUnit_Framework_TestCase {

    protected $plant;

    /**
     *
     */
    protected function setUp() {
        $this->plant = new Plant;
    }

    /**
     *
     */
    public function testInvertersProperty() {
        $inverter = new Inverter();

        $plant = $this->plant->addInverter($inverter);
        $this->assertInstanceOf('PVLog\Classes\Json\Plant', $plant);
        $this->assertEquals(1, $plant->countInverters());

        $plant = $this->plant->setInverters(array($inverter));
        $this->assertInstanceOf('PVLog\Classes\Json\Plant', $plant);
        $this->assertEquals(1, $plant->countInverters());
        $this->assertCount(1, $plant->getInverters());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidInverters1() {
        $this->plant->setInverters(array(0));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidInverters2() {
        $this->plant->inverter = 0;
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidInverters3() {
        $this->plant->inverter = array(0);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddInvalidProperty() {
        $this->plant->add('invalid', 0);
    }

    /**
     *
     */
    public function testInterpolateMultipleInvertes1() {
        $this->plant = new Plant;

        // Inverter 1
        $inverter = new Inverter;
        $inverter->addPowerAcWatts('2000-01-01 00:00:00', 600)
                 ->addPowerAcWatts('2000-01-01 00:05:00', 600);

        $this->plant->addInverter($inverter);

        // Inverter 1
        $inverter = new Inverter;
        $inverter->addPowerAcWatts('2000-01-01 00:05:00', 600)
                 ->addPowerAcWatts('2000-01-01 00:10:00', 600);

        $instance = new Instance;
        $instance->setPlant($this->plant->addInverter($inverter));
        $this->assertJsonStringEqualsJsonFile(__DIR__.'/files/interpolate.multi.inverters.1.json', $instance->asJson(TRUE));
    }

    /**
     *
     */
    public function testInterpolateMultipleInvertes2() {
        $this->plant = new Plant;

        // Inverter 1
        $inverter = new Inverter;
        $inverter->addPowerAcWatts('2000-01-01 00:05:00', 600)
                 ->addPowerAcWatts('2000-01-01 00:10:00', 600);

        $this->plant->addInverter($inverter);

        // Inverter 1
        $inverter = new Inverter;
        $inverter->addPowerAcWatts('2000-01-01 00:00:00', 600)
                 ->addPowerAcWatts('2000-01-01 00:05:00', 600);

        $instance = new Instance;
        $instance->setPlant($this->plant->addInverter($inverter));
        $this->assertJsonStringEqualsJsonFile(__DIR__.'/files/interpolate.multi.inverters.2.json', $instance->asJson(TRUE));
    }

    /**
     *
     */
    public function testSetToInstance() {
        $plant = $this->plant->setToInstance(new Instance);
        $this->assertInstanceOf('PVLog\Classes\Json\Plant', $plant);
    }
}
