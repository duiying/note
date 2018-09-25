<?php
namespace core;

class View
{
	// 模板文件
	protected $file;
	// 模板变量
	protected $vars = [];

	public function make($file) {
		$this->file = ROOT_PATH . '/web/view/' . $file . '.html';
		return $this;
	}

	public function with($key, $val) {
		$this->vars[$key] = $val;
		return $this;
	}

	public function __toString() {
		extract($this->vars);
		include $this->file;
		return '';
	}
}