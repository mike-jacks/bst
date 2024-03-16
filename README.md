# BST: Binary Search Tree Assignment

This was quite a challenging assignment. I was able to complete all the base challenges with little to no help from chat gpt
I think I only needed chat gpt help with remembering some pytest syntax, but I was able to follow the logic for the bst build.
When it came to delete and rebalance, I was completely lost and used chat gpt to help guide me. I understand it about 70%,
not sure if I could write it my own yet. I would like to see if there is a way to balance a tree on the fly vs calling on a method
to create a brand new tree that is sorted.

## Update 5/15/23
Gabe and I met earlier in the day to talk code and stuff. He brought up his BST project, and we worked through some of the later functions. 
I came to an epiphany working on his project of ANOTHER way to do the delete and rebalance way, outside of Chat GPT's help/code. 
We worked together to solve it for his coding assignment. I then came home and after a few hours had passed, decided to recreate it from scratch
in my head. I ended up improving it a bit more than what we did earlier in the day. Overall glad I came back and revisted, I now have a greater understanding
to create a BST and implement delete and balance. The big epiphany was when Gabe made a comment of using serialize to make a list and just remove the value from the
list and recreate the tree. That was all I needed to hear to get my wheels spinning in my brain to help come up with the solution to solve delete and balance. 
I have not updated the PHP version, just the python version FYI.

## PHP Version

Recreated the BST.py in PHP. Used phpunit to run NodeTreeTest.php.
