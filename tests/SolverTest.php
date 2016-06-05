<?php
namespace EmployeesCalendar\Test;

use PHPUnit_Framework_TestCase;
use EmployeesCalendar\Employee;
use EmployeesCalendar\Solver;
use EmployeesCalendar\Calendar;
use EmployeesCalendar\EmployeesCollection;

class SolverTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        new Solver(new Calendar(), new EmployeesCollection());
    }

    public function testCreateWithoutConstraints()
    {
        $employees = new EmployeesCollection();
        $employees->add(new Employee('John Doe'));
        $employees->add(new Employee('Susan Bar'));
        $solver = new Solver(new Calendar(6, 2016, null, []), $employees);
        $solver->solve();

        $solution = $solver->getFormattedOutput();
        $this->assertContains('John Doe', $solution[0]);
        $this->assertContains('Susan Bar', $solution[1]);
    }
}
