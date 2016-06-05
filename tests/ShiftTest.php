<?php
namespace EmployeesCalendar\Test;

use PHPUnit_Framework_TestCase;
use EmployeesCalendar\Shift;

class ShiftTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Can't create Shift from string: foo. Valid format is {weekday} {type}
     */
    public function testCreateFromBadString()
    {
        Shift::createFromString('foo');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Can't create Shift from string: foo bar zas. Valid format is {weekday} {type}
     */
    public function testCreateFromBadStringFormat()
    {
        Shift::createFromString('foo bar zas');
    }

    public function testCreateFromString()
    {
        $shift = Shift::createFromString('Sunday nighttime');
        $this->assertInstanceOf(Shift::class, $shift);
        $this->assertEquals('Sunday nighttime', (string) $shift);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Wrong shift type: foo
     */
    public function testBadType()
    {
        new Shift(1, 'foo');
    }

    public function testIsEqualTo()
    {
        $shift1 = Shift::createFromString('Sunday nighttime');
        $shift2 = Shift::createFromString('Sunday nighttime');
        $this->assertTrue($shift1->isEqualTo($shift2));
        $this->assertTrue($shift2->isEqualTo($shift1));
    }
}
