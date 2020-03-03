<?php
 
 namespace App\Http\Controllers\Api  ;

 require_once(dirname(__FILE__) . "/../../../Util/MovieUtil.php") ;
 use App\Http\Controllers\Controller;
 use Illuminate\Support\Facades\DB;
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
 		MovieUtil::movie2flv("C:\\Users\\Administrator\\Desktop\\1.mp4" , "1.flv" , $list[0]) ;
 		echo "haha" ;
 	}

 }

?>