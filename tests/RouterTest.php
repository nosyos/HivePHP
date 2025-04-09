<?php
use PHPUnit\Framework\TestCase;
use Nosyos\Hivephp\Router\Router;
use Nosyos\Hivephp\Handler\Handler;
use Nosyos\Hivephp\Handler\Handlers;
use Nosyos\Hivephp\Request\Context;
use Nosyos\Hivephp\Router\TrieTree;

class RouterTest extends TestCase {

    public function testParsePath(): void {
        $handlers = new Handlers();
        $tree = new TrieTree();
        $tree->addPath('GET', '/users/profile/edit');
        $router = new Router($tree, $handlers);

        $path = 'http://www.test.com/users/profile/edit';
        $expectedSegments = '/users/profile/edit';

        $this->assertSame($expectedSegments, $router->parsePath($path));
    }

    public function testResolve(): void {
        $handlers = new Handlers();
        $handler = new Handler('/users/profile/edit', function() {
            return "This is /users/profile/edit";
        });
        $handlers->addHandler('GET', $handler);
        

        $tree = new TrieTree();
        $tree->addPath('GET', '/users/profile/edit');

        $router = new Router($tree, $handlers);
        $context = new Context();
        $context->setQueryParams($tree->getPathParams());

        $this->assertSame(
            $router->resolve('GET', '/users/profile/edit', $context), 
            "This is /users/profile/edit"
        );
        
    }

    public function testResolvePathParam(): void {
        $handlers = new Handlers();
        $handler = new Handler('/users/profile/:id', function() {
            return "This is /users/profile/edit";
        });
        $handlers->addHandler('GET', $handler);
        

        $tree = new TrieTree();
        $tree->addPath('GET', '/users/profile/:id');

        $router = new Router($tree, $handlers);
        $context = new Context();
        $context->setQueryParams($tree->getPathParams());

        $this->assertSame(
            $router->resolve('GET', $tree->searchPath('GET', '/users/profile/111'), $context), 
            "This is /users/profile/edit"
        );
        
    }
}
