<?php

require_once __DIR__ . '/../vendor/autoload.php';

use EmployeesCalendar\Calendar;
use EmployeesCalendar\Employee;
use EmployeesCalendar\Shift;
use EmployeesCalendar\Solver;

$options = getopt('m:y:');
$month = !empty($options['m']) ? (int) $options['m'] : (int) date('m');
$year = !empty($options['y']) ? (int) $options['y'] : (int) date('Y');

$calendar = new Calendar(
    $month,
    $year,
    [
        'weeklyRest' => 1,
    ]
);

$calendar->addEmployee(
    new Employee('Empleado 1', [
        Shift::createFromString('Sunday daytime'),
        Shift::createFromString('Saturday nighttime')
    ])
);

$calendar->addEmployee(
    new Employee('Empleado 2', [
        Shift::createFromString('Wednesday daytime'),
    ])
);

$calendar->addEmployee(
    new Employee('Empleado 2', [
        Shift::createFromString('Wednesday daytime'),
    ])
);

$calendar->addEmployee(
    new Employee('Empleado 3'),
    [
        Shift::createFromString('Monday nighttime'),
        Shift::createFromString('Tuesday daytime')
    ]
);

$solver = new Solver($calendar);
var_dump($solve->solve());
