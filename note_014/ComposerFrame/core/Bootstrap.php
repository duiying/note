<?php
namespace core;

class Bootstrap
{
	// 启动
	public static function run() {
		session_start();
		self::parseUrl();
	}

	// 解析URL
	public static function parseUrl() {
		if (isset($_GET['s'])) {
			$arr = explode('/', $_GET['s']);
			$class = '\web\controller\\' . ucfirst($arr[0]);
			$action = $arr[1];
		} else {
			$class = '\web\controller\Index';
			$action = 'index';
		}

		echo (new $class)->$action();
	}
}