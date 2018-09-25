<?php
namespace web\controller;
use Gregwar\Captcha\CaptchaBuilder;

class Index
{
	protected $view;

	public function __construct() {
		$this->view = new \core\View();
	}

	public function index() {
		return $this->view->make('index')->with('version', '1.0');
	}

	public function hello() {
		echo 'hello';
	}

	public function code() {
		header('Content-type: image/jpeg');
		$builder = new CaptchaBuilder;
		$builder->build(200, 50);
		// 存入session
		$_SESSION['phrase'] = $builder->getPhrase();
		$builder->output();
	}
}