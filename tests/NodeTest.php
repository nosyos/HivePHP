<?php
use PHPUnit\Framework\TestCase;

use Nosyos\Hivephp\Router\Node;


class NodeTest extends TestCase {
    public function testPrintNode(): void {
        $node = new Node('test_path');
        $test = json_decode($node);

        $this->assertSame($test->val, 'test_path');
    }
/*
    public function testgetParent(): void {
        $node = new Node('root');
        $childNode = new Node('child');
        $node->addChild($childNode);

        $this->assertSame($node, $childNode->getParent());
    }

    public function testaddChild(): void {
        $node = new Node('root');
        $childNode = new Node('child');
        $node->addChild($childNode);

        $this->assertSame($node->children[$childNode->val], $childNode);
    }

    public function testgetFullPath(): void {
        $node = new Node('root');
        $childNode = new Node('child');
        $gchildNode = new Node('gchild');

        $node->addChild($childNode);
        $childNode->addChild($gchildNode);

        $this->assertSame('/root/child/gchild', $gchildNode->getFullPath());
    }
 */
}
