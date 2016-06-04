<?php
namespace EmployeesCalendar\Test;

use PHPUnit_Framework_TestCase;
use EmployeesCalendar\Shift;
use EmployeesCalendar\Slot;

class SlotTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        new Slot(Shift::createFromString('Sunday nighttime'), 2);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Slot size should be numeric
     */
    public function testCreateBadArguments()
    {
        new Slot(Shift::createFromString('Sunday nighttime'), 'two');
    }
}
