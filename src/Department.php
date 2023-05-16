<?php

namespace App;

use Exception;

class Department
{
    public static function list()
    {
        global $conn;

        try {
            $sql = "SELECT d.dept_no, d.dept_name, 
                           CONCAT(e.first_name, ' ', e.last_name) AS manager_name, dm.from_date, dm.to_date, 
                           TIMESTAMPDIFF(YEAR, dm.from_date, dm.to_date) AS num_years 
                    FROM departments d 
                    INNER JOIN dept_manager dm ON d.dept_no = dm.dept_no 
                    INNER JOIN employees e ON dm.emp_no = e.emp_no 
                    ORDER BY dm.from_date 
                    LIMIT 10";

            $statement = $conn->prepare($sql);
            $statement->execute();
            $records = [];

            while ($row = $statement->fetch()) {
                array_push($records, $row);
            }

            return $records;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        return null;
    }
}