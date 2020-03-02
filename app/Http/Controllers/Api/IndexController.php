<?php
 
 namespace App\Http\Controllers\Api  ;

 #require_once(dirname(__FILE__) . "/../../../Util/editormd.uploader.class.php") ;
 use App\Http\Controllers\Controller;
 use Illuminate\Support\Facades\DB;
 use Illuminate\Http\Request;

 class IndexController extends Controller{

 	public function news($page , $limit){
 		$start = ($page - 1)*$limit ;
 		$list = DB::select("select news.heading , news.sub_heading , news.release_time , news.html_url as url , news.page_view , news.cover , news.author , tags.tag_name  from news , tags where tags.id = news.tag_id limit $start , $limit") ;
 		$count = DB::select("select count(*) as count from news")[0] -> count ;
 		$result = Array("code" => 200 , "msg" => "成功" , "data" => Array("list" => $list , "count"=> $count , "limit" => $limit ));
        return response(json_encode($result)) -> header("Content-Type", "application/json");
 	}

 }

?>