<?php declare(strict_types= 1);
namespace BinaryTree;

class Node {
    public function __construct(public int $value, public ?Node $left = null, public ?Node $right = null) {
        $this->value = $value;
    }
}