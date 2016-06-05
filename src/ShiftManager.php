<?php
namespace EmployeesCalendar;

class ShiftManager
{
    /**
     * @var array
     */
    protected $collection;

    /**
     * @var array
     */

    protected $specialDays = [];
    /**
     * @var array
     */
    protected $workingDays = [];

    public function add($date, Shift $shift, Employee $employee)
    {
        $this->collection[$date][(string) $shift][] = $employee;
        $week = date('W', strtotime($date));

        $this->incrementCounter($this->workingDays, $employee, $week);
    }

    public function addSpecialDay($date, Shift $shift, Employee $employee)
    {
        $week = date('W', strtotime($date));
        $this->incrementCounter($this->specialDays, $employee, $week);
        $this->add($date, $shift, $employee);
    }

    public function getNumberOfSpecialDaysThisWeek($date, Employee $employee)
    {
        if (!empty($this->specialDays[$employee->getId()][date('W', strtotime($date))])) {
            return $this->specialDays[$employee->getId()][date('W', strtotime($date))];
        }

        return 0;
    }

    public function getNumberOfWorkingThisWeek($date, Employee $employee)
    {
        if (!empty($this->workingDays[$employee->getId()][date('W', strtotime($date))])) {
            return $this->workingDays[$employee->getId()][date('W', strtotime($date))];
        }

        return 0;
    }

    protected function incrementCounter(array &$array, Employee $employee, $week)
    {
        if (empty($array[$employee->getId()])) {
            $array[$employee->getId()] = [];
        }

        if (empty($array[$employee->getId()][$week])) {
            $array[$employee->getId()][$week] = 0;
        }

        ++$array[$employee->getId()][$week];
    }

    public function getCollection()
    {
        return $this->collection;
    }
}
