<?php
 
namespace App\Http\Controllers\Api  ;

#require_once(dirname(__FILE__) . "/../../../Util/editormd.uploader.class.php") ;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ManagerController extends Controller{
	public function news(Request $request) {
		$heading = $request -> input('heading');
		$sub_heading = $request -> input('sub_heading');
		$md_url = $request -> input('md_url');
		$html_url = $request -> input('html_url');
		$cover = $request -> input('cover');
		$tag = $request -> input('tag');
   }
}
