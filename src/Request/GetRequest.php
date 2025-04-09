<?php

namespace Nosyos\Hivephp\Request;

use DateTimeImmutable;

class GetRequest extends Request {
    public array $queryParams = [];
    public array $pathParams = [];

    public function __construct() {
        // TODO: varidate param/headers
        parse_str($_SERVER['QUERY_STRING'], $this->queryParams);

        $httpHost = $_SERVER['HTTP_HOST'] ?? '';
        $parts = explode(':', $httpHost);

        parent::__construct(
            $_SERVER['REQUEST_METHOD'],
            $this->createURL(),
            $_SERVER['REQUEST_URI'],
            new DateTimeImmutable(),
            $_SERVER['REMOTE_ADDR'] ?? '',
            $_SERVER['CONTENT_TYPE'] ?? '',
            $_SERVER['SERVER_PROTOCOL'] ?? '',
            $_COOKIE,
            $_FILES, // TODO: inprement files process.
            $parts[0] ?? '',
            $parts[1] ?? '80',
            $_SERVER['HTTP_USER_AGENT'],   
        );

        $this->setHeaders();
    }
}
