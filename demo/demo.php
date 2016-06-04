<?php
require_once __DIR__ . '/../vendor/autoload.php';

use EmployeesCalendar\Calendar;
use EmployeesCalendar\Employee;
use EmployeesCalendar\Shift;
use EmployeesCalendar\Slot;
use EmployeesCalendar\SlotCollection;
use EmployeesCalendar\Solver;

$options = getopt('m:y:');
$month = !empty($options['m']) ? (int) $options['m'] : (int) date('m');
$year = !empty($options['y']) ? (int) $options['y'] : (int) date('Y');

$calendar = new Calendar(
    $month,
    $year,
    new SlotCollection(
        [
            new Slot(Shift::createFromString('Friday nighttime'), 3),
            new Slot(Shift::createFromString('Saturday nighttime'), 2)
        ],
        1
    )
);

$solver = new Solver($calendar);

$solver->addEmployee(
    new Employee('Empleado 1', [
        Shift::createFromString('Sunday daytime'),
        Shift::createFromString('Saturday nighttime')
    ])
);

$solver->addEmployee(
    new Employee('Empleado 2', [
        Shift::createFromString('Wednesday daytime'),
    ])
);

$solver->addEmployee(
    new Employee('Empleado 3', [
        Shift::createFromString('Monday nighttime'),
        Shift::createFromString('Tuesday daytime')
    ]),
    2
);


$solver->solve();
