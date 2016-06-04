<?php
namespace EmployeesCalendar;

class Calendar
{
    /**
     * @var int
     */
    protected $month;

    /**
     * @var int
     */
    protected $year;

    /**
     * @var Employee[]
     */
    protected $employees = [];

    /**
     * @var int
     */
    protected $daysInMonth;

    /**
     * @param int $month
     * @param int $year
     */
    public function __construct($month = null, $year = null)
    {
        $month = !empty($month) ? (int) $month : (int) date('m');
        $year = !empty($year) ? (int) $year : (int) date('Y');

        $this->month = $month;
        $this->year = $year;

        $this->daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    /**
     * @param Employee $employee
     */
    public function addEmployee(Employee $employee, $freeDaysPerWeek = 1)
    {
        array_push($this->employees, $employee);
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

    public function getPrettyDateName()
    {
        $date = \DateTime::createFromFormat('!m', $this->month);

        return join(' ', [$date->format('F'), $this->year]);
    }

    public function getDaysInMonth()
    {
        return $this->daysInMonth;
    }
}
