<?php
namespace Nosyos\Hivephp\Router;

class Node{
    public string $val;
    public array $children = [];
    public array $dynamicChildren = [];
    public bool $isDynamic = false;
    public ?string $dynamicKey = null;
    public ?int $order = null;

    public function __construct(string $val) {
        $this->val = $val;
        $this->isDynamic = strpos($val, ':') === 0;
        $this->dynamicKey = $this->isDynamic ? $val : null;
        $this->children = [];
        $this->dynamicChildren = [];
    }

    public function matched(string $segment): bool {
        return $this->isDynamic && $this->val === $segment;
    }

    public function __toString() {
        return json_encode([
            'val' => $this->val,
            'isDynamic' => $this->isDynamic,
            'dynamicKey' => $this->dynamicKey,
            'order' => $this->order,
            'children' => $this->children,
            'dynamicChildren' => $this->dynamicChildren
        ]);
    }

}

