<?php

namespace app\index\service ;
use \app\index\model\User ;

class UserService{
	
	public function user(){
		$user = new User() ;
		return $user -> all() ;
	}
	
}