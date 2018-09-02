<?php

// 定义Person类
class Person
{
	protected function say($person) {
		echo 'Person: ' . $person . PHP_EOL;
	}
}

// 定义Man类
class Man extends Person
{
	// override
	public function say($man) {
		// 访问父类中的方法
		parent::say($man);
		echo 'Man: ' . $man . PHP_EOL;
	} 
}

$man = new Man();
// 打印结果: 
// Person: man
// Man: man
$man->say('man');

