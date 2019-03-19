# 面试
通过面试题对一些看似简单的知识点进行尽可能的挖掘,弥补自己的不足

### 框架相关


### 基础算法
- [二分查找](二分查找.md)

### 网络协议
- [TCP三次握手和四次挥手](TCP三次握手和四次挥手.md)

### Redis相关
Redis支持的数据类型有哪些
***
```
redis支持的键值的数据类型有五种
    字符串类型String
    散列类型Hash
    列表类型List
    集合类型Set
    有序集合类型Sorted Set
```
### Git相关
Git分支管理策略
***
```

```
### PHP基础相关
写出下面语句的执行结果
***
```
<?php

echo "hello, \nhello" . PHP_EOL; // hello 换行 hello
echo 'hello, \nhello' . PHP_EOL; // hello, \nhello

echo "\101\102" . PHP_EOL; // AB
echo "\x41\x42" . PHP_EOL; // AB
echo '\101\102' . PHP_EOL; // \101\102
echo '\x41\x42' . PHP_EOL; // \x41\x42

echo "\\ \"" . PHP_EOL; // \ "
echo '\\ \'' . PHP_EOL; // \ '
```
```
考察单引号和双引号的区别
双引号能解析变量, 单引号不能解析变量
双引号能转义字符, 单引号不能转义字符, 但能解析\\ \'
\[0-7]{1,3} 如果符合该正则, 双引号会以八进制方式进行解析出一个字符, 单引号则不作处理
\x[0-9A-Fa-f]{1,2} 如果符合该正则, 双引号会以十六进制方式解析出一个字符, 单引号则不作处理
```

echo,print,print_r的区别
***
```
echo,print是语言结构,可以加括号也可以不加,比如 echo 1; 或者 echo(1); 都是可以的
echo和print可以打印四种标量(布尔型/字符串/整型/浮点型)
echo可以一次打印多个字符串,print一次只能打印一个,但是echo打印多个字符串的时候不能加括号
print_r是函数,可以打印四种标量和两种复合数据类型(数组和对象)
```

写一个函数,能够遍历一个文件夹下的所有文件和子文件夹
***
```
准备目录结构
--|test
--|--|core
--|--|--|core.txt
--|--|--|Lunule
--|--|--|--|Lunule.php
--|--|vender
--|--|1.txt
--|--|2.txt

* Example
<?php
 
function myScandir($dir) {  
     $arr = [];  
     if ( $handle = opendir($dir) ) {  
        while ( ($file = readdir($handle)) !== false ) { 
        	// 排除 . 和 .. 
            if ( $file != ".." && $file != "." ) {  
                if ( is_dir($dir . "/" . $file) ) {  
                    $arr[$file] = myScandir($dir . "/" . $file);   
                } else {  
                    $arr[] = $file;  
                }  
            }  
        }  
    	closedir($handle);  
    	return $arr;  
    }  
}

$arr = myScandir("./test");
echo '<pre>';
print_r($arr);
echo '</pre>';

* 打印结果
Array
(
    [0] => 1.txt
    [1] => 2.txt
    [core] => Array
        (
            [0] => core.txt
            [Lunule] => Array
                (
                    [0] => Lunule.php
                )

        )

    [vender] => Array
        (
        )

)
```
函数说明
```
opendir -- 打开目录句柄
说明
resource opendir(string path)
返回一个目录句柄,可以在之后用在closedir(),readdir()和rewinddir()
如果path不是一个合法的目录或者因为权限限制或文件系统错误而不能打开目录，
opendir()返回FALSE并产生一个E_WARNING级别的PHP错误信息,
可以在opendir()前面加上@符号来抑制错误信息的输出

readdir -- 从目录句柄中读取条目
说明
string readdir(resource dir_handle)
返回目录中下一个文件的文件名,文件名以在文件系统中的排序返回
明确地测试返回值是否全等于FALSE,否则任何目录项的名称值为FALSE的都会导致循环停止(例如一个目录名为'0')

is_dir -- 判断给定文件名是否是一个目录
说明
bool is_dir(string filename)
如果文件名存在并且为目录则返回TRUE
```

PHP中自动加载(autoload)功能包含哪几种,实现方法是什么
***
```
首先说一下为什么需要自动加载
如果没有自动加载,我们要使用某个类,比如include或require该文件之后才能使用
每次使用一个类,都要写一条include或require,造成代码冗余

要实现自动加载,有两种方法,__autoload()和spl_autoload_register()

二者的区别
__autoload()只能定义一次
spl_autoload_register()可定义多个,可以有效地创建一个队列的自动装载函数并按照顺序依次执行
```
准备工作
```
* ./Library/Code.php
<?php
/**
 * 模拟验证码类
 */
namespace Library;

class Code
{
	public function __construct() {
		echo 'Code Class init successfully' . '<br>'; 
	}
}

* ./Library/DB.php
<?php
/**
 * 模拟DB类
 */
namespace Library;

class DB
{
	public function __construct() {
		echo 'DB Class init successfully' . '<br>'; 
	}
}
```
__autoload()
```
* ./index.php
<?php

define('BASEDIR', __DIR__);

/**
 * __autoload(String $class)
 * 尝试加载未定义的类
 * @param String $class 待加载的类名(当使用命名空间时,包含命名空间部分一起作为参数)
 */
function __autoload($class) {
	// str_replace() 一定要将反斜线转义,否则报错
	$file = BASEDIR . '/' . str_replace('\\', '/', $class) . '.php';
	
	/**
	 * file_exists(path)
	 * 检查文件或者目录是否存在
	 * @param path 要检查的路径
	 * 如果指定的文件或目录存在则返回true,否则返回false
	 */
	if (file_exists($file)) {
		require_once $file;
	} else {
		// exit() 函数输出一条消息,并退出当前脚本,exit()函数和die()函数互为别名
		exit("Can't find class " . $class);
	}
}

// Code Class init successfully
$codeObj = new Library\Code();
// DB Class init successfully
$dbObj = new Library\DB();
// Can't find class Library\NotExistClass
$dbObj = new Library\NotExistClass();
```
spl_autoload_register()
```
* ./Core/Loader.php
<?php
/**
 * 自动载入类
 */
namespace Core;

class Loader
{
	// 自定义__autoload()函数
	public static function myAutoload($class) {
		$file = BASEDIR . '/' . str_replace('\\', '/', $class) . '.php';
		
		if (file_exists($file)) {
			require_once $file;
		} else {
			exit("Can't find class " . $class);
		}
	}
}

* ./index.php
<?php

define('BASEDIR', __DIR__);

include BASEDIR . '/Core/Loader.php';

// spl_autoload_register(array('class_name', 'method_name'))
// spl_autoload_register(['Core\Loader', 'myAutoload']);

// spl_autoload_register('func_name')
spl_autoload_register('Core\Loader::myAutoload');

// Code Class init successfully
$codeObj = new Library\Code();
// DB Class init successfully
$dbObj = new Library\DB();
// Can't find class Library\NotExistClass
$dbObj = new Library\NotExistClass();
```


