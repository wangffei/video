<?php

namespace App\Util;

class MovieUtil{
	/**
	* 将视频转flv格式
	*/
	static function movie2flv($source , $target , $config){
		$cmd = "ffmpeg -y -i '{$source}' -s 640x360 -vcodec {$config -> vcodec} -acodec {$config -> acodec} " ;
		if($config -> uselogo){
			$cmd = $cmd."-vf movie={$config -> logo}[bb];[aa][bb]overlay=x={$config -> logo_position_x}:y={$config -> logo_position_y} " ;
		}
		$cmd = $cmd."'{$target}'" ;
		exec($cmd , $out , $status) ;
		return $status == 0 ;
	}

	// 从视频中截图作为封面
	static function movie_cover($source , $target , $config){
		$cmd = "ffmpeg -y -i '{$source}' -ss 5 -s 220x110 -f image2 -vframes 1 '{$target}'" ;
		exec($cmd , $out , $status) ;
		return $status == 0 ;
	}

	static function movie2hls($source , $target , $config){
		$cmd = "ffmpeg -i '{$source}' -profile:v baseline -level 3.0 -s 640x360 -start_number 0 -hls_time 10 -hls_list_size 0 -f hls '{$target}'" ;
		exec($cmd , $out , $status) ;
		return $status == 0 ;
	}
}