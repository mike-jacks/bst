<?php declare(strict_types= 1);
namespace BinaryTree;

class Node {
    public function __construct(public int $value, public ?Node $left = null, public ?Node $right = null) {
        $this->value = $value;
    }
}

class NodeTree {
    public function __construct(public ?Node $root = null, public int $leaves = 0, public int $height = 0, public int $size = 0) {
    }

    public function insert(int $value) {
        if ($this->root == null) {
            $this->root = new Node($value);
            $this->size += 1;
            return;
        }
        if ($value === $this->root->value) {
            return;
        } else if ($value < $this->root->value) {
            if ($this->root->left != null) {
                $this->_recursive_insert($this->root->left, $value, $this->height+1);
            } else {
                $this->root->left = new Node($value);
                $this->size += 1;
                if ($this->height === 0) {
                    $this->height = 1;
                }
            }
        } else if ($value > $this->root->value) {
            if ($this->root->right != null) {
                $this->_recursive_insert($this->root->right, $value, $this->height+ 1);
            } else {
                $this->root->right = new Node($value);
                $this->size += 1;
                if ($this->height === 0) {
                    $this->height = 1;
                }
            }
        }
    }

    private function _recursive_insert(Node $node, int $value, int $current_height) {
        if ($current_height > $this->height) {
            $this->height = $current_height;
        }
        if ($node->value === $value) {
            return;
        } else if ($value < $node->value) {
            if ($node->left != null) {
                $this->_recursive_insert($node->left, $value, $current_height + 1);
            } else {
                $node->left = new Node($value);
                $this->size += 1;
            }
        } else if ($value > $node->value) {
            if ($node->right != null) {
                $this->_recursive_insert($node->right, $value, $current_height + 1);
            } else {
                $node->right = new Node($value);
                $this->size += 1;
            }
        }
    }
    


    public function search(int $value): bool{
        if ($this->root->value === $value) {
            return true;
        } elseif ($value < $this->root->value) {
           if ($this->root->left !== null) {
            return $this->_recursive_search($this->root->left, $value);
           } else {
            return false;
           }
        } else {
           if ($this->root->right !== null) {
            return $this->_recursive_search($this->root->right, $value);
           } else {
            return false;
           } 
        }
    }

    private function _recursive_search(Node $node, int $value): bool {
        if ($node->value === $value) {
            return true;
        } elseif ($value < $node->value) {
            if ($node->left !== null) {
                return $this->_recursive_search($node->left, $value);
            } else {
                return false;
            }
        } else {
            if ($node->right !== null) {
                return $this->_recursive_search($node->right, $value);
            } else {
                return false;
            }
        }
    }

    public function in_order_traversal(): array {
        $in_order_list = [];
        $in_order_list = $this->_recursive_in_order_traversal($this->root, $in_order_list);
        return $in_order_list;

    }

    private function _recursive_in_order_traversal(?Node $node, array $in_order_list): array {
        if ($node->left !== null) {
            $in_order_list = $this->_recursive_in_order_traversal($node->left, $in_order_list);
        }
        $in_order_list[] = $node->value;
        if ($node->right !== null) {
            $in_order_list = $this->_recursive_in_order_traversal($node->right, $in_order_list);
        }
        return $in_order_list;
    }

    public function find_min(): int {
        $current_node = $this->root;
        while ($current_node->left !== null) {
            $current_node = $current_node->left;
        }
        return $current_node->value;
    }
    public function find_max(): int {
        $current_node = $this->root;
        while ($current_node->right !== null) {
            $current_node = $current_node->right;
        }
        return $current_node->value;
    }

    public function count_leaves(): int {
        $leaves_list = [];
        $current_node = $this->root;
        if ($current_node->left === null AND $current_node->right === null) {
            return 0;
        }
        if ($current_node->left !== null) {
            $leaves_list = $this->_recursive_count_leaves($current_node->left, $leaves_list);
        }
        if ($current_node->right !== null) {
            $leaves_list = $this->_recursive_count_leaves($current_node->right, $leaves_list);
        }
        return count($leaves_list);
    }

    private function _recursive_count_leaves(?Node $node, array $leaves_list): array {
        if ($node->left === null AND $node->right === null) {
            $leaves_list[] = $node;
        } else {
            if ($node->left !== null) {
                $leaves_list = $this->_recursive_count_leaves($node->left, $leaves_list);
            }
            if ($node->right !== null) {
                $leaves_list = $this->_recursive_count_leaves($node->right, $leaves_list);
            }
        }
        return $leaves_list;
    }

    public function serialize(): string {
        
        $current = $this->root;
        $pre_order_list = [$current->value];
        if ($current->left !== null) {
            $pre_order_list = $this->_recursive_serialize($current->left, $pre_order_list);
        }
        if ($current->right !== null) {
            $pre_order_list = $this->_recursive_serialize($current->right, $pre_order_list);
        }
        $string = implode(",",$pre_order_list);
        return $string;
    }
    private function _recursive_serialize(?Node $node, array $pre_order_list): array {
            $pre_order_list[] = $node->value;
            if ($node->left !== null) {
                $pre_order_list = $this->_recursive_serialize($node->left, $pre_order_list);
            } if ($node->right !== null) {
                $pre_order_list = $this->_recursive_serialize($node->right, $pre_order_list);
            }
            return $pre_order_list;
        }
    public function deserialize(string $serialize_str, Node $node = null): self{
        if ($node === null) {
            $node = $this->root;
        }
        $serialized_int_array = array_map('intVal', explode(',', $serialize_str));
        $new_bst = new NodeTree();
        foreach ($serialized_int_array as $value) {
            $new_bst->insert($value);
        }
        $this->root = $new_bst->root;
        return $this;
    }
    


    public function delete(int $value) {
        $serialized_bst_str = $this->serialize();
        $parts = explode((string)$value, $serialized_bst_str);
        $parts_without_value = array_filter(array_map(function($part) {
            return trim($part,",");
        }, $parts));
        $reconstructedStr = trim(implode(",", $parts_without_value), ",");
        $this->root = $this->deserialize($reconstructedStr)->root;
    }

    private function _min_value_node(?Node $node) {
        $current = $node;
        // loop down to find the left most leaf
        while($current->left !== null) {
            $current = $current->left;
        }
        return $current;
    }

    public function balance_tree() {
        // Step 1: Get sorted nodes
        $sorted_nodes = $this->in_order_traversal();

        // Step 2: Rebuild the tree from the sorted nodes
        $this->root = $this->_build_balanced_tree($sorted_nodes, 0, count($sorted_nodes) - 1);
    }

    private function _build_balanced_tree(array $sorted_nodes, int $start, int $end) {
        // Base case
        if ($start > $end) {
            return null;
        }

        // Get the middle element and make it root
        $mid = (int)(($start + $end) / 2);
        $node = new Node($sorted_nodes[$mid]);

        // Recursively construct the left subtree and make it left child of root
        $node->left = $this->_build_balanced_tree($sorted_nodes, $start, $mid -1);

        // Recursively construct the right subtree and make it right child of root
        $node->right = $this->_build_balanced_tree($sorted_nodes, $mid + 1, $end);

        return $node;
    }
}

$tree = new NodeTree();
$tree->insert(4);
$tree->insert(2);
$tree->insert(7);
$tree->insert(1);
$tree->insert(3);
$tree->insert(5);
$tree->insert(10);
$tree->insert(15);
echo $tree->serialize(), "\n";
$tree->delete(5);
echo $tree->serialize(),"\n";
