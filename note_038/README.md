# PHP编码技巧
> 合理的命名  

```
命名要有实际含义
命名风格保持一致
不用语言关键字
```

> 适当的注释  

> 变量初始化  

下面的代码有什么问题?
```
<?php

$arr = [1, 2, 3];

foreach ($arr as $k => $v) {
    $newArr[] = $v;
}

print_r($newArr);
```
这段代码是正常运行的, 但是不符合规范, 因为当$arr = [] 的时候会报错 PHP Notice:  Undefined variable: newArr , 正确的编码规范应该先初始化$newArr变量  
```
<?php

$arr = [1, 2, 3];

$newArr = [];
foreach ($arr as $k => $v) {
    $newArr[] = $v;
}

print_r($newArr);
```

> 优先使用单引号  

```
单引号的执行效率高于双引号
$row['id']的效率是$row[id]的7倍
```

> 字符编码统一  

```
PHP文件编码 = 模板文件编码 = 数据库编码
```

> 防御式编程思想  

```
保护程序免遭非法输入数据的危害
错误处理/异常处理
隔离程序: 把数据的错误处理/异常处理专门用一层来处理, 避免程序变得臃肿
```

> 纯PHP代码, 文件末尾删除PHP结束标记  

```
如果是HTML和PHP互相嵌套的代码, 文件末尾不删除PHP结束标记
如果是纯PHP代码, 文件末尾删除PHP结束标记
```

> 优先使用PHP内置函数  

用内置函数优化代码(求数组中最大数的下标)  
优化前
```
<?php

function maxKey($arr)
{
    $maxVal = max($arr);
    foreach ($arr as $key => $val) {
        if ($val == $maxVal) {
            $maxKey = $key;
        }
    }
    return $maxKey;
}

$arr = [0, -1, 3, 'a' => 15, 3];
echo maxKey($arr);
```
优化后
```
<?php

$arr = [0, -1, 3, 'a' => 15, 3];

$maxVal = max($arr);
$maxKey = array_search($maxVal, $arr);

echo $maxKey;
```
> 时刻备份源代码  

```
IDE备份/Git备份
```

> 有效期的原则  

```
对网上发布的文章持有怀疑态度, 因为他人发布的技术分享有可能已经过时
```

> 语法糖  

语法糖指的是计算机语言中添加的某种语法, 这种语法对语言的功能并没有影响, 但是更方便程序员使用
```
1. 用 i += 1 代替 i = i+1 (前者效率更高)

2. 用 isset 代替 strlen (前者效率更高, 因为isset是语言结构, strlen是函数)
<?php
$name = 'wyx';
if (strlen($name) <= 3) {
    echo 'too short';
}
if (!isset($name[3])) {
    echo 'too short';
}
# 如何区分语言结构和函数(通过可变函数来区分, 因为可变函数不能用于例如 echo，print，unset()，isset()，empty()，include，require 以及类似的语言结构)
<?php

// 正常执行
$m = 'trim';
echo $m(' hello ');

// 报错
$m = 'echo';
$m trim(' hello ');

3. 用strtr代替str_replace(由于底层实现原理不同, strtr函数效率是str_replace的4倍)
<?php

// strtr只遍历一次字符串, str_replace遍历多次字符串
$str = 'hello world';
echo strtr($str, ['hello' => 'world', 'world' => 'hello']); // world hello
echo str_replace(['hello', 'world'], ['world', 'hello'], $str); // hello hello

4. 用yield实现协程
```

