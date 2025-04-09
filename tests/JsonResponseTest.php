<?php
use PHPUnit\Framework\TestCase;

use Nosyos\Hivephp\Response\JsonResponse;


class JsonResponseTest extends TestCase {
    public function testCreateJsonResponse(): void {
        $res = new JsonResponse(100, [], ['response_cd' => '10']);

        $arr = json_decode(sprintf($res));

        $this->assertSame($arr->responseCode, 100);
    }
}

