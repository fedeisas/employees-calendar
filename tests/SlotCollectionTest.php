<?php
namespace EmployeesCalendar\Test;

use PHPUnit_Framework_TestCase;
use EmployeesCalendar\Shift;
use EmployeesCalendar\Slot;
use EmployeesCalendar\SlotsCollection;

class SlotsCollectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        new SlotsCollection();
    }

    public function testCreateWithParameters()
    {
        new SlotsCollection(null, 2);
    }

    public function testGetSlots()
    {
        $collection = new SlotsCollection(null, 2);
        $this->assertCount(14, $collection->getSlots());

        foreach ($collection->getSlots() as $slot) {
            $this->assertEquals(2, $slot->getSize());
        }
    }

    public function testCreateWithSomeExceptions()
    {
        $collection = new SlotsCollection([
            new Slot(Shift::createFromString('Monday nighttime'), 1)
        ], 2);
        $this->assertCount(14, $collection->getSlots());
        $this->assertEquals(2, $collection->getSizeForShift(Shift::createFromString('Monday daytime')));
        $this->assertEquals(1, $collection->getSizeForShift(Shift::createFromString('Monday nighttime')));
    }
}