描述一下常见的关于读取文件内容的PHP函数,及各自的特点
***
介绍以下三个函数
```
fopen()
file_get_contents()
file()

fopen()作用是打开一个文件,返回的是文件指针,它不能直接输出文件内容,要配合fget()一类的函数来从文件指针中读取文件内容
文件使用完之后需要通过fclose()函数来关闭该指针指向的文件

file_get_contents()是将整个文件的内容读取到一个字符串中

file()函数和file_get_contents()函数类似,不同的是file()函数读取文件内容并返回一个数组
该数组每个单元都是文件中相应的一行,包括换行符在内
```
准备一个文件content.txt,文件内容为:
```
hello

world!
```
(1) fopen()
```
<?php

// fopen
function fopenTest() {
	$file = 'content.txt';

	// file_exists(path) 函数检查文件或目录是否存在,存在则返回TRUE,否则返回FALSE
	if (file_exists($file)) {
		// 只读方式打开,文件指针指向文件头
		$fp = fopen($file, 'r');
		// feof() 函数检测是否已到达文件末尾
		while (!feof($fp)) {
			//fgets() 函数从文件指针中读取一行
			$text = fgets($fp);
			echo $text;
		}

		// fclose() 函数关闭一个打开文件
		fclose($fp);
	} else {
		echo 'file not exist';
	}
}

// 调用函数
fopenTest();
```
打印结果:
```
hello

world!
```
(2) file_get_contents()
```
<?php

// file_get_contents() 函数把整个文件读入一个字符串中
function file_get_contentsTest() {
	$file = 'content.txt';

	if (file_exists($file)) {
		$text = file_get_contents($file);
		echo $text;
	} else {
		echo 'file not exist';
	}
}

// 调用函数
file_get_contentsTest();
```
打印结果:
```
hello

world!
```
(3) file()
```
<?php

// file() 函数将整个文件读入一个数组中,并发挥该数组,数组中每个单元都是文件中相应一行,包括换行符在内
function fileTest() {
	$file = 'content.txt';

	if (file_exists($file)) {
		$arr = file($file);
		print_r($arr);
	} else {
		echo 'file not exist';
	}
} 

// 调用函数
fileTest();
```
打印结果:
```
Array
(
    [0] => hello

    [1] => 

    [2] => world!
)
```

如何获取客户端IP和服务端IP
***
```
客户端IP: $_SERVER['REMOTE_ADDR']
服务端IP: $_SERVER['SERVER_ADDR']
```

include/include_once和require/require_once之间的区别
***
```
include/include_once在引入不存在文件时产生一个警告且脚本还会继续执行
require/require_once在引入不存在文件时产生一个致命错误且脚本停止执行

_once的作用是先判断是否包含该文件,如果已经包含则不再包含,这样可以避免函数重定义和变量被覆盖的问题

和echo一样,它们都属于语言结构而不是严格意义上的函数,使用的时候可以加括号也可以不加括号,比如
require '1.php';
或者
require('1.php');
```

isset和array_key_exists()之间的区别
***
```
基本区别是isset可用于数组和变量,而array_key_exists()只可用于数组
但是最主要区别是两者在某种情况下的返回值不同
    array_key_exists()检查数组中键名是否存在
    isset同时检查键名和键值,只有键名存在,键值不为NULL的情况才返回TRUE
       
* Example
<?php
/**
 * 比较isset和array_key_exists()
 */

$arr = ['key1' => 'val1', 'key2' => NULL];

// 打印结果: bool(true) bool(false)
var_dump(isset($arr['key1']), isset($arr['key2']));
// 打印结果: bool(true) bool(true)
var_dump(array_key_exists('key1', $arr), array_key_exists('key2', $arr));
```
```
拓展: 还有一个常见问题是isset和empty之间的区别是什么?
empty: 检查一个变量是否为空
若变量不存在则返回TRUE
若变量为空字符串、0、'0'、NULL、FALSE、array()、没有任何属性的对象，则返回TRUE
若变量被unset，则返回TRUE

isset：检查一个变量是否存在
若变量值为NULL或者变量被unset之后，则返回FALSE
```

