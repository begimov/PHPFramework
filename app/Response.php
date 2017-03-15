<?php

namespace App;

class Response
{
    private $body;

    private $statusCode = 200;

    private $headers = [];

    public function setBody($data)
    {
        $this->body = $data;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function withStatus($code)
    {
        $this->statusCode = $code;
        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function withJson($data)
    {
        $this->withHeader('Content-Type', 'application/json');
        $this->body = json_encode($data);
        return $this;
    }

    public function withHeader($header, $value)
    {
        $this->headers[$header] = $value;
        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}
