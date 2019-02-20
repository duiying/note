# yield(生成器) - PHP性能优化利器

### yield介绍
- PHP5.5.0引入的功能
- 可以节省内存使用, 提高PHP性能, 适合计算大量数据和读取超大文件

### 案例: 生成指定长度数组
> 传统方案  

```
# 执行时, 程序停顿10s, 然后一次性输出foreach的结果
# $arr是一次性生成完毕, 然后返回
# 当$num是100万或更大数据时, $arr都会一次性生成然后加载到内存中, 造成内存大量占用
<?php

/**
 * 生成一个长度为$num的数组
 *
 * @param $num
 * @return array
 */
function createRange($num)
{
    $arr = [];
    for ($i = 0; $i < $num; $i++) {
        sleep(1);
        $arr[] = time();
    }
    return $arr;
}

$arr = createRange(10);

foreach ($arr as $val) {
    echo $val;
}
```

> 使用yield优化  

```
# 执行时, 程序每间隔1s输出一个$val
# $arr的生成依赖于foreach, foreach每循环一次, $arr就生成一个值
# 无论$num是100万或更大数据时, $arr都会只在内存中记录一条数据, 节约大量的内存
<?php

/**
 * 生成一个长度为$num的数组
 *
 * @param $num
 * @return array
 */
function createRange($num)
{
    for ($i = 0; $i < $num; $i++) {
        sleep(1);
        yield time();
    }
}

$arr = createRange(10);

foreach ($arr as $val) {
    echo $val;
}
```