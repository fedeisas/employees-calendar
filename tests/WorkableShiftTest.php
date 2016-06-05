<?php
namespace EmployeesCalendar\Test;

use PHPUnit_Framework_TestCase;
use EmployeesCalendar\Shift;
use EmployeesCalendar\WorkableShift;

class WorkableShiftTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Wrong date: foo
     */
    public function testCreateBadArguments()
    {
        new WorkableShift('foo', Shift::createFromString('Monday nighttime'), 1);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid workable shift size: asd
     */
    public function testCreateBadSize()
    {
        new WorkableShift('2016-05-05', Shift::createFromString('Monday nighttime'), 'asd');
    }

    public function testCreate()
    {
        $date = '2016-05-05';
        $shift = Shift::createFromString('Monday nighttime');
        $size = 1;
        $workableShift = new WorkableShift($date, $shift, $size);

        $this->assertEquals($date, $workableShift->getDate());
        $this->assertEquals($shift, $workableShift->getShift());
        $this->assertEquals($size, $workableShift->getSize());
    }
}
