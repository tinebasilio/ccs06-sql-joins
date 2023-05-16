<?php

namespace App;

use Exception;

class Employee
{
    public static function listByDepartment($dept_no)
    {
        global $conn;

        try {
            $sql = "SELECT t.title,
                           CONCAT(e.first_name, ' ', e.last_name) AS employee_name,
                           e.emp_no, e.birth_date, TIMESTAMPDIFF(YEAR, e.birth_date, CURDATE()) AS employee_age,
                           e.gender, e.hire_date, FORMAT(s.salary, 'C') AS salary
                   FROM employees e
                   INNER JOIN dept_emp de ON e.emp_no = de.emp_no
                   INNER JOIN titles t ON e.emp_no = t.emp_no
                   INNER JOIN salaries s ON e.emp_no = s.emp_no
                   WHERE de.dept_no = :dept_no
                   AND t.to_date = '9999-01-01'
                   AND s.to_date = '9999-01-01'
                   LIMIT 10";

            $statement = $conn->prepare($sql);
            $statement->execute();

            $employees = [];

            while ($row = $statement->fetch()) {
                array_push($employees, $row);
            }

            return $employees;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        return null;
    }
}