<?php

namespace Nosyos\Hivephp\Request;

use DateTimeImmutable;

class Request {
    protected string $method;
    protected string $fullUri;
    protected string $uriPath;
    protected array $headers;
    protected DateTimeImmutable $datetime;  // using DateTimeImmutable::ISO8601_EXPANDED
    protected string $clientIP;
    protected string $contentType;
    protected string $httpVersion;
    protected array $cookies;
    protected array $files;
    protected string $hostName;
    protected string $port;
    protected string $userAgent;

    public function __construct(string $method,
                                string $fullUri,
                                string $uriPath,
                                DateTimeImmutable $datetime,
                                string $clientIP,
                                string $contentType,
                                string $httpVersion,
                                array $cookies,
                                array $files,
                                string $hostName,
                                string $port,
                                string $userAgent,   
    ) {
        $this->method = $method;
        $this->fullUri = $fullUri;
        $this->uriPath = $uriPath;
        $this->clientIP = $clientIP;
        $this->datetime = $datetime;
        $this->contentType = $contentType;
        $this->httpVersion = $httpVersion;
        $this->cookies = $cookies;
        $this->setFiles($files);
        $this->hostName = $hostName;
        $this->port = $port;
        $this->userAgent = $userAgent;
    }

    public function setHeaders(): void {
        $this->headers = [];

        foreach($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headername = str_replace('_', '-', strtolower(substr($key, 5)));
                $this->headers[ucwords($headername, '-')] = $value;
            } elseif (in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH', 'CONTENT_ENCODING'])) {
                $headername = str_replace('_', '-', strtolower($key));
                $this->headers[ucwords($headername, '-')] = $value;
            }
        }
    }

    public function setFiles(array $files): void {
        $this->files = $files;
    }

    public function createURL(): string {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        if (strpos($host, ':') === false && isset($_SERVER['SERVER_PORT'])) {
            $host .= ':' . $_SERVER['SERVER_PORT'];
        }
        return $protocol . '://' . $host . $_SERVER['REQUEST_URI'];
    }

    public function getFullURI(): string {
        return $this->fullUri;
    }

}
