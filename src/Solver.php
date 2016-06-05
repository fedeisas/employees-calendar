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
     * @var Manager
     */
    protected $manager;

    /**
     * @var array
     */
    protected $employeesFreeDaysPerWeek = [];

    /**
     * @var array
     */
    protected $solution;

    /**
     * @param Calendar $calendar
     */
    public function __construct(Calendar $calendar)
    {
        $this->calendar = $calendar;
        $this->manager = new ShiftManager();
    }

    /**
     * @param Employee $employee
     * @param int $freeDaysPerWeek Free days per week. Default: 1
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
        $numberOfSpecialDays = $this->calendar->getNumberOfSpecialDaysThisMonth();
        $specialDaysPerEmployee = ceil($numberOfSpecialDays / $this->getEmployeesCount());

        // [$date, Shift $shift, $size]
        foreach ($this->calendar->getAllWorkableShifts() as $shiftComponent) {
            $date = $workableShift->getDate();
            $shift = $workableShift->getShift();
            $shiftSize = $workableShift->getSize();
            $slotsOccupied = 0;
            $numberOfTries = 0;
            $isSpecialDay = $this->calendar->isInSpecialDay($shift);

            while ($slotsOccupied < $shiftSize) {
                $employee = $this->getNextEmployee();

                ++$numberOfTries;

                if ($numberOfTries > $this->getEmployeesCount()) {
                    ++$slotsOccupied;
                    if ($isInSpecialDay) {
                        $this->manager->addSpecialDay($date, $shift, $employee);
                    } elseif(!$isInSpecialDay) {
                        $this->manager->add($date, $shift, $employee);
                    }
                }

                if (!$employee->canWork($shift)) {
                    continue;
                }

                if ($this->manager->getNumberOfWorkingThisWeek($date, $employee) + $this->getFreeDaysForEmployee($employee) >= 7) {
                    continue;
                }

                if ($isInSpecialDay && $this->manager->getNumberOfSpecialDaysThisWeek($date, $employee) > $specialDaysPerEmployee) {
                    continue;
                }

                ++$slotsOccupied;
                if ($isInSpecialDay) {
                    $this->manager->addSpecialDay($date, $shift, $employee);
                } elseif(!$isInSpecialDay) {
                    $this->manager->add($date, $shift, $employee);
                }
            }
        }
    }

    /**
     * @return Employee
     */
    public function getNextEmployee()
    {
        $employee = current($this->employees);
        if (empty($employee)) {
            reset($this->employees);
            $employee = current($this->employees);
        }

        next($this->employees);
        return $employee;
    }

    /**
     * @return Manager
     */
    public function getManager()
    {
        return $this->manager;
    }
}
