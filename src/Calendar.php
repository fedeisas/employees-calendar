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
     * @var SlotCollection
     */
    protected $slotCollection;

    /**
     * @var int
     */
    protected $daysInMonth;

    /**
     * @var array
     */
    protected $employeesFreeDaysPerWeek = [];

    /**
     * @param int $month
     * @param int $year
     */
    public function __construct($month = null, $year = null, SlotCollection $slotCollection = null)
    {
        $month = !empty($month) ? (int) $month : (int) date('m');
        $year = !empty($year) ? (int) $year : (int) date('Y');

        if (empty($slotCollection)) {
            $slotCollection = new SlotCollection(null, 1);
        }

        $this->month = $month;
        $this->year = $year;
        $this->slotCollection = $slotCollection;
        $this->daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
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

    public function getPrettyDateName()
    {
        $date = \DateTime::createFromFormat('!m', $this->month);

        return join(' ', [$date->format('F'), $this->year]);
    }

    public function getDaysInMonth()
    {
        return $this->daysInMonth;
    }

    public function getFreeDaysForEmployee(Employee $employee)
    {
        return $this->employeesFreeDaysPerWeek[$employee->getId()];
    }

    public function getMonth()
    {
        return $this->month;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function getAllPossibleShifts()
    {
        $dates = [];
        for ($i = 1; $i <= $this->daysInMonth; $i++) {
            $dates[] = $this->year . "-" . $this->month . "-" . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $shifts = [];
        foreach ($dates as $date) {
            foreach (Shift::types() as $type) {
                array_push(
                    $shifts,
                    new Shift((int) date('w', strtotime($date)), $type)
                );
            }
        }

        return $shifts;
    }
}
