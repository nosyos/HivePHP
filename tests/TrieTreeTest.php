<?php
use PHPUnit\Framework\TestCase;

use Nosyos\Hivephp\Router\TrieTree;
//use Nosyos\Hivephp\Router\Node;

class TrieTreeTest extends TestCase {
    /* 
    public function testaddPath(): void {
        $trieTree = new TrieTree;

        $trieTree->addPath('GET', '/user/add');
        $trieTree->addPath('GET', '/user/:id');

        echo $trieTree;
    }
     */
    
    public function testaddPathTrue(): void {
        $trieTree = new TrieTree();

        $trieTree->addPath('GET', '/user/add');

        $this->assertSame($trieTree->searchPath('GET', '/user/add'), '/user/add');
    }
    
    public function testaddPathFalse(): void {
        $trieTree = new TrieTree();

        $trieTree->addPath('GET', '/user/add');

        $this->assertSame($trieTree->searchPath('POST', '/user/add'), null);
    }

    public function testNoPath(): void {
        $trieTree = new TrieTree();

        $trieTree->addPath('GET', '/user/add');

        $this->assertSame($trieTree->searchPath('GET', '/user/del'), null);
    }

    public function testPartOfPath(): void {
        $trieTree = new TrieTree();

        $trieTree->addPath('GET', '/user/add');

        $this->assertSame($trieTree->searchPath('GET', '/user'), null);
    }
    
    public function testDynamic(): void {
        $trieTree = new TrieTree();

        $trieTree->addPath('GET', '/user/:id');

        $this->assertSame($trieTree->searchPath('GET', '/user/111'), '/user/:id');
    }

    public function testDynamic2(): void {
        $trieTree = new TrieTree();

        $trieTree->addPath('GET', '/user/:id');

        $trieTree->searchPath('GET', '/user/111');
        
        print_r($trieTree->getPathParams());

        $this->assertSame($trieTree->getPathParams()['id'], '111');
    }
}
