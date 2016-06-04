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
        foreach ($this->calendar->getAllPossibleShifts() as $shiftComponent) {
            $date = $shiftComponent[0];
            $shift = $shiftComponent[1];
            $shiftSize = $shiftComponent[2];
            $slotsOccupied = 0;
            $numberOfTries = 0;

            while ($slotsOccupied < $shiftSize) {
                $employee = $this->getNextEmployee();

                var_dump([
                    'date' => $date,
                    'shift' => (string) $shift,
                    'employee' => $employee->getName(),
                    'canWork' => $employee->canWork($shift),
                    'numberOfWorkingThisWeek' => $this->manager->getNumberOfWorkingThisWeek($date, $employee),
                    'getFreeDaysForEmployee' => $this->getFreeDaysForEmployee($employee),
                    'isInSpecialDay' => $this->calendar->isInSpecialDay($shift),
                    'getNumberOfSpecialDaysThisWeek' => $this->manager->getNumberOfSpecialDaysThisWeek($date, $employee),
                    'specialDaysPerEmployee' => $specialDaysPerEmployee,
                ]);

                if ($employee->canWork($shift) &&
                    $this->manager->getNumberOfWorkingThisWeek($date, $employee) + $this->getFreeDaysForEmployee($employee) <= 7 &&
                    !$this->calendar->isInSpecialDay($shift) || (
                        $this->manager->getNumberOfSpecialDaysThisWeek($date, $employee) < $specialDaysPerEmployee
                    )
                ) {
                    $this->manager->add($date, $shift, $employee, $this->calendar->isInSpecialDay($shift));
                }

                ++$numberOfTries;

                if ($numberOfTries >= $this->getEmployeesCount()) {
                    $this->manager->add($date, $shift, $employee, $this->calendar->isInSpecialDay($shift));
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
}
