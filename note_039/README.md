# PHP坑人小题
> 1. 求下面输出结果  

```
<?php

$a = 3;
$b = 5;

if ($a = 5 || $b = 7) {
    $a++;
    $b++;
}

echo $a . ' ' . $b;
```
答案: 1 6  
解析: 先执行 5 || $b = 7, 5是真所以返回true, $b = 7不会执行, 并把true赋值给$a, 因为$a = true, 所以$a++还是1, $b++ = 6  

> 2. 求下面输出结果  

```
<?php

$count = 5;

function getCount()
{
    static $count = 0;
    return $count++;
}

$count++;
getCount();
echo getCount();
```
答案: 1
解析: 考察++i和i++以及局部静态变量, 因为局部静态变量只被初始化一次, 所以最后输出1  

> 3. 求下面输出结果  

```
<?php

$a = count("567") + count(null) + count(false);
echo $a;
```
答案: 2  
解析: 考察count(), 如果count($str)返回1, 如果count(boolean)返回1, 如果count(null)返回0  

> 4. 求下面输出结果  

```
<?php

$a = 0.2 + 0.7;
$b = 0.9;
var_dump($a == $b);
```
答案: boolean(false)  
解析: 不要用等号去比较浮点数