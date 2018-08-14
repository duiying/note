# PHP实现单链表
```
<?php
/**
 * 实现单链表
 */

// 节点类
class Node
{
    public $id;             // 节点ID
    public $data;           // 节点数据
    public $next = NULL;    // 下一节点

    public function __construct($id, $data) {
        $this->id = $id;
        $this->data = $data;
    }
}

// 单链表类
class singleLinkList
{
    private $header = NULL; // 头节点
    private $last = NULL;   // 尾节点

    // 添加节点
    public function add($node) {
        $current = $this->header;

        // 判断链表是否为空
        if ($this->header == NULL) {
            $this->header = $node;
            $this->last = $node;
        } else {
            // 判断ID是否重复
            do {
                if ($current->id == $node->id) {
                    echo 'Failed: ID already exist';
                    return;
                }
            } while ($current->next != NULL && $current = $current->next);

            $this->last->next = $node;
            $this->last = $node;
        }
    }

    //查找节点
    public function find($id) {
        $current = $this->header;

        // 判断链表是否为空
        if ($current == NULL) {
            echo 'LinkList is NULL';
            return;
        }

        // 判断是否是第一个节点
        if ($current->id == $id) {
            echo 'Success: data is ' . $current->data;
            return;
        }

        while ($current->next != NULL) {
            if ($current->next->id == $id) {
                echo 'Success: data is ' . $current->next->data;
                return;
            }
            $current = $current->next;
        }

        echo 'Failed: ID not found';
    }

    // 查找所有节点
    public function getAll() {
        $current = $this->header;

        // 判断链表是否为空
        if ($current == NULL) {
            echo 'LinkList is NULL';
            return;
        }

        do {
            echo $current->id . ' --- ' . $current->data . '<br>';
        } while ($current->next != NULL && $current = $current->next);
    }

    // 删除
    public function del($id) {
        $current = $this->header;

        // 判断链表是否为空
        if ($current == NULL) {
            echo 'Failed: LinkList is NULL';
            return;
        }

        // 判断是否是第一个节点
        if ($current->id == $id) {
            $this->header = $this->header->next;
            echo 'Success: Del is ok';
            return;
        }
        
        while ($current->next != NULL) {
            if ($current->next->id == $id) {
                $current->next = $current->next->next;
                echo 'Success: Del is ok';
                return;
            }
            $current = $current->next;
        }

        echo 'Failed: ID not found';
    }

    // 清空链表
    public function clear() {
        $this->header = NULL;
    }

    // 判断链表是否为空
    public function isEmpty() {
        if ($this->header == NULL) {
            echo 'LinkList is NULL';
            return;
        }
        echo 'LinkList is not NULL';
    }
}

// 测试
$list =  new singleLinkList();
$node1 = new Node('1', 'data1');
$node2 = new Node('2', 'data2');
$node3 = new Node('3', 'data3');
$node4 = new Node('3', 'data4');
$list->add($node1);
$list->add($node2);
$list->add($node3);
$list->add($node4);

echo '<br>-----------------<br>';
$list->getAll();
$list->del(3);

echo '<br>-----------------<br>';
$list->getAll();
$list->find(2);

echo '<br>-----------------<br>';
$list->isEmpty();

echo '<br>-----------------<br>';
$list->clear();
$list->getAll();

echo '<br>-----------------<br>';
$list->isEmpty();
```
### 打印结果
```
Failed: ID already exist
-----------------
1 --- data1
2 --- data2
3 --- data3
Success: Del is ok
-----------------
1 --- data1
2 --- data2
Success: data is data2
-----------------
LinkList is not NULL
-----------------
LinkList is NULL
-----------------
LinkList is NULL
```