<?php

use PHPUnit\Framework\TestCase;

use Nosyos\Hivephp\Core\FrameworkConfig;

class FrameworkConfigTest extends TestCase {
    public function testGetInstance() {
        $config = FrameworkConfig::getInstance();

        $this->assertSame('/app/vendor/bin', $config->baseUserCodePath);
    }
}
