<?php

use App\Core\Migration;
use App\Core\Seeder;

try {
    $initializedFile = __DIR__ . '/../.initialized';

    if (!file_exists($initializedFile)) {
        $migration = new Migration();
        $migration->up();

        $seeder = new Seeder();
        $seeder->seed(50000);

        file_put_contents($initializedFile, "Initialized on " . date('Y-m-d H:i:s') . "\n");

        echo "Initialization complete.\n";
    }
} catch (\Exception $ex) {
    echo $ex->getMessage();
}