<?php
namespace EmployeesCalendar\Test;

use PHPUnit_Framework_TestCase;
use EmployeesCalendar\Calendar;
use EmployeesCalendar\Employee;
use EmployeesCalendar\Shift;
use EmployeesCalendar\Slot;
use EmployeesCalendar\SlotCollection;

class CalendarTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $calendar = new Calendar();
    }

    public function testCreateAndGetPrettyDateName()
    {
        $calendar = new Calendar();
        $this->assertTrue(is_string($calendar->getPrettyDateName()));
    }

    public function testCreateWithParametersAndGetPrettyDateName()
    {
        $calendar = new Calendar(6, 2016);
        $this->assertEquals('June 2016', $calendar->getPrettyDateName());
    }

    public function testCreateWithParametersAndGetDaysInMonth()
    {
        $calendar = new Calendar(6, 2016);
        $this->assertEquals(30, $calendar->getDaysInMonth());
    }

    public function testGetAllPossibleShifts()
    {
        $calendar = new Calendar(6, 2016);
        $shifts = $calendar->getAllPossibleShifts();

        $this->assertCount(60, $shifts);
    }

    public function testGetAllPossibleShiftsStructure()
    {
        $calendar = new Calendar(
            6,
            2016,
            new SlotCollection(
                [
                    new Slot(Shift::createFromString('Friday nighttime'), 3),
                    new Slot(Shift::createFromString('Saturday nighttime'), 2)
                ],
                1
            )
        );
        $shifts = $calendar->getAllPossibleShifts();

        $this->assertCount(60, $shifts);

        foreach ($shifts as $shift) {
            if ((string) $shift[1] === 'Friday nighttime') {
                $this->assertEquals($shift[2], 3);
            } elseif ((string) $shift[1] === 'Saturday nighttime') {
                $this->assertEquals($shift[2], 2);
            } else {
                $this->assertEquals($shift[2], 1);
            }
        }
    }

    public function testGetNumberOfSpecialDaysThisMonth()
    {
        $calendar = new Calendar(6, 2016);
        $this->assertEquals(8, $calendar->getNumberOfSpecialDaysThisMonth());
    }

    public function testIsInSpecialDay()
    {
        $calendar = new Calendar(6, 2016);
        $this->assertTrue($calendar->isInSpecialDay(Shift::createFromString('Friday nighttime')));
        $this->assertFalse($calendar->isInSpecialDay(Shift::createFromString('Monday nighttime')));
    }
}
