<?php
namespace EmployeesCalendar\Test;

use PHPUnit_Framework_TestCase;
use EmployeesCalendar\Employee;
use EmployeesCalendar\Calendar;

class CalendarTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $calendar = new Calendar();
    }

    public function testCreateAndGetPrettyDateName()
    {
        $calendar = new Calendar();
        $this->assertTrue(is_string($calendar->getPrettyDateName()));
    }

    public function testCreateWithParametersAndGetPrettyDateName()
    {
        $calendar = new Calendar(6, 2016);
        $this->assertEquals('June 2016', $calendar->getPrettyDateName());
    }

    public function testCreateWithParametersAndGetDaysInMonth()
    {
        $calendar = new Calendar(6, 2016);
        $this->assertEquals(30, $calendar->getDaysInMonth());
    }

    public function testGetAllPossibleShifts()
    {
        $calendar = new Calendar(6, 2016);
        $shifts = $calendar->getAllPossibleShifts();

        $this->assertCount(60, $shifts);
    }
}
