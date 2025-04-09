<?php

namespace Nosyos\Hivephp\Handler;

class Handlers {
    private array $callbacks;

    public function __construct() {
        $this->callbacks = [];
    }

    public function addHandler(string $method, Handler $handler): void {
        if (!isset($this->callbacks[$method])) {
            $this->callbacks[$method] = [];
        }
        $this->callbacks[$method][$handler->path] = $handler;
    }

    public function getHandler(string $method, string $fullpath): ?Handler {
        return $this->callbacks[$method][$fullpath] ?? null;
    }
}
