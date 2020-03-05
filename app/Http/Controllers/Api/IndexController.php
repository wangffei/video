<?php
 
 namespace App\Http\Controllers\Api  ;

 require_once(dirname(__FILE__) . "/../../../Util/MovieUtil.php") ;
 use App\Http\Controllers\Controller;
 use Illuminate\Support\Facades\DB;
 use Illuminate\Support\Facades\Cookie;
 use Illuminate\Http\Request;
 use App\Util\MovieUtil ;

 class IndexController extends Controller{

 	public function news($page , $limit){
 		$start = ($page - 1)*$limit ;
 		$list = DB::select("select news.heading , news.sub_heading , news.release_time , news.html_url as url , news.page_view , news.cover , news.author , tags.tag_name  from news , tags where tags.id = news.tag_id limit $start , $limit") ;
 		$count = DB::select("select count(*) as count from news")[0] -> count ;
 		$result = Array("code" => 200 , "msg" => "成功" , "data" => Array("list" => $list , "count"=> $count , "limit" => $limit ));
        return response(json_encode($result)) -> header("Content-Type", "application/json");
 	}

 	// 将资讯详情页的预览量+1
 	public function addNewsView(Request $request){
 		$url = $request -> input("url") ;
 		$list = DB::update("update news set page_view = page_view + 1 where html_url = '{$url}'") ;
 		$result = Array("code" => 200 , "msg" => "成功" , "data" => "");
        return response(json_encode($result)) -> header("Content-Type", "application/json");
 	}

 	// 查出热门资讯
 	public function hot(){
 		$list = DB::select("select news.heading , news.sub_heading , news.release_time , news.html_url as url , news.page_view , news.cover , news.author , tags.tag_name  from news , tags where tags.id = news.tag_id order by page_view desc limit 10") ;
 		$result = Array("code" => 200 , "msg" => "成功" , "data" => $list);
        return response(json_encode($result)) -> header("Content-Type", "application/json");
 	}

 	public function test(){
 		echo "laile" ;
 		$list = DB::select("select * from config where flag = 1 limit 1") ;
 		//MovieUtil::movie2flv("C:\\Users\\Administrator\\Desktop\\1.mp4" , "1.flv" , $list[0]) ;
 		MovieUtil::getDuration("C:\\Users\\Administrator\\Desktop\\1.mp4") ;
 		echo "haha" ;
 	}

 	public function login(Request $request){
 		$username = $request -> input("username") ;
 		$password = $request -> input("password") ;

 		$list = DB::select("select * from user where username='{$username}' and password='{$password}' limit 1") ;
 		if(count($list) != 0){
 			$cookie_username = Cookie::make("username" , $username , $minutes = 300, $path = null, $domain = null, $secure = false, $httpOnly = false) ;
 			$cookie_nickname = Cookie::make("nickname" , $this -> encodeURI($list[0] -> nickname) , $minutes = 300, $path = null, $domain = null, $secure = false, $httpOnly = false) ;
 			$cookie_icon = Cookie::make("icon" , $this -> encodeURI($list[0] -> icon) , $minutes = 300, $path = null, $domain = null, $secure = false, $httpOnly = false) ;
 			$result = Array("code" => 200 , "msg" => "成功" , "data" => Array("nickname" => $list[0] -> nickname)) ;
        	return response(json_encode($result)) -> header("Content-Type", "application/json") -> withCookie($cookie_username) -> withCookie($cookie_nickname) -> withCookie($cookie_icon);
 		}else{
 			$result = Array("code" => 500 , "msg" => "账号或者密码错误" , "data" => "");
        	return response(json_encode($result)) -> header("Content-Type", "application/json");
 		}
 	}

 	// 发布留言
 	public function msg(Request $request , Cookie $cookie){
 		if(!$cookie -> get("username")){
 			$result = Array("code" => 500 , "msg" => "请先登录" , "data" => "");
        	return response(json_encode($result)) -> header("Content-Type", "application/json");
 		}
 		$txt = $request -> input("txt") ;
 		$imgs = $request -> input("imgs") ;
 		$username = $cookie -> get("username") ;

 		// 查出相关的信息，判断是否是重复发布
 		$list = DB::select("select * from msg where publisher='{$username}' order by id desc limit 1") ;
 		if(count($list) != 0){
 			date_default_timezone_set('prc');
 			if($txt == $list[0] -> data){
 				if(strtotime(now()) - strtotime($list[0] -> publish_time) < 300){
 					$result = Array("code" => 500 , "msg" => "不能重复发布" , "data" => "");
        			return response(json_encode($result)) -> header("Content-Type", "application/json");
 				}
 			}
 		}

 		if(is_null($txt)){
 			$result = Array("code" => 500 , "msg" => "发布的内容不能为空" , "data" => "");
        	return response(json_encode($result)) -> header("Content-Type", "application/json");
 		}

 		DB::insert("insert into msg(data , publisher , publish_time) values('{$txt}' , '{$username}' , now())") ;
 		$id = DB::select("SELECT LAST_INSERT_ID() as id") ;

 		if(!is_null($imgs)){
 			for($i = 0 ; $i < count($imgs) ; $i++){
	 			DB::insert("insert into msg_imgs(msg_id , img_url) values('{$id[0] -> id}' , '{$imgs[$i]["url"]}')") ;
	 		}
 		}

 		$result = Array("code" => 200 , "msg" => "发布成功" , "data" => Array("id" => $id[0] -> id));
        return response(json_encode($result)) -> header("Content-Type", "application/json");
 	}

 	// 点赞
 	public function up(Cookie $cookie , $type , $id){
 		if(!$cookie -> get("username")){
 			$result = Array("code" => 500 , "msg" => "请先登录" , "data" => "");
        	return response(json_encode($result)) -> header("Content-Type", "application/json");
 		}
 		$username = $cookie -> get("username") ;
 		$list = DB::select("select * from up_users where username='{$username}' and msg_id=$id limit 1") ;

 		if(count($list) == 0){
 			if($type == "up"){
 				DB::insert("insert into up_users(msg_id , username) values($id , '{$username}')") ;
 				DB::update("update msg set up = up + 1 where id=$id") ;
 			}
 		}else{
 			if($type == "no") {
 				DB::delete("delete from up_users where username='{$username}' and msg_id=$id limit 1") ;
 				DB::update("update msg set up = up - 1 where id=$id") ;
 			}
 		}
 	}

 	public function all_msg($page , $limit , Cookie $cookie){
 		$username = $cookie -> get("username") ;
 		$start = ($page - 1)*$limit ;
 		$list = DB::select("select * from msg order by id desc limit $start , $limit") ;
 		for($i = 0 ; $i < count($list) ; $i++){
 			$item = DB::select("select id , img_url as url from msg_imgs where msg_id={$list[$i] -> id}") ;
 			$list[$i] -> imgs = $item ;
 			$user = DB::select("select nickname , icon from user where username='{$list[$i] -> publisher}' limit 1") ;
 			$list[$i] -> user = $user[0] ;
 			// 查出该用户是否点赞
 			if(!is_null($username)){
 				$ups = DB::select("select * from up_users where username='{$username}' and msg_id={$list[$i] -> id} limit 1") ;
	 			if(count($ups) == 0){
	 				$list[$i] -> has_up = false ;
	 			}else{
	 				$list[$i] -> has_up = true ;
	 			}
 			}
 			// 查出该留言所有的回复
 			$reply = DB::select("select data , publisher from msg_reply where msg_id={$list[$i] -> id}") ;
 			if(count($reply) > 0){
 				for($j = 0 ; $j < count($reply) ; $j++){
 					$user1 = DB::select("select nickname , icon from user where username='{$reply[$j] -> publisher}' limit 1") ;
 					$reply[$j] -> user = $user1[0] ;
 				}
 			}
 			$list[$i] -> reply = $reply ;
 		}
 		$count = DB::select("select count(*) as count from msg") ;
 		$result = Array("code" => 200 , "msg" => "发布成功" , "data" => Array("count" => $count[0] -> count , "list" => $list));
        return response(json_encode($result)) -> header("Content-Type", "application/json");
 	}

 	// 回复
 	public function reply(Request $request , Cookie $cookie){
 		$txt = $request -> input("txt") ;
 		$username = $cookie -> get("username") ;
 		$id = $request -> input("id") ;

 		if(is_null($username)){
 			$result = Array("code" => 500 , "msg" => "请先登录" , "data" => "");
        	return response(json_encode($result)) -> header("Content-Type", "application/json");
 		}
 		if(is_null($txt)){
 			$result = Array("code" => 500 , "msg" => "发布的内容不能为空" , "data" => "");
        	return response(json_encode($result)) -> header("Content-Type", "application/json");
 		}

 		DB::insert("insert into msg_reply(data , msg_id , publisher) values('{$txt}' , $id , '{$username}')") ;
 		$id = DB::select("SELECT LAST_INSERT_ID() as id") ;

 		$result = Array("code" => 200 , "msg" => "回复成功" , "data" => Array("id" => $id[0] -> id));
        return response(json_encode($result)) -> header("Content-Type", "application/json");
 	}

 	private function encodeURI($url) {
	    $unescaped = array(
	        '%2D'=>'-','%5F'=>'_','%2E'=>'.','%21'=>'!', '%7E'=>'~',
	        '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')'
	    );
	    $reserved = array(
	        '%3B'=>';','%2C'=>',','%2F'=>'/','%3F'=>'?','%3A'=>':',
	        '%40'=>'@','%26'=>'&','%3D'=>'=','%2B'=>'+','%24'=>'$'
	    );
	    $score = array(
	        '%23'=>'#'
	    );
	    return strtr(rawurlencode($url), array_merge($reserved,$unescaped,$score));
	}
 }

?>