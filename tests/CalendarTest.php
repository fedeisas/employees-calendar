<?php
namespace EmployeesCalendar\Test;

use PHPUnit_Framework_TestCase;
use EmployeesCalendar\Calendar;
use EmployeesCalendar\Employee;
use EmployeesCalendar\Shift;
use EmployeesCalendar\Slot;
use EmployeesCalendar\SlotsCollection;
use EmployeesCalendar\WorkableShift;

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

    public function testGetAllPossibleShifts()
    {
        $calendar = new Calendar(6, 2016);
        $shifts = $calendar->getAllWorkableShifts();

        $this->assertCount(60, $shifts);
    }

    public function testGetAllPossibleShiftsStructure()
    {
        $calendar = new Calendar(
            6,
            2016,
            new SlotsCollection(
                [
                    new Slot(Shift::createFromString('Friday nighttime'), 3),
                    new Slot(Shift::createFromString('Saturday nighttime'), 2)
                ],
                1
            )
        );
        $shifts = $calendar->getAllWorkableShifts();

        $this->assertCount(60, $shifts);

        foreach ($shifts as $workableShift) {
            $this->assertInstanceOf(WorkableShift::class, $workableShift);
            if ((string) $workableShift->getShift() === 'Friday nighttime') {
                $this->assertEquals($workableShift->getSize(), 3);
            } elseif ((string) $workableShift->getShift() === 'Saturday nighttime') {
                $this->assertEquals($workableShift->getSize(), 2);
            } else {
                $this->assertEquals($workableShift->getSize(), 1);
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
