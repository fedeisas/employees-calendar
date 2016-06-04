<?php
namespace EmployeesCalendar;

class SlotCollection
{
    /**
     * @var Slot[]
     */
    protected $collection = [];

    public function __construct(array $slots = null, $defaultSlotSize = 2)
    {
        foreach (range(0, 6) as $weekday) {
            foreach (Shift::types() as $shiftType) {
                if ($slots) {
                    $foundSlot = $this->findSlot($slots, $weekday, $shiftType);
                    if (!empty($foundSlot)) {
                        array_push($this->collection, $foundSlot);
                    }
                }

                if (empty($foundSlot)) {
                    array_push($this->collection, new Slot(new Shift($weekday, $shiftType), $defaultSlotSize));
                }
            }
        }
    }

    protected function findSlot(array $slots = null, $weekday, $type)
    {
        foreach ($slots as $slot) {
            if ($slot->getShift()->isEqualTo(new Shift($weekday, $type))) {
                return $slot;
            }
        }
    }

    public function getSlots()
    {
        return $this->collection;
    }

    public function getSizeForShift(Shift $shift)
    {
        foreach ($this->collection as $slot) {
            if ($slot->getShift()->isEqualTo($shift)) {
                return $slot->getSize();
            }
        }
    }
}
