<?php
namespace Nosyos\Hivephp\Router;

use Nosyos\Hivephp\Handler\Handlers;
use Nosyos\Hivephp\Request\Context;

class Router {

    private TrieTree $tree;
    private Handlers $handlers;

    public function __construct(TrieTree $tree, Handlers $handlers) {
        $this->tree = $tree;
        $this->handlers = $handlers;
    }
    
    public function parsePath(string $url): string {
        $parsedUrl = parse_url($url);
        return parse_url($url)['path'];
    }


    public function resolve(string $method, string $path, Context $context): mixed {
        if ($method === null) {
            return 'Request method not found.';
        }

        if (!$this->tree->searchPath($method, $path)) {
            return 'Path not found';
        }

        $handler = $this->handlers->getHandler($method, $path);
        if ($handler) {
            // TODO: implement call handler process.
            return $handler->execute($context);
        }

        return 'No maching handler found';
    }
}
