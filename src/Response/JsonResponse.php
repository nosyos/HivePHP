<?php

namespace Nosyos\Hivephp\Response;


class JsonResponse implements Response {

    private int $responseCode;
    private array $headers;
    private array $body;

    public function __construct(int $responseCode=200, array $headers=[], array $body = []) {
        $this->responseCode = $responseCode;
        if (count($headers) === 0) {
            $this->headers['Content-Type'] = 'application/json; charset=UTF-8';
            $this->headers['Cache-Control'] = 'no-cache, must-revalidate';
        }

        foreach ($headers as $key => $val) {
            $this->headers[$key] = $val;
        }
        $this->body = $body;
    }

    public function send(): array {
        $resp = [];
        $resp['responseCode'] = $this->responseCode;
        $resp['headers'] = $this->headers;
        $resp['body'] = $this->body;

        return $resp;
    }

    public function __toString() {
        return json_encode(get_object_vars($this));
    }
}
