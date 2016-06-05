<?php
require_once __DIR__ . '/../vendor/autoload.php';

use EmployeesCalendar\Calendar;
use EmployeesCalendar\Employee;
use EmployeesCalendar\EmployeesCollection;
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

$employeesCollection = new EmployeesCollection();

$employeesCollection->add(
    new Employee('Empleado 1')
);

$employeesCollection->add(
    new Employee('Empleado 2')
);

$employeesCollection->add(
    new Employee('Empleado 3', [
        Shift::createFromString('Tuesday daytime'),
        Shift::createFromString('Tuesday nighttime'),
    ])
);

$solver = new Solver($calendar, $employeesCollection);
$solver->solve();

foreach ($solver->getFormattedOutput() as $row) {
    echo $row . PHP_EOL;
}
