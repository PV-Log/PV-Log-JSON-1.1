<?php

namespace PVLog\Json\Tests;

use PVLog\Classes\Json\Inverter;
use PVLog\Classes\Json\Strings;
use PVLog\Classes\Json\Set;

/**
 *
 */
class StringTest extends \PHPUnit_Framework_TestCase {

    protected $string;

    /**
     *
     */
    protected function setUp() {
        $this->string = new Strings;
    }

    /**
     *
     */
    public function testPowerAcWatts() {
        $this->string->addPowerAcWatts('2000-01-01', 1);
        $this->assertEquals(1, $this->string->countPowerAcWatts());

        $this->string->setPowerAcWatts(array('2000-01-01' => 1));
        $this->assertEquals(1, $this->string->countPowerAcWatts());

        $this->string->setPowerAcWatts(new Set(array('2000-01-01' => 1)));
        $this->assertEquals(1, $this->string->countPowerAcWatts());
        $this->assertInstanceOf('PVLog\Classes\Json\Set', $this->string->getPowerAcWatts());

        $this->string->setPowerAcWatts(0);
        $this->assertEquals(1, $this->string->countPowerAcWatts());
    }

    /**
     *
     */
    public function testAddToInverter() {
        $r = $this->string->addToInverter(new Inverter);
        $this->assertInstanceOf('PVLog\Classes\Json\Strings', $r);
    }
}
