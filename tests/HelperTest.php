<?php

namespace PVLog\Json\Tests;

use PVLog\Classes\Json\Helper;

/**
 *
 */
class HelperTest extends \PHPUnit_Framework_TestCase {

    /**
     *
     */
    public function testAsTimestampFormats() {
        // Force time zone to use
        date_default_timezone_set('Europe/London');
        $this->assertEquals(0, Helper::asTimestamp(0));
        $this->assertEquals(-date('Z'), Helper::asTimestamp('1970-01-01 00:00:00'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAsTimestampFormatsInvalid() {
        Helper::asTimestamp('invalid');
    }

    /**
     *
     */
    public function testSetDateFormats() {
        Helper::getDateFormat();
        Helper::setDateFormatMinutes();
        $this->assertEquals('Y-m-d H:i:s', Helper::getDateFormat());
        Helper::setDateFormatDay();
        $this->assertEquals('Y-m-d', Helper::getDateFormat());
        Helper::setDateFormatMonth();
        $this->assertEquals('Y-m-t', Helper::getDateFormat());
    }

    /**
     *
     */
    public function testTimestampOffsetProperty() {
        Helper::setTimestampOffset(1);
        $this->assertEquals(1, Helper::getTimestampOffset());
        $this->assertEquals('2000-01-01 01:00:00', Helper::localTimestamp('2000-01-01'));

    }

    /**
     *
     */
    public function testConvertTemperatures() {
        $c = 0;
        $f = $c * 1.8 + 32;
        $this->assertEquals($c, Helper::convertFahrenheitToCelsius($f));
#        $this->assertEquals($f, Helper::convertCelsiusToFahrenheit($c));
    }

    /**
     *
     */
    public function testConvertTemperatureArrays() {
        $c = array( 0, 1 );
        $f = array( $c[0] * 1.8 + 32, $c[1] * 1.8 + 32 );
        $this->assertEquals($c, Helper::convertFahrenheitToCelsius($f));
#        $this->assertEquals($f, Helper::convertCelsiusToFahrenheit($c));
    }
}
