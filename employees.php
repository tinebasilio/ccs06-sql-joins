<?php
require "config.php";

use App\Employee;

if (isset($_GET['dept_no'])) {
   $dept_no = $_GET['dept_no'];
} else {
   die("Department not specified.");
}
?>

<?php
   // Retrieve the list of employees in the department
   $emps = Employee::listByDepartment($dept_no);

   // Display the table headers
   echo "<table>";
   echo "<tr><th>Employee Title</th><th>Employee Name</th><th>Birthday</th><th>Age</th><th>Gender</th><th>Hire Date</th><th>Latest Salary</th><th>Link</th></tr>";

   // Query to retrieve the department name and manager name
   $sql = "SELECT d.dept_name, CONCAT(e.first_name, ' ', e.last_name) AS manager_name
            FROM departments d
            JOIN dept_manager dm 
               ON d.dept_no = dm.dept_no
            JOIN employees e 
               ON dm.emp_no = e.emp_no
            WHERE d.dept_no = :dept_no
            LIMIT 1";
   $statement = $conn->prepare($sql);
   $statement->bindValue(':dept_no', $dept_no);
   $statement->execute();
   $display = $statement->fetch();

   // Display the department name and manager name
   echo "<h1>{$display['dept_name']}</h1>";
   echo "<p>Department Manager: {$display['manager_name']}</p>";

   // Loop through each employee in the department to retrieve the latest salary information of the employee
   foreach ($emps as $emp) {
      $sql = "SELECT FORMAT(s.salary, 'C') AS salary, s.from_date, s.to_date
              FROM salaries s
              WHERE emp_no = :emp_no
              ORDER BY to_date DESC
              LIMIT 1";

      $statement = $conn->prepare($sql);
      $statement->bindValue(':emp_no', $emp['emp_no']);
      $statement->execute();
      
      // Display employee information in each table row
      echo "<tr>";
      echo "<td>{$emp['title']}</td>";
      echo "<td>{$emp['employee_name']}</td>";
      echo "<td>{$emp['birth_date']}</td>";
      echo "<td>{$emp['employee_age']}</td>";
      echo "<td>{$emp['gender']}</td>";
      echo "<td>{$emp['hire_date']}</td>";
      echo "<td>\${$emp['salary']}</td>";
      echo "<td><a href=\"/salary-history.php?emp_no={$emp['emp_no']}\">View Salary History</a></td>";
      echo "</tr>";
   }

   echo "</table>";
?>
