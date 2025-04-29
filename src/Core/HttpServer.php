<?php

namespace Nosyos\Hivephp\Core;

use RuntimeException;

class HttpServer {

    private string $host;
    private int $port;
    private $requestHandler;

    public function __construct($requestHandler, $host="127.0.0.1", $port=8008) {
        $this->host = $host;
        $this->port = $port;
        $this->requestHandler = $requestHandler;
    }

    public function start(): void {
        $socket = stream_socket_server("tcp://{$this->host}:{$this->port}", $errno, $errstr);
        if (!$socket) {
            throw new RuntimeException("Error: $errstr ($errno)");
        }

        //echo "Server started at {$this->host}:{$this->port}".PHP_EOL;

        while ($conn = stream_socket_accept($socket)) {
            $rawRequest = fread($conn, 1024);

            $parseRequest = $this->parseRequest($rawRequest);
            $this->setServerGlobals($parseRequest);

            $response = ($this->requestHandler)($parseRequest);
            $json_resp = json_decode($response, true);
            if ($json_resp === null) {
                var_dump(json_last_error_msg());
                exit;
            }

            // add
            $statusLine = "HTTP/1.1 ". $json_resp['responseCode']." OK";
            $headers = implode("\r\n", array_map(fn($key, $val) => "$key: $val", array_keys($json_resp['headers']), $json_resp['headers']));
            $body = json_encode($json_resp['body'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            fwrite($conn, "$statusLine\r\n$headers\r\n\r\n{$body}");
            
            // add end

            fflush($conn);
            fclose($conn);
        }

        fclose($socket);
    }

    private function parseRequest(string $rawRequest): array {
        $lines = explode("\r\n", $rawRequest);

        if (empty($lines[0])) {
            throw new RuntimeException('Invalid request: Empty request line');
        }
        $requestLine = explode(' ', array_shift($lines));
        if (count($requestLine) < 3) {
            throw new RuntimeException('Invalid request format');
        }
        $headers = [];
        $bodyStartIndex = null;

        foreach ($lines as $index => $line) {
            if (empty($line)) {
                $bodyStartIndex = $index + 1;
                break;
            }

            if (preg_match('/^([^:]+):\s*(.+)$/', $line, $matches)) {
                $headers[trim($matches[1])] = trim($matches[2]);
            }
        }

        $body = '';
        if ($bodyStartIndex !== null && isset($headers['Content-Length'])) {
            $body = implode("\r\n", array_slice($lines, $bodyStartIndex));
        }

        return [
            'method' => $requestLine[0],
            'path' => $requestLine[1],
            'protocol' => $requestLine[2],
            'headers' => $headers,
            'body' => $body
        ];
    }

    private function setServerGlobals(array $request): void {
        $_SERVER['REQUEST_METHOD'] = $request['method'];
        $_SERVER['REQUEST_URI'] = $request['path'];
        $_SERVER['SERVER_PROTOCOL'] = $request['protocol'];
        $_SERVER['HTTP_HOST'] = $request['headers']['host'] ?? 'localhost';

        foreach ($request['headers'] as $key => $value) {
            $_SERVER['HTTP_'.strtoupper(str_replace('-', '_', $key))] = $value;
        }

        $query = parse_url($request['path'], PHP_URL_QUERY);
        if ($query !== null ) {
            parse_str($query, $_GET);
        } else {
            $_GET = [];
        }

        if ($request['method'] === 'POST') {
            $_POST = json_decode($request['body'], true) ?? [];
        }
    }
}
