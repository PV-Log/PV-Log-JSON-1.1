<?php

namespace PVLog\Json\Tests;

use PVLog\Classes\Json\Json;
use PVLog\Classes\Json\Instance;
use PVLog\Classes\Json\Plant;
use PVLog\Classes\Json\Inverter;
use PVLog\Classes\Json\Strings;
use PVLog\Classes\Json\Irradiation;
use PVLog\Classes\Json\Temperature;

/**
 *
 */
class InstanceTest extends \PHPUnit_Framework_TestCase {

    protected $instance;

    /**
     *
     */
    protected function setUp() {
        $this->instance = new Instance();
        $this->instance->setPrettyJson(TRUE);
    }

    /**
     *
     * /
    public function testTestInstance() {
        $file = __DIR__.'/files/test.json';
        print_r((new Instance(json_decode(file_get_contents($file), TRUE)))->asArray(Json::DATETIME));
    }

    /**
     *
     */
    public function testPlainInstance() {
        $this->assertInstanceOf('PVLog\Classes\Json\Instance', $this->instance);

        $c = (new \ReflectionClass($this))->getShortName();

        $file1 = __DIR__.'/files/'.$c.'.json';
        $this->assertJsonStringEqualsJsonFile($file1, $this->instance->asJson(TRUE));
        // Test __toString()
        $this->assertJsonStringEqualsJsonFile($file1, $this->instance.'');

        $file2 = __DIR__.'/files/'.$c.'.test.json';
        $this->instance->saveJsonToFile($file2);

        $this->assertJsonFileEqualsJsonFile($file1, $file2);
        unlink($file2);

        $raw = $this->instance->getRaw();
        $this->assertContains('1.1', $raw);
    }

    /**
     *
     */
    public function testFullInstance() {
        $plant = new Plant;

        for ($i=1; $i<=2; $i++) {
            // Inverter $i
            $inverter = new Inverter;

            for ($j=1; $j<=2; $j++) {
                // Inverter $i, String $j
                $inverter->addPowerAcWatts('2000-01-01 00:00:00', 900)
                         ->addPowerAcWatts('2000-01-01 00:05:00', 900);

                $string = new Strings;
                $string->addPowerAcWatts('2000-01-01 00:00:00', 1000)
                       ->addPowerAcWatts('2000-01-01 00:05:00', 1000);

                $inverter->addString($string);
            }

            $plant->addInverter($inverter);
        }

        $this->instance->setPlant($plant);

        // check that multiple interpolate() Calls don't mess up the result,
        // interpolate() is called from asJson() by default
        $this->instance->interpolate()->interpolate();

        $this->assertJsonStringEqualsJsonFile(__DIR__.'/files/FullInstance.json', $this->instance->asJson());

        $this->instance->setPrettyJson(FALSE);
        $this->assertJsonStringEqualsJsonFile(__DIR__.'/files/FullInstance.json', $this->instance->asJson());
    }

    /**
     *
     */
    public function testCreatorProperty() {
        $value = md5(rand());
        $r = $this->instance->setCreator($value);
        $this->assertInstanceOf('PVLog\Classes\Json\Instance', $r);
        $this->assertEquals($value, $this->instance->getCreator());
    }

    /**
     *
     */
    public function testDeleteDayBeforeImportProperty() {
        $value = 1;
        $r = $this->instance->setDeleteDayBeforeImport($value);
        $this->assertInstanceOf('PVLog\Classes\Json\Instance', $r);
        $this->assertEquals($value, $this->instance->getDeleteDayBeforeImport());

        $value = 0;
        $this->instance->setDeleteDayBeforeImport($value);
        $this->assertEquals($value, $this->instance->getDeleteDayBeforeImport());
    }

    /**
     *
     */
    public function testTypeSetsProperty() {
        $r = $this->instance->setTypeMinutes();
        $this->assertInstanceOf('PVLog\Classes\Json\Instance', $r);
        $this->assertEquals('minutes', $this->instance->getType());

        $r = $this->instance->setTypeDay();
        $this->assertInstanceOf('PVLog\Classes\Json\Instance', $r);
        $this->assertEquals('day', $this->instance->getType());

        $r = $this->instance->setTypeMonth();
        $this->assertInstanceOf('PVLog\Classes\Json\Instance', $r);
        $this->assertEquals('month', $this->instance->getType());
    }

    /**
     *
     */
    public function testPlantSetterGetterProperty() {
        $r = $this->instance->setPlant(new Plant);
        $this->assertInstanceOf('PVLog\Classes\Json\Plant', $r->getPlant());
    }

    /**
     *
     */
    public function testPlantMagicProperty() {
        $this->assertNull($this->instance->plant);

        $this->instance->plant = new Plant;
        $this->assertInstanceOf('PVLog\Classes\Json\Plant', $this->instance->plant);
    }

    /**
     *
     */
    public function testSetGetIrradiation() {
        $this->assertNull($this->instance->getIrradiation());
        $this->assertNull($this->instance->irradiation);

        $irradiation = new Irradiation;
        $this->assertInstanceOf('PVLog\Classes\Json\Irradiation', $irradiation);

        $this->instance->setIrradiation($irradiation);
        $this->assertInstanceOf('PVLog\Classes\Json\Irradiation', $this->instance->getIrradiation());

        $this->instance->irradiation = $irradiation;
        $this->assertInstanceOf('PVLog\Classes\Json\Irradiation', $this->instance->irradiation);
    }

    /**
     *
     */
    public function testPlantWithoutInverters() {
        $plant = new Plant;
        $plant->addTotalWattHours('2000-01-01 00:00:00', 1);
        $this->instance->setPlant($plant);
        $this->instance->asArray();
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidProperty() {
        $this->assertNull($this->instance->notExistingProperty);
        // throws InvalidArgumentException
        $this->instance->notExistingProperty = 0;
    }

    /**
     *
     */
    public function testSetGetTemperature() {
        $this->assertNull($this->instance->getTemperature());

        $this->instance->setTemperature(new Temperature);
        $this->assertInstanceOf('PVLog\Classes\Json\Temperature', $this->instance->getTemperature());

        $this->instance->temperature = new Temperature;
        $this->assertInstanceOf('PVLog\Classes\Json\Temperature', $this->instance->temperature);
    }

}
