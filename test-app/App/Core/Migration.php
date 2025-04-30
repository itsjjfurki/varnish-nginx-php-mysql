<?php

namespace App\Core;

use Exception;

class Migration
{
    private $db;

    public function __construct()
    {
        $this->db = new Db()->getConnection();
    }

    public function up(): void
    {
        try {
            $this->db->query("CREATE TABLE IF NOT EXISTS employees (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                bio TEXT NOT NULL
            )");

            echo "Table 'employees' created successfully.\n";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}
