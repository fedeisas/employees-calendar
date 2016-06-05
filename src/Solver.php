<?php
namespace EmployeesCalendar;

class Solver
{
    const MAXIMUM_WORKING_DAYS = 7;

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
     * @param EmployeesCollection $employeesCollection
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
        $maxSpecialDays = ceil($numberOfSpecialDays / $employeesCount);

        foreach ($this->calendar->getAllWorkableShifts() as $workableShift) {
            $date = $workableShift->getDate();
            $shift = $workableShift->getShift();
            $shiftSize = $workableShift->getSize();
            $slotsOccupied = 0;
            $numberOfTries = 0;
            $isInSpecialDay = $this->calendar->isInSpecialDay($shift);

            while ($slotsOccupied < $shiftSize && $numberOfTries < ($employeesCount * 2)) {
                $employee = $this->employeesCollection->next();

                ++$numberOfTries;

                if ($this->manager->exists($date, $shift, $employee)) {
                    continue;
                }

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

                if ($this->hasReachedWeeklyWorkingDays($date, $employee)) {
                    continue;
                }

                if ($isInSpecialDay && $this->manager->getNumberOfSpecialDaysThisWeek($date, $employee) > $maxSpecialDays) {
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
     * @param string $date
     * @param Employee $employee
     * @return bool
     */
    protected function hasReachedWeeklyWorkingDays($date, Employee $employee)
    {
        return $this->manager->getNumberOfWorkingThisWeek($date, $employee) + $this->employeesCollection->getFreeDaysForEmployee($employee) >= static::MAXIMUM_WORKING_DAYS;
    }

    /**
     * @return array
     */
    public function getFormattedOutput()
    {
        $output = [];
        foreach ($this->manager->getCollection() as $date => $row) {
            foreach ($row as $shift => $employees) {
                foreach ($employees as $employee) {
                    $output[] = join(' - ', [$date, $shift, $employee->getName()]);
                }
            }
        }

        return $output;
    }
}
