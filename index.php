<?php

require "config.php";

use App\Department;

$depts = Department::list();

?>

<!DOCTYPE html>
<html>
<head>
   <title>List of Departments</title>
</head>
<body>
   <h1>List of Departments</h1>
      <table>
         <tr>
            <th>Department Number</th>
            <th>Department Name</th>
            <th>Manager Name</th>
            <th>From Date</th>
            <th>To Date</th>
            <th>Number of Years</th>
            <th>Link</th>
         </tr>

   <?php
   
   // Iterate over each row of department information and display the data
   foreach ($depts as $dept) {
      echo "<tr>";
      echo "<td>{$dept['dept_no']}</td>";
      echo "<td>{$dept['dept_name']}</td>";
        echo "<td>{$dept['manager_name']}</td>";
        echo "<td>{$dept['from_date']}</td>";
        echo "<td>{$dept['to_date']}</td>";
        echo "<td>{$dept['num_years']}</td>";
        echo "<td><a href=\"/employees.php?dept_no={$dept['dept_no']}\">View Employees</a></td>";
        echo "</tr>";
    }
    ?>
  </table>
</body>
</html>
