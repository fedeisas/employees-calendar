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

    /**
     * @var array
     */
    protected $specialDays = [];

    /**
     * @var array
     */
    protected $workingDays = [];

    /**
     * @param string $date
     * @param Shift $shift
     * @param Employee $employee
     */
    public function add($date, Shift $shift, Employee $employee)
    {
        if ($this->exists($date, $shift, $employee)) {
            throw new \RuntimeException('Can\'t add an employee twice for the same shift.');
        }

        $this->collection[$date][$shift->getName()][] = $employee;
        $week = $this->getYearWeek($date);

        $this->incrementCounter($this->workingDays, $employee, $week);
    }

    /**
     * @param string $date
     * @param Shift $shift
     * @param Employee $employee
     */
    public function addSpecialDay($date, Shift $shift, Employee $employee)
    {
        if ($this->exists($date, $shift, $employee)) {
            throw new \RuntimeException('Can\'t add an employee twice for the same shift.');
        }

        $week = $this->getYearWeek($date);
        $this->incrementCounter($this->specialDays, $employee, $week);
        $this->add($date, $shift, $employee);
    }

    /**
     * @param string $date
     * @param Employee $employee
     * @return int
     */
    public function getNumberOfSpecialDaysThisWeek($date, Employee $employee)
    {
        $week = $this->getYearWeek($date);
        if (!empty($this->specialDays[$employee->getId()][$week])) {
            return $this->specialDays[$employee->getId()][$week];
        }

        return 0;
    }

    /**
     * @param string $date
     * @param Employee $employee
     * @return int
     */
    public function getNumberOfWorkingThisWeek($date, Employee $employee)
    {
        $week = $this->getYearWeek($date);
        if (!empty($this->workingDays[$employee->getId()][$week])) {
            return $this->workingDays[$employee->getId()][$week];
        }

        return 0;
    }

    /**
     * @param array &$array
     * @param Employee $employee
     * @param string $week
     * @return void
     */
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

    /**
     * @return array
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @param string $date
     * @param Shift $shift
     * @param Employee $employee
     * @return bool
     */
    public function exists($date, Shift $shift, Employee $employee)
    {
        if (!empty($this->collection[$date][$shift->getName()])) {
            foreach ($this->collection[$date][$shift->getName()] as $presentEmployee) {
                if ($presentEmployee->getId() === $employee->getId()) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param string $date
     * @return string
     */
    protected function getYearWeek($date)
    {
        return date('W', strtotime($date));
    }
}
