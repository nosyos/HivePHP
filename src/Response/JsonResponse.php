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
        $this->headers = $headers;
        $this->body = $body;
    }

    public function send(): string {
        http_response_code($this->responseCode);
        foreach($this->headers as $key => $val) {
            header("$key: $val");
        }
        return json_encode($this->body);
    }

    public function __toString() {
        return json_encode(get_object_vars($this));
    }
}
