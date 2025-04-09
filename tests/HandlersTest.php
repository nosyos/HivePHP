<?php
use PHPUnit\Framework\TestCase;

use Nosyos\Hivephp\Handler\Handlers;
use Nosyos\Hivephp\Handler\Handler;
use Nosyos\Hivephp\Router\TrieTree;
use Nosyos\Hivephp\Router\Router;


class HandlersTest extends TestCase {

    public function testaddAndGetHandler(): void {
        $handlers = new Handlers();
        $handler = new Handler('/user/get', function() {
            return "This is /user/get handler.";
        });

        $handlers->addHandler('GET', $handler);

        $testhandler = $handlers->getHandler('GET', '/user/get');

        $this->assertSame($handler->execute(), $testhandler->execute());

    }
}

