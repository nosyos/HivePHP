<?php
namespace Nosyos\Hivephp\Core;

use Closure;
use Exception;

use Nosyos\Hivephp\Router\Router;
use Nosyos\Hivephp\Router\TrieTree;
use Nosyos\Hivephp\Handler\Handlers;
use Nosyos\Hivephp\Handler\Handler;
use Nosyos\Hivephp\Request\Context;
use Nosyos\Hivephp\Response\JsonResponse;
use Nosyos\Hivephp\Response\Response;

class HivePHP {
    private static $instance;
    private Router $router;
    private TrieTree $tree;
    private Handlers $handlers;
    private FrameworkConfig $config;
    //private Request $request;
    private Context $context;
    private HttpServer $httpServer;

    public function __construct() {
        $this->handlers = new Handlers();
        $this->tree = new TrieTree();
        $this->router = new Router($this->tree, $this->handlers);
        $this->config = FrameworkConfig::getInstance();
        //$this->request = new Request();
        $this->context = new Context();
    }

    public static function initialize() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function addHandler(string $method, string $path, callable|Closure $callback) {
        if (!is_callable($callback)) {
            throw new Exception("Function not found {$callback}");
        }

        $handler = new Handler($path, $callback);
        $this->handlers->addHandler($method, $handler);

        $this->tree->addPath($method, $path);
    }

    public function callHandler(): string {
        $parsedUrl = parse_url($_SERVER['REQUEST_URI']);

        $this->context->setQueryParams();

        if ($this->tree->searchPath($_SERVER['REQUEST_METHOD'], $parsedUrl['path']) !== null) {
            $this->context->setPathParams($this->tree->getPathParams());
        } else {
            // return "Path not found.";
            echo "path not found";
        }
        
        $this->context->setFormData();

        $response = $this->router->resolve(
            $_SERVER['REQUEST_METHOD'],
            $this->tree->searchPath($_SERVER['REQUEST_METHOD'], $parsedUrl['path']),
            $this->context
        );

        if (is_array($response)) {
            $r = new JsonResponse(body: $response);
            $ret = json_encode($r->send());

            if ($ret === false) {
                var_dump(json_last_error_msg());
                return "callHandler Error";
            }

            return $ret;
        } else {
            // TODO: if not have Response interface, error.
            $response->send();
        }
        
        return "Error";

    }

    public function start() {
        $this->httpServer = 
            new HttpServer(
                function() {
                    return $this->callHandler();
                },
                "127.0.0.1", 
                8008
            );
        $this->httpServer->start();
    }

}
