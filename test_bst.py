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

def test_insert(full_tree):
    assert full_tree.size == 6
    full_tree.insert(11)
    assert full_tree.size == 7

def test_insert_and_search(full_tree):
    value = 11
    assert full_tree.search(value) == False
    full_tree.insert(value)
    assert full_tree.search(value) == True

def test_in_order_traversal(full_tree):
    assert full_tree.in_order_traversal() == [2,3,5,7,8,10]
    full_tree.in_order_traversal()

def test_find_min(full_tree):
    assert full_tree.find_min() == 2

def test_find_max(full_tree):
    assert full_tree.find_max() == 10

def test_get_height(full_tree):
    assert full_tree.height == 3

def test_count_leaves(full_tree):
    assert full_tree.count_leaves() == 2

def test_serialize(full_tree):
    assert full_tree.serialize() == "5,2,3,7,10,8"

def test_deserialize(full_tree):
    new_tree = copy.deepcopy(full_tree)
    assert new_tree.deserialize("5,7,2,10,3,8").serialize() == full_tree.serialize()

def test_delete(full_tree):
    assert full_tree.search(10) == True
    assert full_tree.serialize() == "5,2,3,7,10,8"
    full_tree.delete(10)
    assert full_tree.search(10) == False
    assert full_tree.serialize() == "5,2,3,7,8"

