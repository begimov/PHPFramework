<?php

namespace App\Controllers;

class HomeController
{
    private $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function show()
    {
        return 1;
    }
}
