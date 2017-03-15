<?php

use PHPUnit\Framework\TestCase;
use App\Response;

final class ResponseTest extends TestCase
{
    private $res;

    public function setUp()
    {
        $this->res = new Response;
    }

    public function dataProvider()
    {
        return [
            ['body', 'body'],
            [404, 404],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSetBodyAndGetBody($body, $expected)
    {
        $this->res->setBody($body);
        $this->assertEquals($expected, $this->res->getBody());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testWithStatusAndGetStatusCode($code, $expected)
    {
        $this->res->withStatus($code);
        $this->assertEquals($expected, $this->res->getStatusCode());
    }

    public function testWithJson()
    {
        $this->res->withJson([
          'data' => 'value'
        ]);
        $this->assertEquals(['Content-Type' => 'application/json'],
            $this->res->getHeaders());
        $this->assertEquals('{"data":"value"}', $this->res->getBody());
    }

    public function testWithHeaderAndGetHeaders()
    {
        $this->assertEquals(['Content-Type' => 'text/plain'],
            $this->res->withHeader('Content-Type', 'text/plain')
            ->getHeaders());
    }
}
