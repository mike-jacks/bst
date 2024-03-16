import pytest 
import copy
from bst import NodeTree, Node

@pytest.fixture
def full_tree() -> NodeTree:
    tree = NodeTree()
    tree.insert(5)
    tree.insert(7)
    tree.insert(2)
    tree.insert(10)
    tree.insert(3)
    tree.insert(8)
    return tree


@pytest.fixture
def empty_tree() -> NodeTree:
    return NodeTree()

def test_insert(full_tree: NodeTree):
    assert full_tree.size == 6
    full_tree.insert(11)
    assert full_tree.size == 7

def test_insert_and_search(full_tree: NodeTree):
    value = 11
    assert full_tree.search(value) == False
    full_tree.insert(value)
    assert full_tree.search(value) == True

def test_in_order_traversal(full_tree: NodeTree):
    assert full_tree.in_order_traversal() == [2,3,5,7,8,10]
    full_tree.in_order_traversal()

def test_find_min(full_tree: NodeTree):
    assert full_tree.find_min() == 2

def test_find_max(full_tree: NodeTree):
    assert full_tree.find_max() == 10

def test_get_height(full_tree: NodeTree):
    assert full_tree.height == 3

def test_count_leaves(full_tree: NodeTree):
    assert full_tree.count_leaves() == 2

def test_serialize(full_tree: NodeTree):
    assert full_tree.serialize() == "5,2,3,7,10,8"
    new_tree = NodeTree()
    new_tree.insert(4)
    new_tree.insert(2)
    new_tree.insert(1)
    new_tree.insert(3)
    new_tree.insert(7)
    new_tree.insert(5)
    new_tree.insert(10)
    new_tree.insert(15)
    assert new_tree.serialize() == "4,2,1,3,7,5,10,15"
    

def test_deserialize(full_tree: NodeTree):
    orig_tree = copy.deepcopy(full_tree)
    new_tree = NodeTree()
    new_tree.insert(4)
    new_tree.insert(2)
    new_tree.insert(1)
    new_tree.insert(3)
    new_tree.insert(7)
    new_tree.insert(5)
    new_tree.insert(10)
    new_tree.insert(15)
    assert orig_tree.serialize() != "4,2,1,3,7,5,10,15"
    orig_tree.deserialize("4,7,10,15,5,2,3,1")
    assert orig_tree.serialize() == "4,2,1,3,7,5,10,15"
    new_tree.deserialize("5,7,2,10,3,8")
    assert new_tree.serialize() ==  "5,2,3,7,10,8"


# Used chat GPT for the following two tests and builds in Node Tree.
def test_delete(full_tree: NodeTree):
    assert full_tree.search(10) == True
    assert full_tree.serialize() == "5,2,3,7,10,8"
    full_tree.delete(10)
    assert full_tree.search(10) == False
    assert full_tree.serialize() == "5,2,3,7,8"

def test_balance_tree(full_tree: NodeTree):
    def check_balanced(node):
        """
        Helper function to cvheck if a tree is balanced.
        Returns a tuple (is_balanced, hjeight) for each subtree.
        """
        if node is None:
            return True, 0 # Base case: Empty trees are balanced and have height 0.

        # Check subtrees recursively
        left_balanced, left_height = check_balanced(node.left)
        right_balanced, right_height = check_balanced(node.right)

        # A tree is balanced if both subtrees are balanced and their heights deffer
        # by no more than 1
        is_balanced = left_balanced and right_balanced and abs(left_height - right_height) <= 1

        height = max(left_height, right_height) + 1

        return is_balanced, height
    
    for value in [1,11,12,13,14,16,6,20]:
        full_tree.insert(value)
    
    # Balance the tree
    full_tree.balance_tree()

    # Check if the tree is now balanced
    is_balanced, _ = check_balanced(full_tree.root)
    assert is_balanced
