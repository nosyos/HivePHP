<?php

use PHPUnit\Framework\TestCase;

use Nosyos\Hivephp\Request\GetRequest;

class RequestTest extends TestCase {

    public function testGetRequest(): void {
        $_SERVER = array_merge($_SERVER, [
            'QUERY_STRING' => 'name=test&lang=PHP',
            'REQUEST_METHOD' => 'GET',
            'HTTP_HOST' => 'example.com',
            'REQUEST_URI' => '/user/list?name=test&lang=PHP',
            'REMOTE_ADDR' => '192.168.1.100',
            'CONTENT_TYPE' => 'Application/Json',
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'HTTP_USER_AGENT' => 'Test/UserAgent'
        ]);

        $getRequest = new GetRequest();

        $this->assertSame($getRequest->getFullURI(), 'http://example.com/user/list?name=test&lang=PHP');
    }
}

