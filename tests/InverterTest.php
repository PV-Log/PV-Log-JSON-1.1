<?php

namespace PVLog\Json\Tests;

use PVLog\Classes\Json\Plant;
use PVLog\Classes\Json\Inverter;
use PVLog\Classes\Json\Temperature;
use PVLog\Classes\Json\Strings;
use PVLog\Classes\Json\Set;

/**
 *
 */
class InverterTest extends \PHPUnit_Framework_TestCase {

    protected $inverter;

    /**
     *
     */
    protected function setUp() {
        $this->inverter = new Inverter;
    }

    /**
     *
     */
    public function testTotalWattHours() {
        $inverter = $this->inverter->addTotalWattHours('2000-01-01', 1);
        $this->assertInstanceOf('PVLog\Classes\Json\Inverter', $inverter);
        $this->assertEquals(1, $inverter->countTotalWattHours());

        $inverter = $this->inverter->setTotalWattHours(array('2000-01-01' => 1));
        $this->assertInstanceOf('PVLog\Classes\Json\Inverter', $inverter);

        $this->assertEquals(1, $inverter->countTotalWattHours());
      
        $this->inverter->setTotalWattHours(new Set(array('2000-01-01' => 1)));

        $this->assertEquals(1, $inverter->countTotalWattHours());
        $this->assertInstanceOf('PVLog\Classes\Json\Set', $inverter->getTotalWattHours());

        $inverter->setTotalWattHours(0);
        $this->assertEquals(1, $inverter->countTotalWattHours());
    }

    /**
     *
     */
    public function testStringsProperty() {
        $string = new Strings;

        $inverter = $this->inverter->addString($string);
        $this->assertInstanceOf('PVLog\Classes\Json\Inverter', $inverter);

        $this->assertEquals(1, $inverter->countStrings());

        $inverter = $this->inverter->setStrings(array($string));
        $this->assertInstanceOf('PVLog\Classes\Json\Inverter', $inverter);

        $this->assertEquals(1, $inverter->countStrings());
        $this->assertCount(1, $inverter->getStrings());

        $this->inverter->strings = array($string);
        $this->assertCount(1, $inverter->strings);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidStrings1() {
        $this->inverter->setStrings(array(0));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidStrings2() {
        $this->inverter->strings = 0;
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidStrings3() {
        $this->inverter->strings = array(0);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidProperty() {
        $this->inverter->add('invalid', 0);
    }

    /**
     *
     */
    public function testSetGetTemperature() {
        $this->assertNull($this->inverter->getTemperature());

        $this->inverter->setTemperature(new Temperature);
        $this->assertInstanceOf('PVLog\Classes\Json\Temperature', $this->inverter->getTemperature());

        $this->inverter->temperature = new Temperature;
        $this->assertInstanceOf('PVLog\Classes\Json\Temperature', $this->inverter->temperature);
    }

    /**
     *
     */
    public function testAddToPlant() {
        $inverter = $this->inverter->addToPlant(new Plant);
        $this->assertInstanceOf('PVLog\Classes\Json\Inverter', $inverter);
    }
}
