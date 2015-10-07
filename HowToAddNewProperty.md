# Add property

To add a new property to `Instance` or `Inverter`, you have to follow these steps:

* Decide **parent object**:
  * For **plain list** of timestamp => value pairs: `Set`
  * For **power sensors** (powerAcWatts data): `PowerSensor`
  * For **energy meters** (powerAcWatts and totalWattHours data): `EnergyMeter`
* Add **property constant** to `Property.php`
* Add **class mapping** for camelCase named classes
* Add **Setter** and **Getter** to e.g. `Instance.php` or `Inverter.php`
* Add **property** to `Instance::$validSections`
* **Add** tests and **run** `./testrunner.sh`

# Example

***Irradiation*** as plain `timestamp => value` pairs in ***Instance***

### Add a property constant to `Properties.php`

    /**
     * Property name for irradiation data section (Instance)
     */
    const IRRADIATION = 'irradiation';

### If the class name is **UpperCamelCased**, add an entry to the class map in `Json.php`
(here not required, only for example)

    /**
     * @var array $classMap Mapping of section to class name
     *                      for not standard section names
     */
    protected static $classMap = array(
        ...
        'irradiation'  => 'Irradiation',
    );

### Create `Irradiation.php`

    $ cp src/Classes/Json/Channel.template.php src/Classes/Json/Irradiation.php

### Edit `Irradiation.php` and replace all ... dots

    /**
     * Irradiation channel
     *
     * @author   Knut Kohl <pv@knutkohl.de>
     * @license  http://opensource.org/licenses/MIT MIT License (MIT)
     * @version  PVLog JSON 1.1
     * @since    2015-04-03
     */
    class Irradiation extends Set {}

### Extend `Instance.php`

    /**
     * Setter for Irradiation section
     *
     * @param  Irradiation $data
     * @return self For fluid interface
     */
    public function setIrradiation( Irradiation $data ) {
        return $this->set(Properties::IRRADIATION, $data);
    }

    /**
     * Setter for Irradiation section
     *
     * @return Irradiation|NULL
     */
    public function getIrradiation() {
        return $this->get(Properties::IRRADIATION);
    }

### Extend `Instance::$validSections`

    protected $validSections = array(
        ...
        Properties::IRRADIATION,
    );

### Extend `tests/InstanceTest.php`

    use PVLog\Classes\Json\Irradiation;

    ...

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
