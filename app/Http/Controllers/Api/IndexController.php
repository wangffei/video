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
 			$cookie_username = Cookie::make("username" , $username , $minutes = 30, $path = null, $domain = null, $secure = false, $httpOnly = false) ;
 			$cookie_nickname = Cookie::make("nickname" , $this -> encodeURI($list[0] -> nickname) , $minutes = 30, $path = null, $domain = null, $secure = false, $httpOnly = false) ;
 			$result = Array("code" => 200 , "msg" => "成功" , "data" => Array("nickname" => $list[0] -> nickname)) ;
        	return response(json_encode($result)) -> header("Content-Type", "application/json") -> withCookie($cookie_username) -> withCookie($cookie_nickname);
 		}else{
 			$result = Array("code" => 500 , "msg" => "账号或者密码错误" , "data" => "");
        	return response(json_encode($result)) -> header("Content-Type", "application/json");
 		}
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