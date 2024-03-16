<?php


use PHPUnit\Framework\TestCase;
use BinaryTree\NodeTree;

require_once 'vendor/autoload.php';


class NodeTreeTest extends TestCase {

    public function testInsertAndSearch() {
        $tree = new NodeTree();
        $this->assertNull($tree->root);  // Initially, root should be null

        $tree->insert(10);
        $this->assertNotNull($tree->root);
        $this->assertEquals(10, $tree->root->value);

        $tree->insert(5);
        $tree->insert(15);
        $this->assertTrue($tree->search(5));
        $this->assertTrue($tree->search(15));
        $this->assertFalse($tree->search(20));  // Value not inserted
    }

    public function testFindMinAndMax() {
        $tree = new NodeTree();
        $tree->insert(10);
        $tree->insert(5);
        $tree->insert(15);
        $tree->insert(2);
        $tree->insert(18);

        $this->assertEquals(2, $tree->find_min());
        $this->assertEquals(18, $tree->find_max());
    }

    public function testInOrderTraversal() {
        $tree = new NodeTree();
        $tree->insert(10);
        $tree->insert(5);
        $tree->insert(15);
        $expectedOrder = [5, 10, 15];
        $this->assertEquals($expectedOrder, $tree->in_order_traversal());
    }

    public function testCountLeaves() {
        $tree = new NodeTree();
        $tree->insert(10);
        $tree->insert(5);
        $tree->insert(15);

        // Tree with one leaf
        $this->assertEquals(2, $tree->count_leaves());
    }

    public function testDelete() {
        $tree = new NodeTree();
        $tree->insert(10);
        $tree->insert(5);
        $tree->insert(15);
        $tree->insert(3);
        $tree->insert(7);

        $tree->delete(5);
        $this->assertFalse($tree->search(5));  // 5 should be deleted
        echo $tree->serialize();
        $this->assertTrue($tree->search(7));   // 7 should still exist
    }

    public function testBalanceTree() {
        $tree = new NodeTree();
        $tree->insert(10);
        $tree->insert(5);
        $tree->insert(15);
        $tree->insert(20);
        $tree->insert(2);

        $tree->balance_tree();
        // You should test specific balanced tree properties, 
        // but here we'll just check size and in-order traversal for simplicity
        $this->assertEquals(5, $tree->size);
        $expectedOrder = [2, 5, 10, 15, 20];
        $this->assertEquals($expectedOrder, $tree->in_order_traversal());
    }

    public function testSerializeAndDeserialize() {
        $tree = new NodeTree();
        $tree->insert(10);
        $tree->insert(5);
        $tree->insert(15);

        $serializedTree = $tree->serialize();
        $newTree = new NodeTree();
        $newTree->deserialize($serializedTree);

        $this->assertEquals([5, 10, 15], $newTree->in_order_traversal());
    }
}
