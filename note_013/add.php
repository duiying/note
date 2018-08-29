<?php

/**
 * 测试用+来合并数组
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

	// +
	$res1 = $arr1 + $arr2;
	myPrint($res1); 
}

main();

