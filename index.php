<?php

require "config.php";

use App\Department;
use App\Employee;

$depts = Department::list();
$emps = Employee::list();

echo '<pre>';
var_dump($depts);
echo '<hr />';
var_dump($emps);