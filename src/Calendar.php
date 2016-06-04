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
    protected $specialDays;

    /**
     * @param int $month
     * @param int $year
     */
    public function __construct($month = null, $year = null, SlotCollection $slotCollection = null, array $specialDays = [])
    {
        $month = !empty($month) ? (int) $month : (int) date('m');
        $year = !empty($year) ? (int) $year : (int) date('Y');

        if (empty($slotCollection)) {
            $slotCollection = new SlotCollection(null, 1);
        }

        if (empty($specialDays)) {
            $specialDays = [
                (int) date('w', strtotime('friday')),
                (int) date('w', strtotime('saturday')),
            ];
        }

        $this->month = $month;
        $this->year = $year;
        $this->slotCollection = $slotCollection;
        $this->specialDays = $specialDays;
        $this->daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
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
                $shift = new Shift((int) date('w', strtotime($date)), $type);

                array_push(
                    $shifts,
                    [
                        $date,
                        $shift,
                        $this->getShiftSize($shift)
                    ]
                );
            }
        }

        return $shifts;
    }

    /**
     * @param Shift $shift
     * @return int
     */
    protected function getShiftSize(Shift $shift)
    {
        return $this->slotCollection->getSizeForShift($shift);
    }

    /**
     * @return int
     */
    public function getNumberOfSpecialDaysThisMonth()
    {
        $numberOfSpecialDays = 0;
        $dates = [];
        for ($i = 1; $i <= $this->daysInMonth; $i++) {
            if (in_array((int) date('w', strtotime($this->year . "-" . $this->month . "-" . str_pad($i, 2, '0', STR_PAD_LEFT))), $this->specialDays)) {
                ++$numberOfSpecialDays;
            }
        }

        return $numberOfSpecialDays;
    }

    /**
     * @param Shift $shift
     * @return bool
     */
    public function isInSpecialDay(Shift $shift)
    {
        return in_array($shift->getWeekday(), $this->specialDays);
    }
}
