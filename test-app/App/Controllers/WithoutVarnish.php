<?php

namespace App\Controllers;

use App\Core\Controller;

class WithoutVarnish extends Controller
{
    public function index(): void
    {
        $this->setLoadStartTime();

        $sql = "SELECT * FROM employees WHERE bio LIKE '%test%'";
        $records = $this->db->query($sql);
        $records->fetchAll();

        $this->setLoadEndTime();

        echo $this->getExecutionTime();
    }
}