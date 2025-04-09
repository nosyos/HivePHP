<?php
use PHPUnit\Framework\TestCase;

use Nosyos\Hivephp\Handler\Handler;


class HandlerTest extends TestCase {

    public function testExecute() {
        $handler = new Handler('/', function() {
            return "This is executeTest result.";
        });

        $this->assertSame($handler->execute(), "This is executeTest result.");
    }
}
