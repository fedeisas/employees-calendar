<?php
namespace EmployeesCalendar;

class WorkableShift
{
    /**
     * @param int $weekday
     * @param string $type
     */
    public function __construct($date, Shift $shift, $size)
    {
        if (!\DateTime::createFromFormat('Y-m-d', $date)) {
            throw new \InvalidArgumentException('Wrong date: ' . $date);
        }

        if (!is_int($size)) {
            throw new \InvalidArgumentException('Invalid workable shift size: ' . $size);
        }

        $this->date = $date;
        $this->shift = $shift;
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return Shift
     */
    public function getShift()
    {
        return $this->shift;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }
}
