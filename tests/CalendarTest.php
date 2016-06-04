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

    public function testAddEmployee()
    {
        $calendar = new Calendar(6, 2016);
        $calendar->addEmployee(new Employee('John Foo'));
        $this->assertEquals(1, $calendar->getEmployeesCount());
        $calendar->addEmployee(new Employee('Susan Bar'));
        $this->assertEquals(2, $calendar->getEmployeesCount());
    }

    public function testGetFreeDaysForEmployee()
    {
        $calendar = new Calendar(6, 2016);
        $employee = new Employee('John Foo');
        $calendar->addEmployee($employee, 2);
        $this->assertEquals(2, $calendar->getFreeDaysForEmployee($employee));

        $employee = new Employee('Susan Bar');
        $calendar->addEmployee($employee);
        $this->assertEquals(1, $calendar->getFreeDaysForEmployee($employee));
    }
}
