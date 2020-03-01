<?php
namespace app\index\controller;
use app\index\service\UserService ;
use think\View ;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>';
    }
	
	public function create($val){
		return $val."Hello World" ;
	}

	public function view(){
		$user = new UserService() ;
		$data["name"] = $user -> user() ;
		return $this -> fetch("index" , $data) ;
	}
}
