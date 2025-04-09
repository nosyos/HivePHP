<?php

namespace Nosyos\Hivephp\Handler;

use Closure;
use Nosyos\Hivephp\Request\Context;


class Handler {
    public string $path;
    private $callback;

    public function __construct(string $path, string|Closure $callback) {
        $this->path = $path;
        $this->callback = $callback;
    }

    public function execute(Context $context) {
        return call_user_func($this->callback, $context);
    }
}
