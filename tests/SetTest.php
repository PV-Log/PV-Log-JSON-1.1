<?php

namespace PVLog\Json\Tests;

use PVLog\Classes\Json\Set;

/**
 *
 */
class SetTest extends \PHPUnit_Framework_TestCase {

    protected $set;

    /**
     *
     */
    protected function setUp() {
        $this->set = new Set();
    }

    /**
     *
     */
    public function testCreateFromData() {
        $set = new Set(1);
        $this->assertEquals(1, count($set));

        $set = new Set(array(
            '2000-01-01 00:00:00' => 1,
            '2000-01-01 00:05:00' => 2
        ));
        $this->assertEquals(2, count($set));
    }

    /**
     *
     */
    public function testSetData() {
        $this->set->set(array(
            '2000-01-01 00:00:00' => 1,
            '2000-01-01 00:05:00' => 2
        ));
        $this->assertEquals(2, count($this->set));

        $this->set->set('2000-01-01 00:00:00', 1);
        $this->assertEquals(1, count($this->set));
        $this->assertEquals(1, $this->set['2000-01-01 00:00:00']);

        $this->set->set(1);
        $this->assertEquals(1, count($this->set));
    }

    /**
     *
     */
    public function testCountable() {
        $this->set['2000-01-01 00:00:00'] = 0;
        $this->assertEquals(1, count($this->set));
        $this->set->add('2000-01-01 00:05:00', 1);
        $this->assertEquals(2, count($this->set));
    }

    /**
     *
     */
    public function testArrayAccess() {
        // offsetSet
        $this->set['2000-01-01 00:00:00'] = 1;
        $this->set['2000-01-01 00:05:00'] = 2;
        // offsetGet
        $this->assertEquals(1, $this->set['2000-01-01 00:00:00']);
        // offsetUnset
        unset($this->set['2000-01-01 00:05:00']);
        // offsetExists
        $this->assertTrue(isset($this->set['2000-01-01 00:00:00']));
        $this->assertFalse(isset($this->set['2000-01-01 00:05:00']));
    }

    /**
     *
     */
    public function testIterator() {
        // Internal as timestamps, so foreach returns also timestamps as keys
        $before = array(
            strtotime('2000-01-01 00:00:00') => 1,
            strtotime('2000-01-01 00:05:00') => 2
        );
        $this->set->set($before);
        $after = array();
        foreach ($this->set as $key=>$value) {
            $after[$key] = $value;
        }
        $this->assertEquals($before, $after);
    }

    /**
     *
     */
    public function testInterpolateAndAsArray() {
        // Internal as timestamps, so foreach returns also timestamps as keys
        $before = array(
            '2000-01-01 00:00:00' => 1,
            '2000-01-01 00:06:00' => 2,
            '2000-01-01 00:15:00' => 4
        );
        $this->set->set($before);

        $after = array(
            '2000-01-01 00:00:00' => 1,
            '2000-01-01 00:05:00' => 2,
            '2000-01-01 00:10:00' => 3,
            '2000-01-01 00:15:00' => 4
        );
        $this->assertEquals($this->set->interpolate()->asArray(Set::DATETIME), $after);
    }

    /**
     *
     */
    public function testGetLastValue() {
        $data = array(
            strtotime('2000-01-01 00:00:00') => 1,
            strtotime('2000-01-01 00:05:00') => 2
        );
        $this->set->set($data);
        $this->assertEquals(2, $this->set->last());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEmptyKey() {
        $this->set[] = 1;
    }


}
