<?php
namespace EmployeesCalendar\Test;

use PHPUnit_Framework_TestCase;
use EmployeesCalendar\Employee;
use EmployeesCalendar\Shift;

class EmployeeTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $name = 'Steve Jobs';
        $employee = new Employee($name);
        $this->assertEquals($name, $employee->getName());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Employee should have name.
     */
    public function testCreateWithoutName()
    {
        new Employee();
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Employee constraints should be an instance of Shift.
     */
    public function testCreateWithBadConstraints()
    {
        new Employee('Name', [new \StdClass()]);
    }

    public function testConstraints()
    {
        $employee = new Employee('Steve Jobs', [Shift::createFromString('Sunday daytime')]);

        $this->assertTrue($employee->canWork(Shift::createFromString('Monday daytime')));
        $this->assertFalse($employee->canWork(Shift::createFromString('Sunday daytime')));
    }

    public function testGetId()
    {
        $employee1 = new Employee('Steve Jobs');
        $employee2 = new Employee('Steve Jobs');

        $this->assertTrue(is_string($employee1->getId()));
        $this->assertTrue(is_string($employee2->getId()));
        $this->assertNotEquals($employee1->getId(), $employee2->getId());
    }
}
