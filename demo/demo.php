<?php
require_once __DIR__ . '/../vendor/autoload.php';

use EmployeesCalendar\Calendar;
use EmployeesCalendar\Employee;
use EmployeesCalendar\Shift;
use EmployeesCalendar\Slot;
use EmployeesCalendar\SlotsCollection;
use EmployeesCalendar\Solver;

$options = getopt('m:y:');
$month = !empty($options['m']) ? (int) $options['m'] : (int) date('m');
$year = !empty($options['y']) ? (int) $options['y'] : (int) date('Y');

$calendar = new Calendar(
    $month,
    $year,
    new SlotsCollection(
        [
            new Slot(Shift::createFromString('Friday nighttime'), 2),
            new Slot(Shift::createFromString('Saturday nighttime'), 2)
        ],
        1
    ),
    [
        (int) date('w', strtotime('saturday')),
        (int) date('w', strtotime('sunday')),
    ]
);

$solver = new Solver($calendar);

$solver->addEmployee(
    new Employee('Empleado 1')
);

$solver->addEmployee(
    new Employee('Empleado 2')
);

$solver->addEmployee(
    new Employee('Empleado 3', [
        Shift::createFromString('Tuesday daytime'),
        Shift::createFromString('Tuesday nighttime'),
    ])
);

$solver->solve();

foreach ($solver->getManager()->getCollection() as $date => $row) {
    foreach ($row as $shift => $employees) {
        foreach ($employees as $employee) {
            echo join(' - ', [$date, $shift, $employee->getName()]) . PHP_EOL;
        }
    }
}
