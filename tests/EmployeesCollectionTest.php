<?php
namespace EmployeesCalendar\Test;

use PHPUnit_Framework_TestCase;
use EmployeesCalendar\Employee;
use EmployeesCalendar\EmployeesCollection;

class EmployeesCollectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        new EmployeesCollection();
    }

    public function testAddEmployee()
    {
        $collection = new EmployeesCollection($calendar);
        $collection->add(new Employee('John Foo'));
        $this->assertEquals(1, $collection->count());
        $collection->add(new Employee('Susan Bar'));
        $this->assertEquals(2, $collection->count());
    }

    public function testGetFreeDaysForEmployee()
    {
        $collection = new EmployeesCollection($calendar);
        $employee = new Employee('John Foo');
        $collection->add($employee, 2);
        $this->assertEquals(2, $collection->getFreeDaysForEmployee($employee));

        $employee = new Employee('Susan Bar');
        $collection->add($employee);
        $this->assertEquals(1, $collection->getFreeDaysForEmployee($employee));
    }

    public function testGetNextEmployee()
    {
        $collection = new EmployeesCollection($calendar);
        $employee = new Employee('John Foo');
        $collection->add($employee, 2);
        $employee = new Employee('Susan Bar');
        $collection->add($employee);

        $this->assertEquals('John Foo', $collection->next()->getName());
        $this->assertEquals('Susan Bar', $collection->next()->getName());
        $this->assertEquals('John Foo', $collection->next()->getName());
        $this->assertEquals('Susan Bar', $collection->next()->getName());
    }
}
