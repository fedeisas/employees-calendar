<?php
namespace EmployeesCalendar;

class SlotsCollection
{
    /**
     * @var Slot[]
     */
    protected $collection = [];

    /**
     * @var array
     */
    protected $slotSizeCache = [];

    /**
     * @param Slot[]|null $slots
     * @param int $defaultSlotSize
     */
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

    /**
     * @param Slot[]|null $slots
     * @param int $weekday
     * @param string $type
     * @return Slot|null
     */
    protected function findSlot(array $slots = null, $weekday, $type)
    {
        foreach ($slots as $slot) {
            if ($slot->getShift()->isEqualTo(new Shift($weekday, $type))) {
                return $slot;
            }
        }
    }

    /**
     * @return Slot[]
     */
    public function getSlots()
    {
        return $this->collection;
    }

    /**
     * @param Shift $shift
     * @return int
     */
    public function getSizeForShift(Shift $shift)
    {
        if (isset($this->slotSizeCache[$shift->getName()])) {
            return $this->slotSizeCache[$shift->getName()];
        }

        foreach ($this->collection as $slot) {
            if ($slot->getShift()->isEqualTo($shift)) {
                $this->slotSizeCache[$shift->getName()] = $slot->getSize();

                return $this->slotSizeCache[$shift->getName()];
            }
        }
    }
}
