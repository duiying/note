<?php

/**
 * 测试array_merge()函数
 */

function myPrint($arr) {
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
	echo PHP_EOL;
}

function main() {
	$arr1 = [1 => 2, 4, 'color' => 'red'];
	$arr2 = ['a', 'b', 'color' => ['green'], 'shape' => 'circle', 4];
	myPrint($arr1);
	myPrint($arr2);

	// array_merge()
	$res1 = array_merge($arr1, $arr2);
	myPrint($res1); 
}

main();

