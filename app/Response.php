<?php

namespace App;

class Response
{
    private $body;

    public function setBody($data)
    {
        $this->body = $data;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }
}
