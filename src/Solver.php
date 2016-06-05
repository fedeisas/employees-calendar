<?php
namespace EmployeesCalendar;

class Solver
{
    /**
     * @var Calendar
     */
    protected $calendar;

    /**
     * @var EmployeesCollection
     */
    protected $employeesCollection;

    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var array
     */
    protected $solution;

    /**
     * @param Calendar $calendar
     */
    public function __construct(Calendar $calendar, EmployeesCollection $employeesCollection)
    {
        $this->calendar = $calendar;
        $this->employeesCollection = $employeesCollection;
        $this->manager = new ShiftManager();
    }

    /**
     * @return array
     */
    public function solve()
    {
        $employeesCount = $this->employeesCollection->count();
        $numberOfSpecialDays = $this->calendar->getNumberOfSpecialDaysThisMonth();
        $specialDaysPerEmployee = ceil($numberOfSpecialDays / $employeesCount);

        foreach ($this->calendar->getAllWorkableShifts() as $workableShift) {
            $date = $workableShift->getDate();
            $shift = $workableShift->getShift();
            $shiftSize = $workableShift->getSize();
            $slotsOccupied = 0;
            $numberOfTries = 0;
            $isSpecialDay = $this->calendar->isInSpecialDay($shift);

            while ($slotsOccupied < $shiftSize) {
                $employee = $this->employeesCollection->next();

                ++$numberOfTries;

                if ($numberOfTries > $employeesCount) {
                    ++$slotsOccupied;
                    if ($isInSpecialDay) {
                        $this->manager->addSpecialDay($date, $shift, $employee);
                    } elseif (!$isInSpecialDay) {
                        $this->manager->add($date, $shift, $employee);
                    }
                }

                if (!$employee->canWork($shift)) {
                    continue;
                }

                if ($this->manager->getNumberOfWorkingThisWeek($date, $employee) + $this->employeesCollection->getFreeDaysForEmployee($employee) >= 7) {
                    continue;
                }

                if ($isInSpecialDay && $this->manager->employeesCollection->getNumberOfSpecialDaysThisWeek($date, $employee) > $specialDaysPerEmployee) {
                    continue;
                }

                ++$slotsOccupied;
                if ($isInSpecialDay) {
                    $this->manager->addSpecialDay($date, $shift, $employee);
                } elseif (!$isInSpecialDay) {
                    $this->manager->add($date, $shift, $employee);
                }
            }
        }
    }

    /**
     * @return Manager
     */
    public function getManager()
    {
        return $this->manager;
    }
}
