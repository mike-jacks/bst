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
        dict_order = {}
        dict_order[str(current.value)] = current.value
        if current.left != None:
            self._recursive_serialize(current.left, dict_order)
        if current.right != None:
            self._recursive_serialize(current.right, dict_order)
        string = ",".join(iter(dict_order.keys()))
        return string

    def _recursive_serialize(self, node: Node, dict_order: dict):
        dict_order[str(node.value)] = node.value
        if node.left != None:
            self._recursive_serialize(node.left, dict_order)
        if node.right != None:
            self._recursive_serialize(node.right, dict_order)


    
    def deserialize(self, tree: str) -> None:
        self.root = None
        for value in tree.split(","):
            self.insert(int(value))
        return self


    # Used Chat GPT to help with this one...I had no idea how to do it. 
    def delete(self, value: int):
        self.root = self._delete(self.root, value)

    def _delete(self, node, value) -> Node:
        # Base case
        if node is None:
            return node

        # If the value to be deleted is smaller than the node's value,
        # then it lies in the left subtree
        if value < node.value:
            node.left = self._delete(node.left, value)

        # If the key to be ddeleted is greater than the node's,
        # then it lies in the right subtree
        elif(value > node.value):
            node.right = self._delete(node.right, value)

        # If key is same as root's key, then this is the node to be deleted
        else:
            # Node with only one child or no child
            if node.left is None:
                temp = node.right
                node = None
                return temp
            elif node.right is None:
                temp = node.left
                node = None
                return temp

            # Node with two childeren:
            # Get the inorder successor (smallest in the right subtree)
            temp = self._min_value_node(node.right)

            # Copy the inorder successor's content to this node
            node.value = temp.value

            # Delete the inorder successor
            node.right = self._delete(node.right, temp.value)

        return node

    def _min_value_node(self, node):
        current = node
        # loop down to find the left most leaf
        while(current.left is not None):
            current = current.left
        return current
    
    def balance_tree(self):
        # Step 1: Get sorted nodes
        sorted_nodes = self.in_order_traversal()

        # Step 2: Rebuild the tree from the sorted nodes
        self.root = self._build_balanced_tree(sorted_nodes, 0, len(sorted_nodes) - 1)

    def _build_balanced_tree(self, sorted_nodes, start, end):
        # Base case
        if start > end:
            return None

        # get the middle element and make it root
        mid = (start + end) // 2
        node = Node(sorted_nodes[mid])

        # Recursively construct the left subtree and make it left child of root
        node.left = self._build_balanced_tree(sorted_nodes, start, mid - 1)

        # Recursively construct the right subtree and make it right child of root
        node.right = self._build_balanced_tree(sorted_nodes, mid + 1, end)

        return node


    





    

       
