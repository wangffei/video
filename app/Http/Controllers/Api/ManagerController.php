<?php
 
namespace App\Http\Controllers\Api  ;

#require_once(dirname(__FILE__) . "/../../../Util/editormd.uploader.class.php") ;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
		$md = $request -> input('md') ;
		//$html = $request -> input('html') ;
		$cover = $request -> input('cover_file') ;
		$tag = $request -> input('tag') ;
		$md_url = null;
		$html_url = null;
		$release_time = Carbon::now() -> toDateTimeString();

		if(!is_null($md)){
			$newfilename = time().".md" ;
			$f = fopen("./uploads/md/".$newfilename , "w") ;
			fwrite($f , $md) ;
			fclose($f) ;
			$md_url = "./uploads/md/".$newfilename;
		}

		if(!is_null($md)){
			$newfilename = time().".html" ;
			$f = fopen("./uploads/html/".$newfilename , "w") ;
			fwrite($f , "<head><link rel='stylesheet' href='/editer/css/style.css' /><link rel='stylesheet' href='/editer/css/editormd.css' /><script src='/editer/js/jquery.min.js'></script><script src='/editer/js/editormd.js'></script><script src='/editer/lib/marked.min.js'></script><script src='/editer/lib/prettify.min.js'></script><script src='/editer/lib/raphael.min.js'></script><script src='/editer/lib/underscore.min.js'></script><script src='/editer/lib/sequence-diagram.min.js'></script></script><script src='/editer/lib/flowchart.min.js'></script><script src='/editer/lib/jquery.flowchart.min.js'></script></script><script src='/editer/lib/jquery.flowchart.min.js'></script></head><div id='code'><textarea>".$md."</textarea></div><script>editormd.markdownToHTML('code', {
			 htmlDecode: 'style,script,iframe', emoji: true,
			 taskList:true,
			 tex: true,  flowChart:true, sequenceDiagram:true,});</script>") ;
			fclose($f) ;
			$html_url = "./uploads/html/".$newfilename;
		}

		if($tag != null && $md_url != null && $html_url != null) {
			switch ($tag) {
				case '1':
					DB::update('update tags set count=count+1 where id=?', [$tag]);
					break;

				case '2':
					DB::update('update tags set count=count+1 where id=?', [$tag]);
					break;

				case '3':
					DB::update('update tags set count=count+1 where id=?', [$tag]);
					break;

				case '4':
					DB::update('update tags set count=count+1 where id=?', [$tag]);
					break;

				case '5':
					DB::update('update tags set count=count+1 where id=?', [$tag]);
					break;

				case '6':
					DB::update('update tags set count=count+1 where id=?', [$tag]);
					break;
				
				default:
					return false;
					break;
			}
		}else {
			$result = Array("code" => 500, "msg" => "失败", "count" => 1, "data" =>"");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}

		if($tag != null && $md_url != null && $html_url != null && $heading != null) {
			$bool = DB::insert("insert into news(heading, sub_heading, release_time, md_url, html_url, page_view, cover, author, tag_id) values('$heading', '$sub_heading', '$release_time', '$md_url', '$html_url', 0, '$cover', '管理员', '$tag')");
			$result = Array("code" => 200, "msg" => "成功", "count" => 1, "data" =>"");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}else {
			$result = Array("code" => 500, "msg" => "失败", "count" => 1, "data" =>"");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
    }
}
