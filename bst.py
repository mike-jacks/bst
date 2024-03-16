from typing import Self


class Node:
    def __init__(self, value):
        self.value = value
        self.left = None
        self.right = None 

class NodeTree:
    def __init__(self):
        self.root = None
        self.leaves = 0
        self.height = 0
        self.size = 0

    def insert(self, value: int) -> None:
        if self.root == None:
            self.root = Node(value)
            self.size += 1
            return
        if value == self.root.value:
            return
        if value < self.root.value: 
            if self.root.left != None:
                return self._recursive_insert(self.root.left, value, 2)
            else:
                self.root.left = Node(value)
                self.size += 1
                self.height = 1 if self.height == 0 else self.height
        else:
            if self.root.right != None:
                return self._recursive_insert(self.root.right, value, 2)
            else:
                self.root.right = Node(value)
                self.size += 1
                self.height = 1 if self.height == 0 else self.height

    def _recursive_insert(self, node: Node, value: int, current_depth: int) -> None:
        if self.height < current_depth:
            self.height = current_depth
        if value == node.value:
            return
        if value < node.value:
            if node.left != None:
                return self._recursive_insert(node.left, value, current_depth + 1)
            else:
                node.left = Node(value)
                self.size += 1
        else:
            if node.right != None:
                return self._recursive_insert(node.right, value, current_depth + 1)
            else:
                node.right = Node(value)
                self.size += 1

    def search(self, value: int) -> bool:
        if self.root.value == value:
            return True
        elif value < self.root.value:
            if self.root.left != None:
                return self._recursive_search(self.root.left, value)
            else:
                return False
        else:
            if self.root.right != None:
                return self._recursive_search(self.root.right, value)
            else:
                return False

    def _recursive_search(self, node: Node, value: int) -> bool:
        if node.value == value:
            return True
        elif value < node.value:
            if node.left != None:
                return self._recursive_search(node.left, value)
            else:
                return False
        else:
            if node.right != None:
                return self._recursive_search(node.right, value)
            else:
                return False

    def in_order_traversal(self) -> list[int]:
        in_order_list = []
        self._recursive_in_order_traversal(self.root, in_order_list)
        return in_order_list

    def _recursive_in_order_traversal(self, node: Node, in_order_list: list):
        if node.left != None:
            self._recursive_in_order_traversal(node.left, in_order_list)
        in_order_list.append(node.value)
        if node.right != None:
            self._recursive_in_order_traversal(node.right, in_order_list)

    def find_min(self) -> int:
        current_node = self.root
        while current_node.left != None:
            current_node = current_node.left
        return current_node.value

    def find_max(self) -> int:
        current_node = self.root
        while current_node.right != None:
            current_node = current_node.right
        return current_node.value

    def count_leaves(self) -> int:
        leaves_list = []
        current_node = self.root
        if current_node.left == None and current_node.right == None:
            return 0
        if current_node.left != None:
            self._recursive_count_leaves(current_node.left, leaves_list)
        if current_node.right != None:
            self._recursive_count_leaves(current_node.right, leaves_list)
        return len(leaves_list)

    def _recursive_count_leaves(self, node: Node, leaves_list: list):
        if node.left == None and node.right == None:
            leaves_list.append(node)
        if node.left != None:
            self._recursive_count_leaves(node.left, leaves_list)
        if node.right != None:
            self._recursive_count_leaves(node.right, leaves_list)

    def serialize(self) -> str:
        current = self.root
        pre_order_list = [current.value]
        if current.left != None:
            self._recursive_serialize(current.left, pre_order_list)
        if current.right != None:
            self._recursive_serialize(current.right, pre_order_list)
        string = ",".join(map(str, pre_order_list))
        return string

    def _recursive_serialize(self, node: Node, pre_order_list: list):
        pre_order_list.append(node.value)
        if node.left != None:
            self._recursive_serialize(node.left, pre_order_list)
        if node.right != None:
            self._recursive_serialize(node.right, pre_order_list)

    def deserialize(self,serialize_str: str, node: Node = None, ) -> Self:
        if node is None:
            node = self.root
        searialized_str_iter_object = iter(map(lambda x: int(x), serialize_str.split(",")))
        new_bst = NodeTree()
        while (current_value := next(searialized_str_iter_object, None)) != None:
            new_bst.insert(current_value)
        self.root = new_bst.root
        return self
            

    # Recreated my delete function at home after working on it with Gabe on his BST project earlier in the day
        
    def delete(self, value: int):
        serialized_bst_str = self.serialize()
        serialized_bst_str = "".join(serialized_bst_str.split("," + str(value)))
        self.root = self.deserialize(serialized_bst_str).root
    
    # Recreated my balance tree function at home after working on it with Gabe on his BST project earlier in the day
    
    def balance_tree(self):
        def _rec_balance_tree(sub_list: list[int]):
            if len(sub_list) == 1:
                balance_tree_list.append(sub_list[0])
                return
            if len(sub_list) == 2:
                balance_tree_list.append(sub_list[0])
                balance_tree_list.append(sub_list[1])
                return
            mid_index = len(sub_list) // 2
            mid_value = sub_list[mid_index]
            left_list = sub_list[:mid_index]
            right_list = sub_list[mid_index+1:]
            balance_tree_list.append(mid_value)
            _rec_balance_tree(left_list)
            _rec_balance_tree(right_list)
            
            
        balance_tree_list = []
        bst_in_order = self.in_order_traversal()
        length = len(bst_in_order)
        if length <= 2:
            return
        mid_index = length // 2
        mid_value = bst_in_order[mid_index]
        left_list = bst_in_order[:mid_index]
        right_list = bst_in_order[mid_index+1:]
        balance_tree_list.append(mid_value)
        _rec_balance_tree(left_list)
        _rec_balance_tree(right_list)

        new_bst = NodeTree()
        for value in balance_tree_list:
            new_bst.insert(value)
        self.root = new_bst.root       

