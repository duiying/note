# PHP实现队列
```
<?php
/**
 * 实现队列
 */

// 节点类
class Node
{
    public $data;           // 节点数据
    public $next = NULL;    // 下一节点

    public function __construct($data) {
        $this->data = $data;
    }
}

// 队列类
class Queue
{
    private $header = NULL; // 头节点
    private $last = NULL;   // 尾节点

    // 入队
    public function push($node) {
        if ($this->header == NULL) {
            $this->header = $node;
            $this->last = $node;
        } else {
            $this->last->next = $node;
            $this->last = $node;
        }
    }

    // 出队
    public function shift() {
        
        // 判断队列是否为空
        if ($this->header == NULL) {
            echo 'Failed: Queue is NULL';
            return;
        }

        $this->header = $this->header->next;
    }

    // 查找所有节点
    public function getAll() {
        $current = $this->header;

        // 判断队列是否为空
        if ($this->header == NULL) {
            echo 'Queue is NULL';
            return;
        }

        do {
            echo $current->data . '<br>';
        } while ($current != NULL && $current = $current->next);
    }

}

// 测试
$queue = new Queue();
$node1 = new Node(1);
$node2 = new Node(2);
$node3 = new Node(3);

$queue->push($node1);
$queue->push($node2);
$queue->push($node3);

$queue->getAll();
echo '<br>-----------------<br>';

$queue->shift();
$queue->getAll();
echo '<br>-----------------<br>';

$queue->shift();
$queue->getAll();
echo '<br>-----------------<br>';

$queue->shift();
$queue->getAll();
echo '<br>-----------------<br>';
```
### 打印结果
```
1
2
3

-----------------
2
3

-----------------
3

-----------------
Queue is NULL
-----------------
```