PHP重写是什么?重载是什么?
***
```
方法的重写(override): 又叫方法的重载
方法的重写指的是子类重写父类中的方法
要实现重写,需要满足:
    子类中重写的方法 方法名称以及该方法的参数个数和父类中被重写的方法一样,但是并不要求参数的名称相同
    子类中重写的方法 访问权限要>=父类中的被重写方法的访问权限
```
```
Example: override.php
```
![override](https://raw.githubusercontent.com/duiying/note/master/img/override.png)
```
方法的重载(overload): 存在多个同名方法,方法的参数个数 or 参数类型不同,当传入不同的参数时,调用不同的方法
PHP不像java,在PHP中不允许多个同名方法,但是可以用__call魔术方法来实现重载
```
```
Example: overload.php
```
![overload](https://raw.githubusercontent.com/duiying/note/master/img/overload.png)

说一下值传递和引用传递的区别,数组和对象是值传递还是引用传递
***
```
值传递: 函数范围内对值的改变在函数外部不会起到作用
引用传递: 函数范围内对值的改变在函数外部也会起到作用
值传递时PHP会复制值,消耗资源;引用传递时传递的是地址,资源消耗小;

数组默认是值传递,可以用&符号做到引用传递
对象是引用传递
NULL是值传递,可以用&符号做到引用传递
```
```
Example: transmit.php
```
![transmit](https://raw.githubusercontent.com/duiying/note/master/img/transmit.png)
```
深入理解对象的传递方式
```
```
Example: objectTransmit.php
```
![objectTransmit](https://raw.githubusercontent.com/duiying/note/master/img/objectTransmit.png)

### PHP函数相关
PHP中字符串截取的函数有哪些?
***
```
substr
mb_substr
使用mb_substr要现在php.ini总开启extension=php_mbstring.dll
二者不同在于substr按照字节截取,mb_substr指定编码之后,可以按照字符截取
```
```
<?php
/**
 * 字符串截取函数
 */

// string substr ( string $string , int $start [, int $length ] )

$str = 'abcdefg';
// cdefg 不指定length默认到字符串的结尾
echo substr($str, 2);
// cde
echo substr($str, 2, 3);
// ab start=0表示从第一个字符开始
echo substr($str, 0, 2);
// fg start为负数表示从字符串结尾的位置开始
echo substr($str, -2);
// cdef length为负数表示到字符串结尾的位置
echo substr($str, 2, -1);
// ef
echo substr($str, -3, -1);

// string mb_substr ( string $str , int $start [, int $length = NULL [, string $encoding = mb_internal_encoding() ]] )
// $encoding：$encoding 参数为字符编码,如果省略,则使用内部字符编码
$str = '今天天气不错hello';
// 今
echo substr($str, 0, 3);
// 今
echo mb_substr($str, 0, 3);
// 今天天气不错h
echo mb_substr($str, 0, 7, 'utf-8');
```

在PHP中error_reporting()这个函数有什么作用?
***
```
error_reporting的作用是设置错误报告级别
可以通过两种方式来设置错误级别,一种是在php.ini文件中修改,另外一种是通过error_reporting函数设置
error_reporting函数只在当前脚本文件生效

error_reporting(report_level)
report_level常见的值有
E_ALL 所有可能出现的错误
E_ERROR 运行时致命错误,程序停止执行
E_WARNING 非致命的警告错误,程序不会停止执行
E_PARSE 编译时解析错误
E_NOTICE 通知类错误

error_reporting(E_ALL & ~E_NOTICE);//报告所有错误但是除了E_NOTICE级别的错误
error_reporting(E_ALL);//报告所有错误
error_reporting(E_ERROR | E_WARNING | E_PARSE);//只报告E_ERROR、E_WARNING、E_NOTICE三种错误
```

用array_merge()函数合并数组的时候,遇到索引相同的情况会怎么处理?还有哪些合并数组的方式,它们之间有什么不同?
***
```
对于array_merge()函数
如果是相同的数字索引,array_merge()会重建索引,新的索引从0开始
如果是相同的字符串索引,array_merge()会用后面数组的值覆盖掉前面的值
其他合并数组的方式有: + 和 array_merge_recursive()函数
```
```
Example: array_merge.php
```
![array_merge](https://raw.githubusercontent.com/duiying/note/master/img/array_merge.png)
```
执行结果
```
![array_merge_res](https://raw.githubusercontent.com/duiying/note/master/img/array_merge_res.png)
```
对于用+来合并数组
对于相同的数字索引和字符串索引,+会前面数组的值覆盖掉后面数组的值,合并后的数组索引保持不变
```
```
Example: add.php
```
![add](https://raw.githubusercontent.com/duiying/note/master/img/add.png)
```
执行结果
```
![add_res](https://raw.githubusercontent.com/duiying/note/master/img/add_res.png)
```
对于用array_merge_recursive()函数合并数组
如果是相同的数字索引,处理方式和array_merge相同,都是重建索引,新的索引从0开始
如果是相同的字符串索引,会把相同的索引放到一个数组里面
```
```
Example: array_merge_recursive.php
```
![array_merge_recursive](https://raw.githubusercontent.com/duiying/note/master/img/array_merge_recursive.png)
```
执行结果
```
![array_merge_recursive_res](https://raw.githubusercontent.com/duiying/note/master/img/array_merge_recursive_res.png)
### 面向对象相关

self和$this的区别
***
```
最主要的区别是self代表的是类,$this代表的是对象
静态成员是给类调用的,不是给$this调用的

类外部
访问const(常量)或static(静态)修饰的成员,必须使用 :: 操作符, 除此之外的成员必须使用操作符 ->

类内部
访问const(常量)或static(静态)修饰的成员,必须使用 self:: 操作符, 除此之外的成员必须使用操作符 $this
```
```
* Example
<?php

class Animal
{
	public $name = 'Animal';
	public static $age = 10;

	public function myPrint()
	{
		echo $this->name;	// Animal
		echo self::$age;	// 10
		echo $this::$age;	// 10 特殊用法: 允许使用$this::$age 替代 self::$age
	}
}

// 类的外部访问
// 10
echo Animal::$age;
$animal = new Animal();
// Animal
echo $animal->name;

// 类的内部访问
// Animal 10 10 
$animal->myPrint();
```

权限修饰符有哪些?
***
```
权限修饰符可以用在类的属性和方法(属性和方法统称为类的成员),用来控制类的成员的访问权限
权限修饰符一共有三种
public (公共的) : 任何地方都可以访问
protected (受保护的) : 本类内部和子类内部可以访问,类的外部不可以访问
private (私有的) : 只能本类内部可以访问
```
```
* Example
<?php

class Animal
{
	public $name = 'Animal';
	protected $age = 0;
	private $sex = '';
}

class Dog extends Animal
{
	function shout()
	{
		echo $this->age;
	}
}

$animal = new Animal();
// Animal
echo $animal->name;
// 类外部不能访问
// echo $animal->age;
// 类外部不能访问
// echo $animal->sex;

$dog = new Dog();
// Animal
echo $dog->name;
// 类外部不能访问
// echo $dog->age;
// 私有属性不能继承
// echo $dog->sex;
// 0
$dog->shout();
```



什么是面向对象?面向对象的三大特性
***
```
面向对象是把解决的问题按照一定规则划分成一个或多个对象,然后通过调用对象的方法来解决问题
面向过程是把解决的问题划分成几个步骤,然后用函数将这些步骤一一实现,使用的时候调用函数

三大特性: 封装 继承 多态
封装: 定义类的时候将类中属性私有化,私有属性只能在本类中访问,为了让外界访问,需要提供public修饰的方法比如getXxx和setXxx
封装的好处在于隐藏实现细节,对类外部提供访问方法,提高安全性

继承: 在一个现有类的基础上去构建一个新的类,新的类称作子类,现有类被称为父类,子类会自动拥有父类所有可继承的属性和方法(public和protected)
语法结构： 
class 父类名{} 
class 子类名 extends 父类名{}
继承的好处在于提高了代码复用性
注意: 子类继承的方法和属性的访问权限不能低于父类对应方法和属性的访问权限,比如下面这样是错误的
class Animal
{
	protected $name;
}

class Dog extends Animal
{
	private $name;
}
Dog类中的name属性的访问权限只能是大于等于protected,也就是public和protected,不能低于protected

多态: 一个类被多个子类继承,如果这个类的某个方法被多个子类表现不同的功能,这种行为称为多态,即多态是同一个东西不同形态的展示
多态实现必须满足三个条件: 子类继承父类/子类重写父类方法/父类引用指向子类对象
* Example
<?php

abstract class Animal
{
	abstract function say();
}

class Dog extends Animal
{
	function say() {echo 'wangwang';}
}

class Cat extends Animal
{
	function say() {echo 'miaomiao';}
}

// PHP的类型约束只存在于函数的形参
function work(Animal $a)
{
	$a->say();
}

// 父类引用指向子类对象
work(new Dog()); // wangwang
work(new Cat()); // miaomiao
```

描述出PHP类中的常见魔术方法(最少5个)
***
```
构造函数和析构函数
__construct() 在每次创建新对象时先调用此方法
__destruct() 在某个对象的所有引用都被删除或者当对象被显式销毁时执行

在给不可访问属性赋值时,__set() 会被调用,__set() 方法用于设置私有属性值
读取不可访问属性的值时,__get() 会被调用,__get() 方法用于获取私有属性值
当对不可访问属性调用 isset()时,__isset() 会被调用,__isset() 方法用于检测私有属性值是否被设定
当对不可访问属性调用 unset() 时,__unset() 会被调用,__unset() 方法用于删除私有属性

在对象中调用一个不可访问方法时,__call()会被调用
__toString() 方法用于一个对象被当成字符串时应怎样回应,此方法必须返回一个字符串,否则将发出一条 E_RECOVERABLE_ERROR 级别的致命错误
__clone() 当通过关键字clone克隆一个对象时,新创建的对象(即克隆生成的对象)中的__clone()方法会被调用
```
__construct()
```
如果子类中定义了构造函数则不会隐式调用其父类的构造函数
要执行父类的构造函数,需要在子类的构造函数中调用 parent::__construct()
如果子类没有定义构造函数则会如同一个普通的类方法一样从父类继承(假如没有被定义为 private 的话)

* Example
<?php
/**
 * __construct()
 */

class BaseClass {
   function __construct() {
       print "In BaseClass constructor\n";
   }
}

class SubClass extends BaseClass {
   function __construct() {
       parent::__construct();
       print "In SubClass constructor\n";
   }
}

class OtherSubClass extends BaseClass {
    // inherits BaseClass's constructor
}

// In BaseClass constructor
$obj = new BaseClass();

// In BaseClass constructor
// In SubClass constructor
$obj = new SubClass();

// In BaseClass constructor
$obj = new OtherSubClass();
```
__destruct()
```
析构函数和构造函数类似
如果子类中定义了析构函数则不会隐式调用其父类的析构函数
要执行父类的析构函数,需要在子类的析构函数中调用 parent::__destruct()
如果子类没有定义析构函数则会如同一个普通的类方法一样从父类继承(假如没有被定义为 private 的话)

* Example
<?php
/**
 * __destruct()
 */

class BaseClass {
   function __destruct() {
       print "In BaseClass destructor\n";
   }
}

class SubClass extends BaseClass {
   function __destruct() {
       parent::__destruct();
       print "In SubClass destructor\n";
   }
}

class OtherSubClass extends BaseClass {
    // inherits BaseClass's destructor
}

// In BaseClass destructor
$obj = new BaseClass();

// In BaseClass destructor
// In SubClass destructor
$obj = new SubClass();

// In BaseClass destructor
$obj = new OtherSubClass();
```
__get()
```
* Example
<?php
/**
 * __get()
 */

class Info
{
	private $name;

	public function __construct() {
		$this->name = 'wyx';
	}

	public function __get($key) {
		if (isset($this->$key)) return $this->$key;
		return 'not exist'; 
	}
}

$obj = new Info();
// wyx
echo $obj->name;
// not exist
echo $obj->noexist;
```
__set()
```
* Example
<?php
/**
 * __set()
 */

class Info
{
	private $name;

	public function __construct() {
		$this->name = 'wyx';
	}

	public function __get($key) {
		if (isset($this->$key)) return $this->$key;
		return 'not exist'; 
	}

	public function __set($key, $val) {
		$this->$key = $val;
	}
}

$obj = new Info();
$obj->name = 'akun';
// akun
echo $obj->name;
```
__isset()
```
* Example
<?php
/**
 * __isset()
 */

class Info
{
	private $name;

	public function __construct() {
		$this->name = 'wyx';
	}

	public function __isset($key) {
		return isset($this->$key);  
	}
}

$obj = new Info();
// bool(true)
var_dump(isset($obj->name));
// bool(false)
var_dump(isset($obj->noexist));
```
__unset()
```
* Example
<?php
/**
 * __isset()
 */

class Info
{
	private $name;

	public function __construct() {
		$this->name = 'wyx';
	}

	public function __get($key) {
		if (isset($this->$key)) return $this->$key;
		return 'not exist'; 
	}

	public function __unset($key) {
		unset($this->$key); 
	}
}

$obj = new Info();
// wyx
echo $obj->name;
unset($obj->name);
// not exist
echo $obj->name;
```
__call()
```
* Example
<?php
/**
 * __call()
 */

class Info
{
	private $name;

	public function __construct() {
		$this->name = 'wyx';
	}

	/**
	 * public mixed __call ( string $name , array $arguments )
	 * @param string $name 要调用的方法名称
	 * @param array $arguments 一个枚举数组,包含着要传递给方法 $name 的参数
	 */
	public function __call($name, $arguments) {
		echo '未定义方法' . '<br>';
		print_r($arguments);
	}
}

$obj = new Info();
// 未定义方法
// Array ( [0] => wyx [1] => 18 ) 
$obj->nonMethod('wyx', 18);
```
__toString()()
```
* Example
<?php
/**
 * __toString()
 */

class Info
{
	private $name;

	public function __construct() {
		$this->name = 'wyx';
	}

	public function __toString() {
		return 'Object: name = ' . $this->name;
	}
}

$obj = new Info();
// Object: name = wyx
echo $obj;
```
__clone()
```
语法格式: $新克隆对象名称 = clone $原对象名称;
对象的__clone方法不能被直接调用,只有通过关键字clone克隆一个对象时才会调用新克隆对象的__clone方法
当克隆一个对象时,如果对象的__clone方法不存在,会调用默认的__clone方法,复制对象的所有属性
如果__clone方法存在,存在的__clone方法会覆盖默认的__clone方法,通常在__clone方法中覆盖那些需要更改的属性

* Example
<?php
/**
 * __clone()
 */

class Info
{
	private $name;
	private $age;

	public function __construct() {
		$this->name = 'wyx';
		$this->age = 18;
	}

	public function __clone() {
		// $this指向的是新克隆的对象
		$this->name = 'clone wyx';
	}
}

$objA = new Info();
// object(Info)#1 (2) { ["name":"Info":private]=> string(3) "wyx" ["age":"Info":private]=> int(18) }
var_dump($objA);

// $objB和$objA指向的是同一个对象
$objB = $objA;
// object(Info)#1 (2) { ["name":"Info":private]=> string(3) "wyx" ["age":"Info":private]=> int(18) } 
var_dump($objB);

// $objClone和$objA指向的是完全不同的两个对象
$objClone = clone $objA;
// object(Info)#2 (2) { ["name":"Info":private]=> string(9) "clone wyx" ["age":"Info":private]=> int(18) }  
var_dump($objClone);
```
__clone()浅拷贝
```
什么是浅拷贝
对象属性值如果是非对象,新克隆的对象与原对象是完全独立的两个对象
对象属性值如果是对象,新克隆的对象的属性值和原对象的属性值指向的是同一个对象

* Example
<?php
/**
 * __clone()深拷贝和浅拷贝
 */

class Info
{
	private $name;
	private $newObj;

	public function __construct() {
		$this->name = 'wyx';
		$this->newObj = new NewClass();
	}

	public function __clone() {
		// 浅拷贝,当对象属性值是对象时,新克隆对象属性值和原对象指向的是同一个对象
		// 当新克隆对象的属性值发生变化时,原对象属性值也会发生变化
		$this->newObj->newAttr = 'change attr';
	}
}

class NewClass
{
	public $newAttr = 'new attr';
}

$objA = new Info();
// object(Info)#1 (2) { ["name":"Info":private]=> string(3) "wyx" ["newObj":"Info":private]=> object(NewClass)#2 (1) { ["newAttr"]=> string(8) "new attr" } } 
var_dump($objA);

$objClone = clone $objA;
// object(Info)#3 (2) { ["name":"Info":private]=> string(3) "wyx" ["newObj":"Info":private]=> object(NewClass)#2 (1) { ["newAttr"]=> string(11) "change attr" } }
var_dump($objClone);

// object(Info)#1 (2) { ["name":"Info":private]=> string(3) "wyx" ["newObj":"Info":private]=> object(NewClass)#2 (1) { ["newAttr"]=> string(11) "change attr" } } 
var_dump($objA);
```
__clone()深拷贝
```
相对于浅拷贝,深拷贝要求即使对象属性值是对象时,新克隆对象的属性值和原对象的属性值也不能是同一个对象

一种解决方法是使用__clone()方法
public function __clone() {
	$this->newObj = new NewClass();
}
这种解决方法弊端在于如果为对象的属性值太多的话,造成代码冗余

另一种解决方法是序列化与反序列化
将
$objClone = clone $objA;
替换为下面的代码
$objClone = unserialize(serialize($objA));
```


### 设计模式相关
实现单例模式
***
```
理解单例模式: 单例模式即一个类只能实例化一次,当其再次实例化时,返回第一次实例化的对象,可以节省资源
单例模式常见的应用是数据库类的实例化

实现思路: 三私一公
私有的构造方法 (限制只能类内部实例化,防止类外部实例化)
私有的克隆方法 (防止通过克隆生成对象)
私有的静态属性 (保存类的实例)
公有的静态方法 (提供实例,实例化前先判断该类是否已经被实例化,若是则返回实例化对象,若不是则实例化对象并将该对象保存在类的静态属性中)

* Example
<?php
/**
 * 单例模式
 */

class Single
{
	private static $instance;
	
	private function __clone() {}
	
	private function __construct() {}
	
	public static function getInstance() {
		if (self::$instance === NULL) self::$instance = new self();
		return self::$instance;
	} 
}
```

### HTTP相关
表单中get和post提交方法的区别
***
```
get通过url传递参数,传递参数数据量有限,由于参数暴露在url所以安全性较低
post通过请求体传递参数,传递参数没有大小限制,安全性较高
```

cookie和session了解吗?有什么区别?session保存在服务器哪个目录?禁用cookie怎么保存会话信息
***
```
因为HTTP协议是无状态的,一个用户在访问不同页面时,要识别出这是同一个用户,就要用到会话技术,会话技术包括cookie和session
cookie保存在客户端,session保存在服务端,保存目录由php.ini里面session.save_path参数确定

session的原理
前提是开启了session,第一次访问页面时,服务端生成一个不重复的sessionid(当前会话id)以及命名为sess_xxx的session文件,保存在php.ini文件中指定的目录
文件中保存着存储的session信息,xxx即为sessionid,sessionid可以通过session_id()函数来获取
同时返回响应头(Response Header)Set-Cookie:PHPSESSID=xxxxxxx
客户端接受到Set-Cookie响应头,将sessionid写入cookie,cookie的key为PHPSESSID,value为sessionid
比如PHPSESSID=jlis2mcmv6d5hejkemom77ibm3

当第二次访问页面时,客户端会把cookie放在请求头(Request Header)中,服务端识别PHPSESSID这个cookie
然后根据这个cookie获取当前会话ID(sessionid),从而找到对应的session文件,再从session文件中读取信息

cookie和session的区别
cookie保存在客户端,session依赖于cookie保存在服务端,如果禁用cookie则session也不可用
cookie大小有限制,session大小限制取决于服务器
cookie信息保存在客户端,不安全,session保存在服务端,安全
session保存在服务端会消耗服务端资源,如果考虑到性能可以保存到cookie

禁用cookie
禁用cookie后,服务器每次session_start()的时候都会创建一个单独的session文件,后果就是无法让多个页面共享同一份session,也就是会话失效
解决方法:
首先判断是否有PHPSESSID参数是否存在,如果存在就使用这个参数来获得sessionid,如果没有就创建一个新的sessionid;
然后在每个链接上都加上PHPSESSID=sessionid
Example: x.php
<?php

if(isset($_GET['PHPSESSID'])){
    session_id($_GET['PHPSESSID']);
}

session_start();
$sid = session_id();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>页面</title>
</head>
<body>
<a href="xxx.php?PHPSESSID=<?php echo $sid;?>">链接</a>
</body>
</html>
```


### Linux相关
- [文本处理工具sed](文本处理工具sed.md)

将/data目录链接到当前用户的家目录
***
硬链接不支持目录,只能使用软链接
```
[root@VM_12_22_centos data]# ln -s /data ~
[root@VM_12_22_centos ~]# ll
lrwxrwxrwx 1 root root 5 Sep 23 11:01 data -> /data

或者指定目标目录名
[root@VM_12_22_centos data]# ln -s /data ~/mydata
[root@VM_12_22_centos ~]# ll
lrwxrwxrwx 1 root root 6 Sep 23 10:36 mydata -> /data
```
理解软链接和硬链接
```
linux链接分为两种: 硬链接(Hard Link) 和 符号链接(Symbolic Link)又称为软链接
软链接: ln -s 源文件 目标文件
硬链接: ln 源文件 目标文件 

索引节点(inode)又称i节点: linux系统为每个文件或者目录分配索引节点的编号inode,通过inode找到文件的具体物理存储位置,简单理解为指针
硬链接: 硬链接通过索引节点进行连接,多个文件名指向同一索引节点,同一索引节点指向的是同一文件
软链接: 本质是新建一个文件,保存原文件的路径名,相当于快捷方式,因此新建文件和原文件的inode是不同的

软链接和硬链接的区别
硬链接不支持为目录创建硬链接,但软链接可以

[root@VM_12_22_centos data]# touch 1.txt
[root@VM_12_22_centos data]# ln 1.txt ying.txt
[root@VM_12_22_centos data]# ln -s 1.txt ruan.txt
[root@VM_12_22_centos data]# ll
-rw-r--r-- 2 root root    2 Sep 23 10:42 1.txt
lrwxrwxrwx 1 root root    5 Sep 23 10:44 ruan.txt -> 1.txt
-rw-r--r-- 2 root root    2 Sep 23 10:42 ying.txt

由此可见,硬链接目标文件和原文件创建时间相同,硬链接相当于给目标文件创建一个副本,硬链接的作用是允许一个文件拥有多个有效路径名,防止误删文件
软链接目标文件和原文件创建时间不同,目标文件相当于一个快捷方式

硬链接和软链接中对目标文件的修改都会在原文件中保持同步
删除原文件,硬链接目标文件不受影响;删除硬链接目标文件,原文件不受影响
删除原文件,软链接目标文件成为断链(没有链接对象);删除软链接目标文件,原文件不受影响
```

如何找出文本中含有'abc'的行,如何统计共有多少行
***
```
[root@VM_12_22_centos /]# cat 1.txt
linux fdjalfd
fjdlsfjdlal linux linux fdlaj
fdjaljf
fdjal
fdjal linuxlinuxfjdla
fjadl
[root@VM_12_22_centos /]# cat 1.txt | grep 'linux'
linux fdjalfd
fjdlsfjdlal linux linux fdlaj
fdjal linuxlinuxfjdla
[root@VM_12_22_centos /]# cat 1.txt | grep 'linux' | wc -l
3
```

如何显示文本文件头/尾部的M行
***
```
head filename 查看文本文件头部10行
head -n 20 filename 查看文本文件头部20行

tail filename 查看文本文件尾部10行
tail -n 20 filename 查看文本文件尾部20行

wc -l filename 查看文本文件行数
```

free命令
***
```
* Example
[root@localhost ~]# free 
              total        used        free      shared  buff/cache   available
Mem:         999936      346300      194540       21800      459096      421092
Swap:       2097148        1568     2095580
[root@localhost ~]# free -m
              total        used        free      shared  buff/cache   available
Mem:            976         338         190          21         448         411
Swap:          2047           1        2046
[root@localhost ~]# free -h
              total        used        free      shared  buff/cache   available
Mem:           976M        338M        189M         21M        448M        411M
Swap:          2.0G        1.5M        2.0G

* 命令说明
free命令是用来查看内存占用情况,-m表示以M为单位显示,-h表示以方便阅读的方式显示
total 内存总数
used 已经使用的内存数
free 空闲的内存数
shared 不必关心
buff/cache 缓存内存数
available 可用内存数
说明:buff/cache分为used和free两部分,free部分是可以回收的内存
total = used + free + buff/cache
available = free + buff/cache中可以回收的内存
```
top命令主要看什么参数
***
```
* Example
[root@localhost ~]# top
top - 11:12:18 up 4 days, 11:02,  3 users,  load average: 0.00, 0.01, 0.05
Tasks: 108 total,   2 running, 106 sleeping,   0 stopped,   0 zombie
%Cpu(s):  0.0 us,  0.3 sy,  0.0 ni, 99.7 id,  0.0 wa,  0.0 hi,  0.0 si,  0.0 st
KiB Mem :   999936 total,   194136 free,   346648 used,   459152 buff/cache
KiB Swap:  2097148 total,  2095580 free,     1568 used.   420808 avail Mem 

   PID USER      PR  NI    VIRT    RES    SHR S %CPU %MEM     TIME+ COMMAND                                          
   903 root      20   0  553164  14764   4128 S  0.3  1.5   1:39.11 tuned                                            
     1 root      20   0  193628   6448   3684 S  0.0  0.6   0:33.31 systemd                                          
     2 root      20   0       0      0      0 S  0.0  0.0   0:00.09 kthreadd 

* 命令说明
第一行
11:12:18 当前时间 
4 days, 11:02 系统运行时间
3 users 当前登录用户数
load average: 0.00, 0.01, 0.05 系统负载(1分钟 10分钟 15分钟),即任务队列的平均长度(单核CPU中不超过1是正常的,超过1说明负载压力大)
第二行
108 total 进程总数 2 running 运行进程数 106 sleeping 休眠进程数 0 stopped 终止进程数 0 zombie 僵尸进程数
第三行
0.0 us 用户空间占用cpu百分比
0.3 sy 内核空间占用cpu百分比
0.0 ni 用户进程空间内改变过优先级的进程占用cpu百分比
99.7 id 空闲cpu百分比
0.0 wa 等待输入输出（I/O）的cpu百分比
0.0 hi cpu处理硬件中断的时间
0.0 si cpu处理软件中断的时间 
0.0 st 用于有虚拟cpu的情况,用来指示被虚拟机偷掉的cpu时间
第四行
999936 total 内存总数
194136 free 空闲的内存数
346648 used 已经使用的内存数   
459152 buff/cache 缓存的内存数
第五行
2097148 total 总的交换空间
2095580 free 空闲的交换空间     
1568 used 已经使用的交换空间   
420808 avail Mem 可用交换空间

进程信息区统计信息区域的下方显示了各个进程的详细信息
PID 进程ID
USER 进程所有者用户名
PR 优先级
NI nice值,负值表示高优先级,正值表示低优先级   
VIRT 进程使用的虚拟内存总量   
RES 物理内存用量  
SHR 共享内存用量
S 该进程的状态;其中S代表休眠状态,D代表不可中断的休眠状态,R代表运行状态,Z代表僵尸状态,T代表停止或跟踪状态 
%CPU 进程占用的CPU时间和总时间的百分比 
%MEM 进程占用的物理内存和总内存的百分比    
TIME+ 累计CPU占用时间 
COMMAND 命令名/命令行
```
### MySQL相关
写出PHP如何连接MySQL
***
```
PHP连接MySQL常用方式有三种
MySQL扩展(PHP5.5.0起被弃用,不建议使用)
mysqli扩展
PDO扩展
```
准备SQL
```
DROP TABLE IF EXISTS `student`;
CREATE TABLE `student` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '姓名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of student
-- ----------------------------
INSERT INTO `student` VALUES ('1', '小明');
INSERT INTO `student` VALUES ('2', '小红');

```
MySQL扩展
```
<?php
/**
 * 使用MySQL扩展连接数据库
 */

/**
 * API说明
 * mysql_query(query,connection)
 * mysql_select_db(database,connection)
 * mysql_fetch_assoc(data) 从结果集中取得一行作为关联数组
 * mysql_close(link_identifier) link_identifier => MySQL 的连接标识符 关闭非持久的MySQL连接
 */
$db_host = 'localhost:3306';
$db_user = 'root';
$db_pass = '';
$db_name = 'testdb';

$conn = mysql_connect($db_host, $db_user, $db_pass) or die('unable to connect to the MySQL');
mysql_query("SET NAMES UTF8", $conn); 
$select_db = mysql_select_db($db_name, $conn) or die('cant not use db ' . $db_name);
$sql = "select * from student";

$res = mysql_query($sql, $conn) or die(mysql_error());
while ($row = mysql_fetch_assoc($res)) {
    print_r($row);
}

mysql_close($conn);
```
打印结果
```
Array
(
    [id] => 1
    [name] => 小明
)
Array
(
    [id] => 2
    [name] => 小红
)
```
MySQLi扩展
```
<?php
/**
 * 使用MySQLi扩展连接数据库
 */

$db_host = 'localhost:3306';
$db_user = 'root';
$db_pass = '';
$db_name = 'testdb';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die(mysqli_connect_error());
mysqli_query($conn, "SET NAMES UTF8"); 
$sql = "select * from student";

$res = mysqli_query($conn, $sql) or die(mysqli_error($conn));
while ($row = mysqli_fetch_array($res)) {
    print_r($row);
}

mysqli_free_result($res);
mysqli_close($conn);
```
打印结果
```
Array
(
    [0] => 1
    [id] => 1
    [1] => 小明
    [name] => 小明
)
Array
(
    [0] => 2
    [id] => 2
    [1] => 小红
    [name] => 小红
)
```

一张雇员表employee,一张部门表department,结构如下,写出建表语句
***
id|emp_name|dept_id
--|--|--
1|张三|1
2|李四|1
3|王五|2

id|dept_name
--|--
1|售前
2|客服
3|开发
```
* employee表
CREATE TABLE `employee` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `emp_name` varchar(255) NOT NULL DEFAULT '',
  `dept_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

* department表
CREATE TABLE `department` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `dept_name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```
如果要查询出如下结果,请写出sql语句  

id|dept_name|num
--|--|--
1|售前|2
2|客服|1
3|开发|0
```
SELECT
	dept.id,
	dept.dept_name,
	(
		SELECT
			count(emp.id)
		FROM
			employee emp
		WHERE
			emp.dept_id = dept.id
	) AS num
FROM
	department dept;
```

表的约束有哪些
***
```
为了防止数据表中插入错误的数据,在MySQL中,定义了一些维护数据库完整性的规则,即表的约束
* 常见约束
    PRIMARY KEY 主键约束
    FOREIGN KEY 外键约束
    NOT NULL 非空约束
    UNIQUE 唯一性约束(在MySQL中,唯一性约束和唯一索引概念不同,但是实际效果相同)
    DEFAULT 默认值约束
* Example
create table yueshu(id int primary key,
                    name varchar(20) NOT NULL UNIQUE,
                    location varchar(20) DEFAULT ''
);
* 说明
id字段有主键约束,name字段有唯一约束和非空约束,location字段有默认约束
```
MySQL存储引擎之MyIsam和Innodb的区别,如何选择
***
```
1)InnoDB支持事务;MyISAM不支持
2)MyISAM强调的是性能,查询速度比InnoDB快;InnoDB适合频繁修改以及涉及到安全性较高的应用
3)InnoDB支持外键;MyISAM不支持
4)MySQL5.5以后,默认存储引擎是InnoDB
5)InnoDB不支持全文索引;MyISAM支持全文索引
6)MyISAM仅支持表锁,并发较小;InnoDB支持行锁,并发较大
7)使用delete删除表的时候,InnoDB是逐行删除;而MyISAM是先DROP表,然后重新建表,MyISAM的效率快
8)对于select count(*) from 表名;MyISAM因为保存了表的行数可以直接取出;而InnoDB会遍历整个表来计算行数;但是对于加了WHERE条件,select count(*) from 表名 WHERE 条件;MyISAM和InnoDB都会遍历整个表来计算行号
```
MySQL中char和varchar类型的区别
***
```
char和varchar都用来表示字符串数据
varchar长度可变,比如varchar(10),如果存储'github',会占用6个字符的长度;
char长度不可变,比如char(10),如果存储''github',会占用10个字符的长度,'github'后面会跟4个空格,取数据的时候会将添加的空格trim掉
因为char的长度固定,方便数据的存储和查找,所以char的时间效率比varchar高,但是char的空间占用比varchar高

关于存储
MySQL4.0以前,char(20)和varchar(20)指的是20个字节长度,如果存放utf8汉字(3个字节)时,只能存放6个
5.0版本以上,char(20)和varchar(20)指的是20个字符长度,无论存放数字/字母/utf8汉字(3个字节),都可以存放20个

char和varchar的最大存储
char范围是0-255,可以存储255个字符
varchar是0-65535(可以存放65532个字节的数据)
为什么是65532呢,65535-1-2,减1的原因是实际存储从第二个字节开始,减2的原因是varchar头部的2个字节表示长度
实际应用中varchar长度限制的是一个行定义的长度,MySQL要求一个行的定义长度不能超过65535字节
若定义的表长度超过这个值，则提示
ERROR 1118 (42000): Row size too large. The maximum row size for the used table type, not counting BLOBs, is 65535. You have to change some columns to TEXT or BLOBs

MySQL对于varchar会有1-2个字节来保存字符长度,当字符数小于等于255时,MySQL只用1个字节来记录,因为2的8次方减1只能存到255,当字符数多于255就用2个字节

英文字符和数字占1个字节,GBK字符集每个汉字占两个字节,UTF8字符集每个汉字占三个字节
GBK 一个字符占1-2个字节,varchar最多能存 32766 个字符(65535-1-2 / 2)
UTF8 一个字符占1-3个字节,varchar最多能存 21844 个字符(65535-1-2 / 3)

举例说明
a)若一个表只有一个varchar类型,如定义为
create table t4(c varchar(N)) charset=gbk;
则此处N的最大值为(65535-1-2)/2 = 32766 个字符
减1的原因是实际存储从第二个字节开始,减2的原因是varchar头部的2个字节表示长度,除2的原因是字符编码是gbk

b)若一个表定义为
create table t4(c int, c2 char(30), c3 varchar(N)) charset=utf8;
则此处N的最大值为(65535-1-2-4-30*3)/3=21812
减1和减2与上例相同,减4的原因是int类型的c占4个字节,减30*3的原因是char(30)占用90个字节,编码是utf8 
```
tinyint占多少个字节,tinyint(1)能表示的数据范围
***
```
这个问题首先要掌握整数类型所占的字节数和能表示的数据范围,另外拓展一下,还要掌握浮点数类型和定点数类型
```
```
整型
```
数据类型|字节数|无符号取值范围|有符号取值范围
--|--|--|--|--
TINYINT|1|0-255|-128-127
SMALLINT|2|0-65535|-32768-32767
MEDIUMINT|3|0-16777215|-8388608-8388607
INT|4|0-4294967295|-2147483648-2147483647
BIGINT|8|0-18446744073709551615|-9223372036854775808-9223372036854775807
```
TINYINT(M)中M表示的含义是什么,比如TINYINT(1)
M的含义并不是允许存入的字符长度,无符号TINYINT(1)可以存入其最大值255,而且存入的数值无论是0还是255,占用的字节大小是固定的,都是1
那么这个M值到底代表什么,有什么作用?
整型的字节数已经限制了存入数值的取值范围,M值在这里不会有任何影响,比如TINYINT(1)和TINYINT(2)没有区别
如果设置zerofill(左前位置零填充),对于TINYINT(1)和TINYINT(2),如果存入1,TINYINT(1)存入的是1,而TINYINT存入的是01
也就是说,没有zerofill,M值是没用的
所以在设计字段的时候,mysql会自动分配长度:int(11)、tinyint(4)、smallint(6)、mediumint(9)、bigint(20)
就用这些默认长度就可以了,不用自己搞int(10),tinyint(1)之类的,基本没什么用而且导致表的字段类型多样化
TINYINT(M)、SMALLINT(M)、MEDIUMINT(M)、INT(M)、BIGINT(M)是同理的,没有zerofill,M值是没用的
```
```
浮点型和定点型
```
![float](https://raw.githubusercontent.com/duiying/note/master/img/float.png)
```
float数值类型用于表示单精度浮点数值,而double数值类型用于表示双精度浮点数值
float和double都是浮点型,而decimal是定点型
MySQL浮点型和定点型可以用类型名称后加(M,D)来表示,M表示该值的总共长度,D表示小数点后面的长度,M和D又称为精度和标度
如float(7,4),最大可存入-999.9999,如果尝试存入999.00009,则存入结果为999.0001

浮点型和定点型的区别是浮点型在一些情况下数据库中存放的是近似值,而定点型存放的永远都是精确值
浮点型不能做精确的计算,其计算结果不准确,只是近似,而定点型可以做精确的计算
```
```
* Example
mysql > create table float_test(id int, float_test float);
mysql > desc float_test;
+------------+---------+------+-----+---------+-------+
| Field      | Type    | Null | Key | Default | Extra |
+------------+---------+------+-----+---------+-------+
| id         | int(11) | YES  |     | NULL    |       |
| float_test | float   | YES  |     | NULL    |       |
+------------+---------+------+-----+---------+-------+
mysql > insert into float_test values(1, 123456),(2, 123.456),(3, 1234567),(4,1234.5678);
mysql > select * from float_test;
+------+------------+
| id   | float_test |
+------+------------+
|    1 |     123456 |
|    2 |    123.456 |
|    3 |     123570 |
|    4 |    1234.57 |
+------+------------+
从上面测试代码可知float默认保存6位精度(包括小数位和整数位),超过6位会被四舍五入并补入0
```
了解数据库三范式吗
***
```
1NF(第一范式)指的是表的属性(即列)具有原子性,不可再分
2NF(第二范式)指的是在第一范式的基础上,不能有部分依赖,部分依赖的前提是有组合主键,也就是非主键不能只依赖于部分主键
3NF(第三范式)指的是在第二范式的基础上,非主键字段和主键字段不能产生传递依赖
```
MySQL索引有哪些
***
```
普通索引(KEY/INDEX):基本索引
唯一索引(UNIQUE定义的索引):列值唯一,允许有空值
全文索引(FULLTEXT定义的索引):只可用于MyISAM存储引擎
主键索引(PRIMARY KEY):列值唯一,不允许有空值,一张表最多有一个主键索引
组合索引:在多个字段上创建的索引,只有查询条件使用了第一个字段时,该索引才会被使用
```
如何分析SQL查询语句的性能
***
```
使用explain语句可以查看索引使用情况以及其他查询信息,从而定位慢查询
```
描述select for update的作用?以innodb为假设
***
```

```

面向对象 抽象类和接口之间区别 设计模式了解哪些,抽象类能否定义非抽象方法  mysql数组函数尽可能的多说  说一下常用的排序算法,,冒泡/插入/快排怎么实现,时间复杂度如何 trait了解吗 composer具体的命令用过哪些  函数重载 self和$this的区别 

docker如何进入仓库 elasticsearch的query和filter 快排 10个牛奶 几个小鼠可以找到有毒的牛奶 http中有mac协议吗 二叉树 前是xxx 后是xxx 中是什么? sql书写 一个dept 一个employee 写出建表语句和查询语句 怎么关掉php-fpm进程 十六进制转成十进制 说出尽可能多地linux命令 ctrontab每隔一分钟 每隔五分钟 月尾 yii框架 laravel框架 写出时间复杂度logn php安全和性能了解吗 sql注入 redis如何防止登录过快?五次怎么设计 队列用过吗 mvc的认识 docker如何进入仓库 vim如何删除10行 进入文件末尾 如何删除一行并进入行头 
