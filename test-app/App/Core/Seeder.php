<?php

namespace App\Core;

use Exception;

class Seeder
{
    private $db;

    public function __construct()
    {
        $this->db = new Db()->getConnection();
    }

    private function generateRandomName(): string
    {
        $firstNames = ["John", "Jane", "Michael", "Sarah", "David", "Emily", "James", "Jessica", "Daniel", "Laura"];
        $lastNames = ["Smith", "Johnson", "Williams", "Brown", "Jones", "Garcia", "Miller", "Davis", "Martinez", "Hernandez"];

        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];

        return $firstName . ' ' . $lastName;
    }

    private function generateBio(): string
    {
        $length = rand(1000, 2000);

        $alphanumeric = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $characters = $alphanumeric . ' ';

        $randomString = '';
        $charCount = 0;

        for ($i = 0; $i < $length; $i++) {
            if ($charCount >= rand(10, 20)) {
                $randomString .= ' ';
                $charCount = 0;
            } else {
                $randomChar = $characters[rand(0, strlen($characters) - 1)];
                $randomString .= $randomChar;

                if (str_contains($alphanumeric, $randomChar)) {
                    $charCount++;
                }
            }
        }

        return $randomString;
    }

    public function seed($count = 10): void
    {
        try {
            $sql = "INSERT INTO employees (name,bio) VALUES (:name,:bio)";
            $stmt = $this->db->prepare($sql);

            for ($i = 0; $i < $count; $i++) {
                $name = $this->generateRandomName();
                $bio = $this->generateBio();

                $stmt->execute([':name' => $name, ':bio' => $bio]);
            }

            echo "$count random employees have been added successfully.\n";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}
