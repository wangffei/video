<?php

namespace App\Util;

class MovieUtil{

	/**
	* 将视频转flv格式
	*/
	static function movie2flv($source , $target , $config){
		$os = strtoupper(substr(PHP_OS,0,3))==='WIN'?'win':'linux' ; 
		$path = public_path() ;
		$ffmpeg = $path."\\lib\\ffmpeg.exe" ;
		$cmd = ($os == "win" ? $ffmpeg : "ffmpeg")." -y -i {$source} -s 640x360 -vcodec {$config -> vcodec} -acodec {$config -> acodec} " ;
		if($config -> uselogo){
			$cmd = $cmd."-vf movie={$config -> logo}[bb];[aa][bb]overlay=x={$config -> logo_position_x}:y={$config -> logo_position_y} " ;
		}
		$cmd = $cmd."{$target}" ;
		if($os == "win"){
			$cmd = $path."\lib\RunHiddenConsole.exe ".$cmd ;
		}else{
			$cmd = "nohup ".$cmd." &" ;
		}
		exec($cmd , $out , $status) ;
		return $status == 0 ;
	}

	// 从视频中截图作为封面 （后台运行）
	static function movie_cover($source , $target , $config){
		$os = strtoupper(substr(PHP_OS,0,3))==='WIN'?'win':'linux' ; 
		$path = public_path() ;
		$ffmpeg = $path."\\lib\\ffmpeg.exe" ;
		$cmd = ($os == "win" ? $ffmpeg : "ffmpeg")." -y -i {$source} -ss {$config -> cover_position} -s 220x110 -f image2 -vframes 1 {$target}" ;
		if($os == "win"){
			$cmd = "./lib/RunHiddenConsole.exe ".$cmd ;
		}else{
			$cmd = "nohup ".$cmd." &" ;
		}
		exec($cmd , $out , $status) ;
		return $status == 0 ;
	}

	// 从指定时间开始截图 (后台运行)
	static function movie_cover_time($source , $target , $time){
		$os = strtoupper(substr(PHP_OS,0,3))==='WIN'?'win':'linux' ;
		$path = public_path() ; 
		$ffmpeg = $path."\\lib\\ffmpeg.exe" ;
		$cmd = ($os == "win" ? $ffmpeg : "ffmpeg")." -y -i {$source} -ss {$time} -s 220x110 -f image2 -vframes 1 {$target}" ;
		if($os == "win"){
			$cmd = "./lib/RunHiddenConsole.exe ".$cmd ;
		}else{
			$cmd = "nohup ".$cmd." &" ;
		}
		exec($cmd , $out , $status) ;
		return $status == 0 ;
	}

	/* 将视频转hls，在后台运行 */
	static function movie2hls($source , $target , $config){
		$os = strtoupper(substr(PHP_OS,0,3))==='WIN'?'win':'linux' ; 
		$path = public_path() ;
		$ffmpeg = $path."\\lib\\ffmpeg.exe" ;
		$cmd = ($os == "win" ? $ffmpeg : "ffmpeg")." -i {$source} -profile:v baseline -level 3.0 -s 640x360 -start_number 0 -hls_time 10 -hls_list_size 0 -f hls {$target}" ;
		if($os == "win"){
			$cmd = "./lib/RunHiddenConsole.exe ".$cmd ;
		}else{
			$cmd = "nohup ".$cmd." &" ;
		}
		exec($cmd , $out , $status) ;
		return $status == 0 ;
	}

	/* 获取视频时长 */
	static function getDuration($source){
		$os = strtoupper(substr(PHP_OS,0,3))==='WIN'?'win':'linux' ; 
		$path = public_path() ;
		$ffmpeg = $path."\\lib\\ffmpeg.exe" ;
		$cmd = ($os == "win" ? $ffmpeg : "ffmpeg")." -i {$source} 2>&1 " ;
		exec($cmd , $out , $status) ;
		$pattern = "/Duration: (.*?),/" ;
		$str = implode("," , $out) ;
		$res_int = preg_match($pattern, $str, $matches);
		if ($res_int) {
        	$result = $matches[1] ;
        	$out = explode(":" , $result) ;
        	$num = number_format($out[0])*60*60 + number_format($out[1])*60 + number_format($out[2]) ;
        	return $num ;
	    } else {
	        return 0 ;
	    }
	}
}