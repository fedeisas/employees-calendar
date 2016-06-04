<?php
namespace EmployeesCalendar;

class Slot
{
    /**
     * @var Shift
     */
    protected $shift;

    /**
     * @var int
     */
    protected $size;

    public function __construct(Shift $shift, $size = 1)
    {
        if (empty($size) || !is_numeric($size)) {
            throw new \InvalidArgumentException('Slot size should be numeric');
        }

        $this->shift = $shift;
        $this->size = $size;
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
