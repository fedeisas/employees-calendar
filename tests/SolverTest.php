<?php
namespace EmployeesCalendar\Test;

use PHPUnit_Framework_TestCase;
use EmployeesCalendar\Calendar;
use EmployeesCalendar\Employee;
use EmployeesCalendar\EmployeesCollection;
use EmployeesCalendar\Shift;
use EmployeesCalendar\Slot;
use EmployeesCalendar\SlotsCollection;
use EmployeesCalendar\Solver;

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

    public function testCreateWithEmployeeConstraint()
    {
        $employees = new EmployeesCollection();
        $employees->add(new Employee('John Doe', [Shift::createFromString('Tuesday daytime')]));
        $employees->add(new Employee('Susan Bar'));
        $solver = new Solver(new Calendar(6, 2016, null, []), $employees);
        $solver->solve();

        $solution = $solver->getFormattedOutput();
        $this->assertContains('John Doe', $solution[0]);
        $this->assertContains('Susan Bar', $solution[1]);

        foreach ($solution as $row) {
            $this->assertNotContains('Tuesday daytime - John Doe', $row);
        }
    }

    public function testCreateWithClosedDay()
    {
        $employees = new EmployeesCollection();
        $employees->add(new Employee('John Doe'));
        $employees->add(new Employee('Susan Bar'));
        $calendar = new Calendar(
            6,
            2016,
            new SlotsCollection(
                [
                    new Slot(Shift::createFromString('Sunday daytime'), 0),
                ],
                1
            ),
            []
        );
        $solver = new Solver($calendar, $employees);
        $solver->solve();

        $solution = $solver->getFormattedOutput();
        $this->assertContains('John Doe', $solution[0]);
        $this->assertContains('Susan Bar', $solution[1]);

        foreach ($solution as $row) {
            $this->assertNotContains('Sunday daytime', $row);
        }
    }

    public function testCreateWithSeveralEmployeesDay()
    {
        $employees = new EmployeesCollection();
        $employees->add(new Employee('John Doe'));
        $employees->add(new Employee('Susan Bar'));
        $calendar = new Calendar(
            6,
            2016,
            new SlotsCollection(
                [
                    new Slot(Shift::createFromString('Saturday nighttime'), 2),
                ],
                1
            ),
            []
        );
        $solver = new Solver($calendar, $employees);
        $solver->solve();

        $solution = $solver->getFormattedOutput();

        $count = 0;
        foreach ($solution as $row) {
            if (strpos($row, 'Saturday nighttime')) {
                $count++;
            }
        }

        $this->assertEquals(8, $count);
    }

    public function testCreateWithSeveralEmployeesDayWontRepeatEmployee()
    {
        $employees = new EmployeesCollection();
        $employees->add(new Employee('John Doe'));
        $employees->add(new Employee('Susan Bar'));
        $calendar = new Calendar(
            6,
            2016,
            new SlotsCollection(
                [
                    new Slot(Shift::createFromString('Saturday nighttime'), 3),
                ],
                1
            ),
            []
        );
        $solver = new Solver($calendar, $employees);
        $solver->solve();

        $solution = $solver->getFormattedOutput();

        $count = 0;
        $buffer = [];
        foreach ($solution as $row) {
            if (strpos($row, 'Saturday nighttime')) {
                $buffer[] = $row;
                $count++;
            }
        }

        $this->assertEquals(8, $count);
        $this->assertEquals(8, count(array_unique($buffer)));
    }
}
