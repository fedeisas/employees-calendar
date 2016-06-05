<?php
namespace EmployeesCalendar;

class EmployeesCollection
{
    /**
     * @var array
     */
    protected $employees = [];

    /**
     * @var array
     */
    protected $employeesFreeDaysPerWeek = [];

    /**
     * @param Employee $employee
     * @param int $freeDaysPerWeek Free days per week. Default: 1
     */
    public function add(Employee $employee, $freeDaysPerWeek = 1)
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
    public function count()
    {
        return count($this->employees);
    }

    /**
     * @param Employee $employee
     * @return int
     */
    public function getFreeDaysForEmployee(Employee $employee)
    {
        return $this->employeesFreeDaysPerWeek[$employee->getId()];
    }

    /**
     * @return Employee
     */
    public function next()
    {
        $employee = current($this->employees);
        if (empty($employee)) {
            reset($this->employees);
            $employee = current($this->employees);
        }

        next($this->employees);

        return $employee;
    }
}
