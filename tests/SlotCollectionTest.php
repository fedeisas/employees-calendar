<?php
namespace EmployeesCalendar\Test;

use PHPUnit_Framework_TestCase;
use EmployeesCalendar\Shift;
use EmployeesCalendar\Slot;
use EmployeesCalendar\SlotCollection;

class SlotCollectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        new SlotCollection();
    }

    public function testCreateWithParameters()
    {
        new SlotCollection(null, 2);
    }

    public function testGetSlots()
    {
        $collection = new SlotCollection(null, 2);
        $this->assertCount(14, $collection->getSlots());

        foreach ($collection->getSlots() as $slot) {
            $this->assertEquals(2, $slot->getSize());
        }
    }

    public function testCreateWithSomeExceptions()
    {
        $collection = new SlotCollection([
            new Slot(Shift::createFromString('Monday nighttime'), 1)
        ], 2);
        $this->assertCount(14, $collection->getSlots());

        $this->assertEquals(2, $collection->getSizeForShift(Shift::createFromString('Monday daytime')));
        $this->assertEquals(1, $collection->getSizeForShift(Shift::createFromString('Monday nighttime')));
    }
}
