<?php
namespace EmployeesCalendar\Test;

use PHPUnit_Framework_TestCase;
use EmployeesCalendar\Employee;
use EmployeesCalendar\Calendar;
use EmployeesCalendar\Solver;

class SolverTest extends PHPUnit_Framework_TestCase
{
    public function testAddEmployee()
    {
        $calendar = new Calendar(6, 2016);
        $solver = new Solver($calendar);
        $solver->addEmployee(new Employee('John Foo'));
        $this->assertEquals(1, $solver->getEmployeesCount());
        $solver->addEmployee(new Employee('Susan Bar'));
        $this->assertEquals(2, $solver->getEmployeesCount());
    }

    public function testGetFreeDaysForEmployee()
    {
        $calendar = new Calendar(6, 2016);
        $solver = new Solver($calendar);
        $employee = new Employee('John Foo');
        $solver->addEmployee($employee, 2);
        $this->assertEquals(2, $solver->getFreeDaysForEmployee($employee));

        $employee = new Employee('Susan Bar');
        $solver->addEmployee($employee);
        $this->assertEquals(1, $solver->getFreeDaysForEmployee($employee));
    }

    public function testGetNextEmployee()
    {
        $calendar = new Calendar(6, 2016);
        $solver = new Solver($calendar);
        $employee = new Employee('John Foo');
        $solver->addEmployee($employee, 2);
        $employee = new Employee('Susan Bar');
        $solver->addEmployee($employee);

        $this->assertEquals('John Foo', $solver->getNextEmployee()->getName());
        $this->assertEquals('Susan Bar', $solver->getNextEmployee()->getName());
        $this->assertEquals('John Foo', $solver->getNextEmployee()->getName());
        $this->assertEquals('Susan Bar', $solver->getNextEmployee()->getName());
    }
}
