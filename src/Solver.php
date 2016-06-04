<?php
namespace EmployeesCalendar;

class Solver
{
    /**
     * @var Employee[]
     */
    protected $employees = [];

    /**
     * @var Calendar
     */
    protected $calendar;

    /**
     * @var array
     */
    protected $employeesFreeDaysPerWeek = [];

    /**
     * @param Calendar $calendar
     */
    public function __construct(Calendar $calendar)
    {
        $this->calendar = $calendar;
    }

    /**
     * @param Employee $employee
     */
    public function addEmployee(Employee $employee, $freeDaysPerWeek = 1)
    {
        array_push($this->employees, $employee);
        $this->employeesFreeDaysPerWeek[$employee->getId()] = $freeDaysPerWeek;
    }

    /**
     * @return Employee[]
     */
    public function getEmployees()
    {
        return $this->employees;
    }

    /**
     * @return int
     */
    public function getEmployeesCount()
    {
        return count($this->employees);
    }

    public function getFreeDaysForEmployee(Employee $employee)
    {
        return $this->employeesFreeDaysPerWeek[$employee->getId()];
    }

    /**
     * @return array
     */
    public function solve()
    {
        foreach ($this->calendar->getAllPossibleShifts() as $shift) {
            # code...
        }
    }
}
