<?php

// 定义Person类
class Person
{
	/**
	 * public mixed __call ( string $name , array $arguments )
	 * 在对象中调用一个不可访问方法时,__call() 会被调用
	 * @param string $name 要调用的方法名称
	 * @param array $arguments 一个枚举数组,包含着要传递给方法$name的参数
	 */
	public function __call($name, $arguments) {
		if ($name == 'say') {
			$argumentsCount = count($arguments);

			/**
			 * bool method_exists ( mixed $object , string $method_name )
			 * 检查类的方法是否存在
			 * @param mixed $object 对象示例或者类名
			 * @param string $method_name 方法名
			 */
			$methodName = 'f' . $argumentsCount;
			if (method_exists($this, $methodName)) {
				/**
				 * 调用回调函数,并把一个数组参数作为回调函数的参数
				 * mixed call_user_func_array ( callable $callback , array $param_arr )
				 * @param mixed $callback 被调用的回调函数
				 * @param array $param_arr 要被传入回调函数的数组
				 */
				call_user_func_array([$this, $methodName], $arguments);
			}
		} 
	}

	public function f1($param1) {
		echo 'one param: ' . $param1 . '<br>';
	}

	public function f2($param1, $param2) {
		echo 'two param: ' . $param1 . ',' . $param2 . '<br>';
	}
}

$person = new Person;
// 打印结果: one param: one
$person->say('one');
// 打印结果: two param: one,two
$person->say('one', 'two');


