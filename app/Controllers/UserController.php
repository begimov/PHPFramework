<?php

namespace App\Controllers;

use App\Models\User;

class UserController
{

    private $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function show($res)
    {
        $users = $this->db->prepare('SELECT * FROM users');
        $users->execute();
        $results = $users->fetchAll(\PDO::FETCH_CLASS, User::class);
        return $res->withJson($results);
    }
}
