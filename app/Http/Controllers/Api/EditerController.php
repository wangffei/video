<?php
 
 namespace App\Http\Controllers\Api  ;

 #require_once(dirname(__FILE__) . "/../../../Util/editormd.uploader.class.php") ;
 use App\Http\Controllers\Controller;
 use Illuminate\Support\Facades\DB;
 use Illuminate\Http\Request;

 class EditerController extends Controller{

   public function upload_img(Request $request){
      // 1、获取上传的文件

      $file=$request->file('editormd-image-file');
      // 2、获取上传文件的文件名（带后缀，如abc.png）

      $filename=$file->getClientOriginalName();
      // 3、获取上传文件的后缀（如abc.png，获取到的为png）

      $fileextension=$file->getClientOriginalExtension();
      // 4、获取上传文件的大小

      // $filesize=$file->getClientSize();
      // 5、获取缓存在tmp目录下的文件名（带后缀，如php8933.tmp）

      // $filaname=$file->getFilename();
      // 6、获取上传的文件缓存在tmp文件夹下的绝对路径

      $realpath=$file->getRealPath();
      // 7、将缓存在tmp目录下的文件移到某个位置，返回的是这个文件移动过后的路径
      $newfilename = time().".".$fileextension ;
      $path=$file->move("./uploads/images" , $newfilename);
      // move() 方法有两个参数，第一个参数是文件移到哪个文件夹下的路径，第二个参数是将上传的文件重新命名的文件名

      // 8、检测上传的文件是否合法，返回值为true或false

      if(!$file->isValid()){
         $result = Array("success" => 1 , "url" => "/uploads/images/".$newfilename);
         return response(json_encode($result)) -> header("Content-Type", "text/html");
      }else{
         $result = Array("code" => 0, "msg" => "上传失败" , "data" => "");
         return response(json_encode($result)) -> header("Content-Type", "text/html");
      }
   }

   public function submit(Request $request){
      $md = $request -> input("test-editormd-markdown-doc") ;
      $html = $request -> input("test-editormd-html-code") ;

      if(!is_null($md)){
         $newfilename = time().".md" ;
         $f = fopen("./uploads/md/".$newfilename , "w") ;
         fwrite($f , $md) ;
         fclose($f) ;
      }

      if(!is_null($md)){
         $newfilename = time().".html" ;
         $f = fopen("./uploads/html/".$newfilename , "w") ;
         fwrite($f , "<head><link rel='stylesheet' href='/editer/css/style.css' /><link rel='stylesheet' href='/editer/css/editormd.css' /><script src='/editer/js/jquery.min.js'></script><script src='/editer/js/editormd.js'></script><script src='/editer/lib/marked.min.js'></script><script src='/editer/lib/prettify.min.js'></script><script src='/editer/lib/raphael.min.js'></script><script src='/editer/lib/underscore.min.js'></script><script src='/editer/lib/sequence-diagram.min.js'></script></script><script src='/editer/lib/flowchart.min.js'></script><script src='/editer/lib/jquery.flowchart.min.js'></script></script><script src='/editer/lib/jquery.flowchart.min.js'></script></head><div id='code'><textarea>".$md."</textarea></div><script>editormd.markdownToHTML('code', {
             htmlDecode: 'style,script,iframe', emoji: true,
             taskList:true,
             tex: true,  flowChart:true, sequenceDiagram:true,});</script>") ;
         fclose($f) ;
      }
      
      $result = Array("code" => 200, "msg" => "成功", "data" => "");
      return response(json_encode($result)) -> header("Content-Type", "application/json");
   }

   public function news(Request $request) {
      $heading = $request -> input('heading');
      $sub_heading = $request -> input('sub_heading');
      $md_url = $request -> input('md_url');
      $html_url = $request -> input('html_url');
      $cover = $request -> input('cover');
      $tag = $request -> input('tag');
   }
   
 }
