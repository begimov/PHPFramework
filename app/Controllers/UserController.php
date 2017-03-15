<?php

namespace App\Controllers;

class UserController
{
    public function show($res)
    {
        return $res->withJson([
          'data' => 'value'
        ]);
    }
}
