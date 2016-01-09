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
class MergeTest extends \PHPUnit_Framework_TestCase {

    /**
     *
     */
    protected $instance;

    /**
     *
     */
    public function setUp() {
        $this->instance = new Instance;
    }

    /**
     *
     */
    public function testMergeInstances() {
        // Add some data to "old" instance
        $string = new Strings;
        $string->addPowerAcWatts('2000-01-01 00:00:00', 0)
               ->addPowerAcWatts('2000-01-01 01:00:00', 1100);

        $inverter = new Inverter;
        $inverter->addPowerAcWatts('2000-01-01 00:00:00', 0)
                 ->addPowerAcWatts('2000-01-01 01:00:00', 1000)
                 ->addTotalWattHours('2000-01-01 00:00:00', 0)
                 ->addTotalWattHours('2000-01-01 01:00:00', 1000);

        // This really works :-)
        $this->instance->setPlant((new Plant)->addInverter($inverter->addString($string)));

        // Add some delta data as "new" instance
        $string = new Strings;
        $string->addPowerAcWatts('2000-01-01 02:00:00', 2200);

        $inverter = new Inverter;
        $inverter->addPowerAcWatts('2000-01-01 02:00:00', 2000);
        $inverter->addTotalWattHours('2000-01-01 02:00:00', 3000);

        $new = new Instance;
        // This really works :-)
        $new->setPlant((new Plant)->addInverter($inverter->addString($string)));

        // Build merged instance
        $this->instance->merge($new);

        $this->assertJsonStringEqualsJsonFile(
            __DIR__.'/files/merge.minutes.json',
            $this->instance->setTypeMinutes()->asJson(TRUE)
        );
        $this->assertJsonStringEqualsJsonFile(
            __DIR__.'/files/merge.days.json',
            $this->instance->setTypeDays()->asJson(TRUE)
        );
        $this->assertJsonStringEqualsJsonFile(
            __DIR__.'/files/merge.months.json',
            $this->instance->setTypeMonths()->asJson(TRUE)
        );
    }

    /**
     *
     */
    public function testMergeSameInstances() {
        $file = __DIR__.'/files/merge.minutes.json';
        $json = json_decode(file_get_contents($file), TRUE);

        $old = new Instance($json);
        $new = new Instance($json);

        // Merge with itself MUST NOT change anything
        $old->merge($new);
        $this->assertJsonStringEqualsJsonFile($file, $old->asJson(TRUE));
    }

    /**
     *
     */
    public function testMergeInstanceWithFile() {
        $file = __DIR__.'/files/merge.minutes.json';
        $json = json_decode(file_get_contents($file), TRUE);
        $old = new Instance($json);

        // "new" data
        $plant = new Plant;
        $plant->addPowerAcWatts('2000-01-01 02:05:00', 1000)
              ->addTotalWattHours('2000-01-01 02:05:00', 3100);

        $old->merge($this->instance->setPlant($plant));

        $file = __DIR__.'/files/merge.minutes.new.json';
        $this->assertJsonStringEqualsJsonFile($file, $old->asJson(TRUE));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testMergeInstancesDifferentVersionsFails() {
        // Fake wrong version, only indirect possible, no setter for version!
        $this->instance = new Instance(array_merge(
            $this->instance->asArray(),
            array('version' => '1.0')
        ));

        // Will throw an exception because of different versions
        $this->instance->merge(new Instance);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testMergeInstancesDifferentFileContentFails() {
        // Will throw an exception because of different file content
        $this->instance->setTypeMinutes()->merge((new Instance)->setTypeDay());
    }

}
