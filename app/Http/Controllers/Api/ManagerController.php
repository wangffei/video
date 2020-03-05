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
		$result = Array("code" => 200, "msg" => "成功", "count" => 1, "data" => $tags);
		return response(json_encode($result)) -> header("Content-Type", "application/json");
	}

	// 资讯发布
	public function upload_news1(Request $request) {
		$heading = $request -> input('heading') ;
		$sub_heading = $request -> input('sub_heading') ;
		$md = $request -> input('md') ;
		$id = $request -> input('id') ;
		//$html = $request -> input('html') ;
		$cover = $request -> input('cover_file') ;
		$tag = $request -> input('tag') ;
		$md_url = null;
		$html_url = null;
		$release_time = Carbon::now() -> toDateTimeString();
		$path = public_path() ;

		$l = DB::select("select * from news where id=$id") ;
		if(count($l) != 0){
			unlink($path.$l[0]->md_url) ;
			unlink($path.$l[0]->html_url) ;
			DB::delete("delete from news where id = $id") ;
		}

		if(!is_null($md)){
			$newfilename = time().".md" ;
			$f = fopen("./uploads/md/".$newfilename , "w") ;
			fwrite($f , $md) ;
			fclose($f) ;
			$md_url = "/uploads/md/".$newfilename;
		}

		if(!is_null($md)){
			$newfilename = time().".html" ;
			$f = fopen("./uploads/html/".$newfilename , "w") ;
			fwrite($f , "<head><link rel='stylesheet' href='/editer/css/style.css' /><link rel='stylesheet' href='/editer/css/editormd.css' /><script src='/editer/js/jquery.min.js'></script><script src='/editer/js/editormd.js'></script><script src='/editer/lib/marked.min.js'></script><script src='/editer/lib/prettify.min.js'></script><script src='/editer/lib/raphael.min.js'></script><script src='/editer/lib/underscore.min.js'></script><script src='/editer/lib/sequence-diagram.min.js'></script></script><script src='/editer/lib/flowchart.min.js'></script><script src='/editer/lib/jquery.flowchart.min.js'></script></script><script src='/editer/lib/jquery.flowchart.min.js'></script></head><div id='code'><textarea>".$md."</textarea></div><script>editormd.markdownToHTML('code', {
			 htmlDecode: 'style,script,iframe', emoji: true,
			 taskList:true,
			 tex: true,  flowChart:true, sequenceDiagram:true,});</script>") ;
			fclose($f) ;
			$html_url = "/uploads/html/".$newfilename;
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
			$sql = "" ;
			if(is_null($id)){
				$sql = "insert into news(heading, sub_heading, release_time, md_url, html_url, page_view, cover, author, tag_id) values('$heading', '$sub_heading', '$release_time', '$md_url', '$html_url', 0, '$cover', '管理员', '$tag')" ;
			}else{
				$sql = "insert into news(id , heading, sub_heading, release_time, md_url, html_url, page_view, cover, author, tag_id) values($id , '$heading', '$sub_heading', '$release_time', '$md_url', '$html_url', 0, '$cover', '管理员', '$tag')" ;
			}
			$bool = DB::insert($sql);
			$result = Array("code" => 200, "msg" => "成功", "count" => 1, "data" =>Array("md" => $md_url));
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}else {
			$result = Array("code" => 500, "msg" => "失败", "count" => 1, "data" =>"");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
    }

    // 获取轮播图信息
    public function cartoons1() {
    	$cartoon = DB::select("select * from cartoons");
		$result = Array("code" => 200, "msg" => "成功", "count" => 1, "data" => $cartoon);
		return response(json_encode($result)) -> header("Content-Type", "application/json");
    }

    // 添加轮播图信息
    public function add_cartoon1(Request $request) {
    	$title = $request -> input('title');
    	$link = $request -> input('link');
    	$flag = $request -> input('flag');
    	$cover_file = $request -> input('cover_file');

    	if($title != null && $link != null && $flag != null && $cover_file != null) {
    		$bool = DB::insert("insert into cartoons(img_url, link, flag, title) values(?,?,?,?)", [$cover_file, $link, $flag, $title]);
    		$result = Array("code" => 200, "msg" => "成功", "count" => 1, "data" =>"");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
    	}else {
    		$result = Array("code" => 500, "msg" => "失败", "count" => 1, "data" =>"");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
    	}
    }

    // 删除轮播图信息
    public function delete_cartoon1(Request $request) {
    	$id = $request -> input('id');

    	if($id != null) {
    		$bool = DB::delete("delete from cartoons where id=?", [$id]);
    		$result = Array("code" => 200, "msg" => "成功", "count" => 1, "data" =>"");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
    	}else {
    		$result = Array("code" => 500, "msg" => "失败", "count" => 1, "data" =>"");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
    	}
    }

    // 批量删除轮播图信息
    public function delete_cartoons1(Request $request) {
    	$ids = $request -> input('ids');

		if($ids != null) {
			foreach ($ids as $value) {
				$bool = DB::delete("delete from cartoons where id=?", [$value]);
			}
			$result = Array("code" => 200, "msg" => "删除成功！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}else {
			$result = Array("code" => 500, "msg" => "删除失败！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
    }

    // 修改轮播图信息
    public function update_cartoon1(Request $request) {
    	$id = $request -> input('id');
    	$title = $request -> input('title');
    	$link = $request -> input('link');
    	$flag = $request -> input('flag');
    	$cover_file1 = $request -> input('cover_file1');

    	if($id != null) {
    		if($cover_file1 != null) {
    			$bool1 = DB::update("update cartoons set img_url=? where id=?", [$cover_file1, $id]);
    		}
    		$bool2 = DB::update("update cartoons set title=? where id=?", [$title, $id]);
    		$bool3 = DB::update("update cartoons set link=? where id=?", [$link, $id]);
    		$bool4 = DB::update("update cartoons set flag=? where id=?", [$flag, $id]);
    		$result = Array("code" => 200, "msg" => "删除成功！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
    	}else {
    		$result = Array("code" => 500, "msg" => "删除失败！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
    	}
    }

    public function all_news(){
    	$list = DB::select("select * from news") ;
    	$result = Array("code" => 200, "msg" => "成功" , "data" => $list);
		return response(json_encode($result)) -> header("Content-Type", "application/json");
    }

    public function news_id($id){
    	$list = DB::select("select * from news where id=$id") ;
    	$result = Array("code" => 200, "msg" => "成功" , "data" => $list[0]);
		return response(json_encode($result)) -> header("Content-Type", "application/json");
    }
}
