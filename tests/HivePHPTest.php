<?php

use PHPUnit\Framework\TestCase;

use Nosyos\Hivephp\Core\HivePHP;
use Nosyos\Hivephp\Request\Context;

require_once __DIR__."/func.php";

class HivePHPTest extends TestCase {

    /*
    public function testinitialHivePHP(): void {
        $hivephp = HivePHP::initialize();

        $hivephp->addHandler('GET', '/healthcheck', function () {
            return "This is health check";
        });

        $this->assertSame($hivephp->callHandler('GET', '/healthcheck'),
            "This is health check"
        );
    }

    public function testCallFunction(): void {
        $hivephp = HivePHP::initialize();
        $hivephp->addHandler('GET', '/healthcheck', 'testFunc');

        $this->assertSame($hivephp->callHandler('GET', '/healthcheck'),
            "This is testFunc"
        );

    }
     */
/*
    public function testCallFunctionWithQueryParam(): void {
        $hivephp = HivePHP::initialize();
        $hivephp->addHandler('GET', '/user', function (Context $context){
            return "This is /user?test={$context->queryParams['test']}";
        });

        $_SERVER['REQUEST_URI'] = '/user?test=111';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['test'] = '111';

        $this->assertSame($hivephp->callHandler(),
            "This is /user?test=111"
        );

    }
 */
/*
    public function testCallFunctionWithPathParam(): void {
        $hivephp = HivePHP::initialize();
        $hivephp->addHandler('GET', '/user/:id', function (Context $context){
            return ["resp" => "This is /user/{$context->pathParams['id']}"];
        });

        $_SERVER['REQUEST_URI'] = '/user/222';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['test'] = '222';

        ob_start();
        $hivephp->callHandler();
        $output = json_decode(ob_get_clean(), true);
        $this->assertSame($output["resp"], "This is /user/222");

    }
 */
    public function testHttpServerAndResponse(): void {

        $serverProcess = proc_open("php tests/TestScript.php", [], $pipes);
        
        if (!is_resource($serverProcess)) {
            die("Failed to start server process");
        }
        sleep(5);

        $response = file_get_contents('http://127.0.0.1:8008/user/222');
        if ($response === false) {
            var_dump(error_get_last());
        }

        proc_terminate($serverProcess);
        proc_close($serverProcess);

        $response = json_decode($response);

        $this->assertSame($response["resp"], "This is /user/222");
    }
}
