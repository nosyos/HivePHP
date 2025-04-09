<?php
namespace Nosyos\Hivephp\Router;


class TrieTree {
    private array $nodes;
    private int $orderCounter;
    private array $pathParams;

    public function __construct() {
        $this->nodes = [];
        $this->orderCounter = 0;
        $this->pathParams = [];
    }

    public function addPath (string $method, string $path): void {
        if (!isset($this->nodes[$method])) {
            $this->nodes[$method] = new Node('/');
        }

        $current = $this->nodes[$method];

        foreach (explode('/', trim($path, '/')) as $segment) {
            if (strpos($segment, ':') === 0) {
                if (!$current->dynamicChildren) {
                    $current->dynamicChildren[$segment] = new Node($segment);
                }
                $current = $current->dynamicChildren[$segment];
                continue;
            }

            if (!isset($current->children[$segment])) {
                $current->children[$segment] = new Node($segment);
            }
            $current = $current->children[$segment];
        }
        $current->order = $this->orderCounter++;
    }

    public function searchPath(string $method, string $path): ?string {
        if (!isset($this->nodes[$method])) {
            return null;
        }
        
        $current = $this->nodes[$method];
        $resolvedSegments = [];
        $params = [];

        foreach (explode('/', trim($path, '/')) as $segment) {
            if (isset($current->children[$segment])) {
                $current = $current->children[$segment];
                $resolvedSegments[] = $segment;

                continue;
            }

            $dynamicChild = null;
            foreach($current->dynamicChildren as $child) {
                if ($dynamicChild === null || $child->order < $dynamicChild->order) {
                    $dynamicChild = $child;
                }
            }


            if ($dynamicChild) {
                $params[$dynamicChild->dynamicKey] = $segment;
                $current = $dynamicChild;
                $resolvedSegments[] = $dynamicChild->dynamicKey;
                $this->pathParams[ltrim($dynamicChild->dynamicKey, ':')] = $segment;
                continue;
            }

            return null;
        }

        return $current->order !== null ? '/' . implode('/', $resolvedSegments) : null;
    }

    public function getPathParams(): array {
        return $this->pathParams;
    }

    public function __toString() {
        return json_encode([
            'nodes' => $this->nodes
        ]);
    }
}
