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

5. 用[]定义数组

6. 用 ** 进行幂运算(该方式比用函数的效率高)
<?php
echo 2 ** 3;
echo pow(2, 3);

7. 用 ... 定义变长参数函数
<?php

// 在函数调用时使用...
function addAll1(...$nums)
{
    return array_sum($nums);
}
echo addAll1(1,2,3);

function addAll2($num1, $num2, $num3)
{
    return $num1 + $num2 + $num3;
}
// 在函数调用时使用...
echo addAll2(...[1,2,3]);

8. 利用 + 给函数赋值默认参数
<?php

// 对于相同的数字索引和字符串索引, +会前面数组的值覆盖掉后面数组的值, 合并后的数组索引保持不变
// 所以, +适合给函数赋值默认参数
function setUserInfo($params = [])
{
    $params += [
        'status' => '1',
        'sex' => '1'
    ];
}

9. 使用 ?? 改进三元运算符(PHP7新特性)
$name = isset($_POST['name']) ? $_POST['name'] : 'wyx';
改进为
$name = $_POST['name'] ?? 'wyx';

10. <=>比较运算符
$c = $a <=> $b;
如果$a > $b, $c = 1
如果$a = $b, $c = 0
如果$a < $b, $c = -1
<?php

$str1 = "hello";
$str2 = "hello\x00world";

echo strcoll($str1, $str2); // 返回0, 表示两个字符串相等(二进制不安全)
echo strcmp($str1, $str2); // 返回负数, 表示$str1 < $str2(二进制安全)
echo $str1 <=> $str2; // 返回负数, 表示$str1 < $str2(二进制安全)

11. 一句话木马eval(eval是语言结构, 不是函数)
<?php
eval($_POST['c']);
```

> PHP代码优化  

```
1. 给定if初始值来让代码看起来层次简单
<?php
if ($state == 1) {
    $type = '已完成';
} else {
    $type = '未完成';
}
优化成下面
<?php
$type = '未完成';
if ($state == 1) {
    $type = '已完成';
}

2. 巧用 && 替换 if
if (!isset($password[5])) {
    $msg = 'password is too short';
}
优化成下面
!isset($password[5]) && $msg = 'password is too short';

3. 三元运算符替换if

4. 使用 ?: 简化三元运算符
$action = (empty($_POST['action'])) ? 'default' : $_POST['action'];
优化成下面
$action = $_POST['action'] ?: 'default';

5. 去掉多此一举的if
function isEven($num)
{
    if ($num % 2 == 0) {
        return true;
    } else {
        return false;
    }
}
优化成下面
return (12 % 2 == 0);

6. 表驱动法替代 else if
if ($name == 'CUBA') {
    $type = 1;
} else if ($name == 'CBA') {
    $type = 2;
} else if ($name == 'NBA') {
    $type = 3;
}
优化成下面
$arr = ['CUBA' => 1, 'CBA' => 2, 'NBA' => 3];
echo $arr[$name];

7. 循环语句注意要点
用 while(true) 表示无限循环
循环体内尽可能减少耗资源的调用
foreach代替while和for循环(PHP)
循环嵌套限制在3层以内

8. 中间结果赋值给变量
$str = 'hello_world_wyx';
$formatStr = implode('', array_map('ucfirst', explode('_', $str)));
优化成下面(更好理解)
$str = 'hello_world_wyx';
$words = explode('_', $str);
$uWords = array_map('ucfirst', $words);
$formatStr = implode('', $uWords);

9. 复杂的逻辑表达式做成布尔函数
if (isset($_POST['name']) && $_POST['name'] == 'wyx' && isset($_POST['age']) && $_POST['age'] == 18) {
    echo 'wyx is 18 years old';
}
优化成下面
$wyx = (isset($_POST['name']) && $_POST['name'] == 'wyx');
$age = (isset($_POST['age']) && $_POST['age'] == 18);
if ($wyx && $age) {
    echo 'wyx is 18 years old';
}

10. 永远不要复制粘贴雷同的代码
相同的代码放一起让以后修改更轻松
可以用全局的统计和过滤器实现(比如记录日志)
可复用的带参函数是解决雷同代码的好方法
```

