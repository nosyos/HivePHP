<?php

namespace Nosyos\Hivephp\Core;

class FrameworkConfig {
    private static $instance;
    public string $baseUserCodePath;

    public function __construct() {
        $this->baseUserCodePath = dirname(realpath($_SERVER['SCRIPT_FILENAME']));
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
