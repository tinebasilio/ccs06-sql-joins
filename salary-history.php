<?php

require "config.php";

use App\Employee;

if (isset($_GET['emp_no'])) {
    $emp_no = $_GET['emp_no'];
} else {
    die("Employee not specified.");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Salary History</title>
            <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                padding: 10px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            th {
                background-color: #f5f5f5;
                font-weight: bold;
            }

            tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            tr:hover {
                background-color: #f5f5f5;
            }
        </style>
</head>
<body>

<?php
   // Query to retrieve the employee's salary history and basic information
   $sql = "SELECT
		CONCAT(e.first_name, ' ', e.last_name) AS employee_name,
		e.birth_date, e.gender, e.hire_date, FORMAT(s.salary, 'C') AS salary,
		s.from_date,s.to_date, MAX(t.title) AS title, MAX(d.dept_name) AS dept_name
	   FROM employees AS e
	   JOIN salaries AS s ON e.emp_no = s.emp_no
	   JOIN titles AS t ON e.emp_no = t.emp_no
	   JOIN dept_emp AS de ON e.emp_no = de.emp_no
	   JOIN departments AS d ON de.dept_no = d.dept_no
	   WHERE e.emp_no = :emp_no
     	   GROUP BY
		employee_name,
		birth_date,
		gender,
		hire_date,
		salary,
		from_date,
		to_date
	    ORDER BY from_date DESC";

   $statement = $conn->prepare($sql);
   $statement->bindValue(':emp_no', $emp_no);
   $statement->execute();
   $employees = $statement->fetchAll();

   // Display the employee's basic information
   echo "<h1>{$employees[0]['employee_name']}'s Salary History</h1>";
   echo "<p>Title: {$employees[0]['title']}</p>";
   echo "<p>Birthday: {$employees[0]['birth_date']}</p>";
   echo "<p>Gender: {$employees[0]['gender']}</p>";
   echo "<p>Hire Date: {$employees[0]['hire_date']}</p>";
   echo "<p>Department: {$employees[0]['dept_name']}</p>";

   // Retrieve the first employee (assuming the array is not empty)
   if (!empty($employees)) {
      $employee = $employees[0];
   }

   // Display the employee's salary history in a table
   echo "<table>";
   echo "<tr><th>From Date</th><th>To Date</th><th>Salary</th></tr>";

   // Iterate over each row of salary history and display the data
   foreach ($employees as $row) {
      echo "<tr>";
      echo "<td>{$row['from_date']}</td>";
      echo "<td>{$row['to_date']}</td>";
      echo "<td>\${$row['salary']}</td>";
      echo "</tr>";
   }

   echo "</table>";
?>

</body>
</html>
