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
            return null;
        }
        if ($value == $this->root->value) {
            return null;
        }
        if ($value < $this->root->value) {
            if ($this->root->left != null) {
               return $this->_recursive_insert($this->root->left, $value, 2); 
            } else {
                $this->root->left = new Node($value);
                $this->size += 1;
                $this->height = ($this->height === 0) ? 1 : $this->height;
            }
        }
        else {
            if ($this->root->right != null) {
                return $this->_recursive_insert($this->root->right, $value, 2);
            } else {
                $this->root->right = new Node($value);
                $this->size += 1;
                $this->height = ($this->height === 0) ? 1 : $this->height;
            }
        }
    }

    private function _recursive_insert(Node $node, int $value, int $current_depth) {
        $this->height = ($this->height < $current_depth) ? $current_depth : $this->height;
        if ($value == $node->value) {
            return null;
        }
        if ($value < $node->value) {
            if ($node->left !== null) {
                return $this->_recursive_insert($node->left, $value, $current_depth + 1);
            } else {
                $node->left = new Node($value);
                $this->size += 1;
                return null;
            }
        } else {
            if ($node->right !== null) {
                return $this->_recursive_insert($node->right, $value, $current_depth + 1);
            } else {
                $node->right = new Node($value);
                $this->size += 1;
                return null;
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
        }
        if ($node->left !== null) {
            $this->_recursive_count_leaves($node->left, $leaves_list);
        }
        if ($node->right !== null) {
            $this->_recursive_count_leaves($node->right, $leaves_list);
        }
        return $leaves_list;
    }

    public function serialize(): string {
        $current = $this->root;
        $dict_order = [];
        $dict_order["$current->value"] = $current->value;
        if ($current->left !== null) {
            $dict_order = $this->_recursive_serialize($current->left, $dict_order);
        }
        if ($current->right !== null) {
            $dict_order = $this->_recursive_serialize($current->right, $dict_order);
        }
        $string = implode(",", array_keys($dict_order));
        return $string;
    }

    private function _recursive_serialize(?Node $node, array $dict_order): array {
        $dict_order["$node->value"] = $node->value;
        if ($node->left !== null) {
            return $this->_recursive_serialize($node->left, $dict_order);
        } if ($node->right !== null) {
            return $this->_recursive_serialize($node->right, $dict_order);
        }
        return $dict_order;
    }

    public function deserialize(string $tree) {
        $this->root = null;
        foreach (explode(",", $tree) as $value) {
            $this->insert((int)$value);
        }
        return $this;
    }
    


    public function delete(int $value) {
        $this->root = $this->_recursive_delete($this->root, $value);
    }

    private function _recursive_delete(?Node $node, int $value): ?Node {
        // Base case
        if ($node === null) {
            return $node;
        }

        // If the value to be deleted is smaller than the node's value,
        // then it lies in the left subtree
        if ($value < $node->value) {
            $node->left = $this->_recursive_delete($node->left, $value);
        }

        // If the value to be deleted is greater than the node's,
        // then it lies in the right subtree
        if ($value > $node->value) {
            $node->right = $this->_recursive_delete($node->right, $value);
        }

        // If value is same as node's value, then this is the node to be deleted
        else {
            // Node with only one child or no child
            if ($node->left === null) {
                $temp = $node->right;
                $node = null;
                return $temp;
            } elseif ($node->right === null) {
                $temp = $node->left;
                $node = null;
                return $temp;
            }

            // Node with two children:
            // Get the inorder successor (smallest in the right subtree)
            $temp = $this->_min_value_node($node->right);

            // Copy the inorder successor's content to this node
            $node->value = $temp->value;

            // Delete the inorder successor
            $node->right = $this->_recursive_delete($node->right, $temp->value);
        }
        return $node;
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
$tree->insert(5);
$tree->insert(7);
$tree->insert(2);
$tree->insert(3);
$tree->insert(10);
$tree->insert(8);
$tree->balance_tree();