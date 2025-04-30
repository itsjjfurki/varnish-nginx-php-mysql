<?php

namespace App\Controllers;

use App\Core\Controller;

class Home extends Controller
{
    public function index(): void
    {
        echo 'Hello 👋';
    }
}