<?php

namespace App\Core;

abstract class Controller
{
    private float $loadStartTime = 0;
    private float $loadEndTime = 0;
    protected ?\PDO $db;

    abstract public function index();

    public function __construct()
    {
        $conn = new Db();
        $this->db = $conn->getConnection();
    }

    public function setLoadStartTime(): void
    {
        $this->loadStartTime = microtime(true);
    }

    public function setLoadEndTime(): void
    {
        $this->loadEndTime = microtime(true);
    }

    public function getExecutionTime(): string
    {
        $diff = $this->loadEndTime - $this->loadStartTime;
        return number_format($diff,6) . ' seconds';
    }
}