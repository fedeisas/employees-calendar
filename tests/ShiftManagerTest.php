<?php
namespace EmployeesCalendar\Test;

use PHPUnit_Framework_TestCase;
use EmployeesCalendar\Employee;
use EmployeesCalendar\Shift;
use EmployeesCalendar\ShiftManager;

class ShiftManagerTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        new ShiftManager();
    }

    public function testAddShift()
    {
        $manager = new ShiftManager();
        $employee = new Employee('John');

        $this->assertEquals(0, $manager->getNumberOfWorkingThisWeek('2016-06-06', $employee));

        $manager->add('2016-06-06', Shift::createFromString('Monday daytime'), $employee);
        $manager->add('2016-06-06', Shift::createFromString('Monday nighttime'), $employee);
        $this->assertEquals(0, $manager->getNumberOfSpecialDaysThisWeek('2016-06-06', $employee));
        $this->assertEquals(2, $manager->getNumberOfWorkingThisWeek('2016-06-06', $employee));

        $manager->addSpecialDay('2016-06-07', Shift::createFromString('Saturday nighttime'), $employee);
        $this->assertEquals(1, $manager->getNumberOfSpecialDaysThisWeek('2016-06-07', $employee));
        $this->assertEquals(3, $manager->getNumberOfWorkingThisWeek('2016-06-06', $employee));
    }

    public function testExists()
    {
        $manager = new ShiftManager();
        $employee = new Employee('John');
        $date = '2016-06-06';
        $shift = Shift::createFromString('Monday daytime');

        $this->assertFalse($manager->exists($date, $shift, $employee));
        $manager->add($date, $shift, $employee);
        $this->assertTrue($manager->exists($date, $shift, $employee));
    }
}
