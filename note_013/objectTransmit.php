<?php

// 定义Person类
class Person
{
	public $a = 0;
}

// $obj和$copy指向同一个对象,当$obj对象属性发生变化时,$copy对象也会发生变化
$obj = new Person();
$copy = $obj;
$obj->a = 1;
// 打印结果: int(1)
var_dump($copy->a);

// $person和$personCopy指向同一个对象,当$person指向发生变化时,$personCopy并不会发生变化,因为$person和$personCopy指向的不是同一个对象
$person = new Person();
$personCopy = $person;
$person = 1;
// 打印结果: object(Person)#2 (1) { ["a"]=> int(0) } 
var_dump($personCopy);

