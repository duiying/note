# PHP实现栈
```
<?php 
/**
 * 实现栈
 */

// 节点类
class Node
{
    public $prev = NULL;
    public $data;

    public function __construct($data) {
        $this->data = $data;
    }
}

// 栈类
class Stack
{
    public $top = NULL;

    // 压栈
    public function push($data) {
        if ($this->top == NULL) {
            $this->top = new Node($data);
        } else {
            $node = new Node($data);
            $node->prev = $this->top;
            $this->top = $node;
        }
    }

    // 弹栈
    public function pop() {

        // 判断栈是否为空
        if ($this->top == NULL) {
            echo 'Failed: Stack is NULL';
            return;
        }
        
        $this->top = $this->top->prev;
    }

    // 获取所有节点
    public function getAll() {
        $current = $this->top;

        // 判断栈是否为空
        if ($this->top == NULL) {
            echo 'Stack is NULL';
            return;
        }

        while ($current != NULL) {
            echo $current->data . '<br>';
            $current = $current->prev;
        }
    }

}

// 测试
$stack = new Stack();
$stack->push(1);
$stack->push(2);
$stack->push(3);

$stack->getAll();
echo '<br>-----------------<br>';

$stack->pop();
$stack->getAll();
echo '<br>-----------------<br>';

$stack->pop();
$stack->getAll();
echo '<br>-----------------<br>';

$stack->pop();
$stack->getAll();
echo '<br>-----------------<br>';
```
### 打印结果
```
3
2
1

-----------------
2
1

-----------------
1

-----------------
Stack is NULL
-----------------
```