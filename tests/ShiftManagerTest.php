<?php
namespace EmployeesCalendar\Test;

use PHPUnit_Framework_TestCase;
use EmployeesCalendar\Calendar;
use EmployeesCalendar\Employee;
use EmployeesCalendar\Shift;
use EmployeesCalendar\Slot;
use EmployeesCalendar\SlotsCollection;
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
}
