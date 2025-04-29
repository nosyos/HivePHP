<?
require_once __DIR__ . '/../vendor/autoload.php';

use Nosyos\Hivephp\Core\HivePHP;
use Nosyos\Hivephp\Request\Context;

$hivephp = HivePHP::initialize();

$hivephp->addHandler('GET', '/user/:id', function (Context $context) {
    return ["resp" => "This is /user/{$context->pathParams['id']}"];
});

$hivephp->start();

