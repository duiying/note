# 利用循环链表解决约瑟夫环问题
```
<?php
/**
 * 利用循环链表解决约瑟夫环问题
 * 约瑟夫环问题: n个小孩坐在一圈,每数到3就退出去,最后余下的是谁？
 */

// 节点类
class Node 
{
    public $name;
    public $next = NULL;

    public function __construct($name) {
        $this->name = $name;
    }
}

// 循环链表类
class circleList
{
    private $header = NULL;

    public function __construct($str) {
        $node = NULL;

        foreach (explode(" ", $str) as $k => $v) {
            if ($node) {
                $node->next = new Node($v);
                $node = $node->next;
            } else {
                $node = new Node($v);
                $this->header = $node;
            }
        }

        $node->next = $this->header;
    }

    public function find() {
        $current = $this->header;
        $count = 1;

        while ($current->next != $current) {
            if ($count % 3 == 2) {
                $current->next = $current->next->next;
            } else {
                $current = $current->next;
            }
            $count++;
        }

        echo $current->name;
    }
}

// 测试
header('Content-type:text/html;charset=utf-8');
$str = "圣夏彤 邴丹丹 台浩邈 鲜依瑶 闪又青 威心远 佛乐正";

$list = new circleList($str);
$list->find();
```
### 打印结果
```
鲜依瑶
```