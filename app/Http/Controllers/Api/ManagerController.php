<?php
 
namespace App\Http\Controllers\Api  ;

#require_once(dirname(__FILE__) . "/../../../Util/editormd.uploader.class.php") ;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ManagerController extends Controller{
	// 获取所有标签信息
	public function get_tags1() {
		$tags = DB::select("select * from tags");
		$result = Array("code" => 0, "msg" => "成功", "count" => 1, "data" => $tags);
		return response(json_encode($result)) -> header("Content-Type", "application/json");
	}

	public function upload_news1(Request $request) {
		$heading = $request -> input('heading') ;
		$sub_heading = $request -> input('sub_heading') ;
		$md_url = $request -> input('md_url') ;
		$html_url = $request -> input('html_url') ;
		$cover = $request -> input('cover') ;
		$tag = $request -> input('tag') ;
   }
}
