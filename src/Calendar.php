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
     * @var SlotsCollection
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
     * @var array
     */
    protected $dates;

    /**
     * @var int
     */
    protected $numberOfSpecialDays = 0;

    /**
     * @param int $month
     * @param int $year
     */
    public function __construct($month = null, $year = null, SlotsCollection $slotCollection = null, array $specialDays = [])
    {
        $month = !empty($month) ? (int) $month : (int) date('m');
        $year = !empty($year) ? (int) $year : (int) date('Y');
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        if (empty($slotCollection)) {
            $slotCollection = new SlotsCollection(null, 1);
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
        $this->daysInMonth = $daysInMonth;

        for ($i = 1; $i <= $this->daysInMonth; $i++) {
            $date = $this->year . "-" . $this->month . "-" . str_pad($i, 2, '0', STR_PAD_LEFT);
            $this->dates[] = $date;
            if (in_array((int) date('w', strtotime($date)), $specialDays)) {
                ++$this->numberOfSpecialDays;
            }
        }
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

    public function getAllWorkableShifts()
    {
        $shifts = [];
        foreach ($this->dates as $date) {
            foreach (Shift::types() as $type) {
                $shift = new Shift((int) date('w', strtotime($date)), $type);

                array_push(
                    $shifts,
                    new WorkableShift(
                        $date,
                        $shift,
                        $this->getShiftSize($shift)
                    )
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
        return $this->numberOfSpecialDays;
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
