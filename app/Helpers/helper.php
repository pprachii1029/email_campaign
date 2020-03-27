<?php
include_once(public_path('getid3/getid3.php'));

function dum($arr){
    echo "<pre>";
    print_r($arr);
    exit;
}

function upload($request,$file,$path){
    if($request->file($file)){
        $files      =   $request->file($file);
        $name       =   md5(md5(time()).md5(rand(12345,99999))).'.'.$request->file($file)->extension();
        $picture    =   $files->move('public/'.$path,$name);
        $picture    =   'public/'.$path.'/'.$name;
        return $picture;
    }else{
        return '';
    }
}

function upload_blob($request,$file,$path){
    if($request->file($file)){
        $files      =   $request->file($file);
        $name       =   md5(md5(time()).md5(rand(12345,99999)));
        $picture    =   $files->move('public/'.$path,$name);
        $picture    =   'public/'.$path.'/'.$name;

        $link       =   URL('shell.php');
        $post       =   ['method'=>'video_to_mp3','link'=>$picture];
        $result     =   json_decode(hit_curl_post($link,$post),true);

        return $result['data'];
    }else{
        return 'error';
    }
}

function upload_blob_video($request,$file,$path){
    if($request->file($file)){
        $files      =   $request->file($file);
        $name       =   md5(md5(time()).md5(rand(12345,99999)));
        $picture    =   $files->move('public/'.$path,$name);
        $picture    =   'public/'.$path.'/'.$name;

        return $picture;
    }else{
        return 'error';
    }
}

function upload_multiple($request,$file,$path){
    if($request->file($file)){
        $files      =   $request->file($file);
        foreach($files as $file){
            $name       =   md5(md5(time()).md5(rand(12345,99999))).'.'.$file->extension();
            $picture    =   $file->move('public/'.$path,$name);
            $final[]    =   'public/'.$path.'/'.$name;
        }
        return $final;
    }else{
        return [];
    }
}

function base64_to_jpeg($base64_string,$path){
    $output_file= 'public/'.$path.rand().md5(time());
    $ifp        = fopen( $output_file, 'wb' );
    $data       = $base64_string;
    fwrite($ifp,base64_decode($data));
    fclose($ifp); 
    return $output_file; 
}

function upload_base64($request,$key,$path){
    $final = [];
    if(!@$request[$key]){
        $request[$key] = [];
    }
    foreach($request[$key] as $row){
        $output_file= 'public/'.$path.(uniqid(rand(), true));
        $ifp        = fopen( $output_file, 'wb' );
        $data       = $row;
        fwrite($ifp,base64_decode($data));
        fclose($ifp); 
        $final[] = $output_file; 
    }
    return $final;
}

function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function get_current_tz(){
    $ip = get_client_ip();
    $tz = json_decode(hit_curl_get("http://ip-api.com/json/".$ip),true);
    return ($tz['timezone']) ? $tz['timezone'] : 'Asia/Kolkata';
}

function my_time($format,$time,$tz='UTC'){
    $triggerOn  = '04/01/2013 03:08 PM';

    $schedule_date = new DateTime($time, new DateTimeZone('UTC') );
    $schedule_date->setTimeZone(new DateTimeZone($tz));
    $triggerOn =  $schedule_date->format($format);

    return $triggerOn;
}

function make_video($index){
    $video = [];
    $ffmpeg = new FFMpeg();
    foreach($index as $val){
        if($val['content']=='video'){
            $video[] = $ffmpeg->splitVideo($val['video'],$val['start'],$val['duration'],$val['mute']);
        }else if($val['content']=='photo'){
            $video[] = $ffmpeg->photoToVideo($val['photo'],$val['duration']);
        }else if($val['content']=='url'){
            $video[] = $ffmpeg->photoToVideo($val['url'],$val['duration']);
        }else if($val['content']=='snapshot'){
            $video[] = $ffmpeg->photoToVideo($val['snapshot_ss'],$val['duration']);
        }
    }

    return $ffmpeg->concatVideos($video);
}


function hit_curl_post($link,$post){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$link);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close ($ch);
    return $server_output;
}

function hit_curl_get($link){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$link);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close ($ch);
    return $server_output;
}

function implode_assoc($arr,$key){
	$data = "";
	if(count($arr)>0){
		$data = $arr[0][$key];
		for($i=1;$i<count($arr);$i++){
			$data .= ','.$arr[$i][$key];
		}
	}
	return $data;
}

function move($arr, $old_index, $new_index) {
    while ($old_index < 0) {
        $old_index .= count($arr);
    }
    while ($new_index < 0) {
        $new_index .= count($arr);
    }
    if ($new_index >= count($arr)) {
        $k = $new_index - count($arr);
        while (($k--) + 1) {
			echo "errror";exit;
        }
    }
	array_splice($arr,$new_index,0,array_splice($arr,$old_index,1)[0]);
   return $arr;
}

function add_audio_to_video($vdo,$audio){
    $ffmpeg = new FFMpeg();
    if(!empty($audio)){
        return $ffmpeg->addAudioInFinalVideo($vdo,$audio);
    }else{
        return $vdo;
    }
}

function add_video_over_video($vdo,$vdo2){
    $ffmpeg = new FFMpeg();
    if(!empty($vdo2)){
        return $ffmpeg->addVideoOverVideo($vdo,$vdo2);
    }else{
        return $vdo;
    }
}

function concat_all_vdos($vdo){
    $ffmpeg = new FFMpeg();
    return $ffmpeg->convertAndConcatVideos($vdo);
}

function make_video_with_user_audio($vdo,$audio){
    $ffmpeg = new FFMpeg();
    $pic    = $ffmpeg->captureThumb($vdo);
    return $ffmpeg->addAudioInPhoto($pic,$audio);
}

function join_two_videos($v1,$v2){
    $ffmpeg = new FFMpeg();
    return $ffmpeg->concatVideos([$v1,$v2]);
}

function join_two_videos_for_campaign($v1,$v2){
    $ffmpeg = new FFMpeg();
    return $ffmpeg->convertAndConcatVideos([$v1,$v2]);
}

function capture_thumb($v){
    $ffmpeg = new FFMpeg();
    return $ffmpeg->captureThumb($v);
}

function make_outro_video($pic,$audio){
    $ffmpeg = new FFMpeg();
    $video  = [];
    if(count($pic)>0){

        foreach ($pic as $key => $value) {
            $video[] = $ffmpeg->photoToVideo($value,3);
        }
        $video = $ffmpeg->concatVideos($video);

        if(!empty($audio)){
            return $ffmpeg->addAudioInFinalVideo($video,$audio);
        }else{
            return $video;
        }

    }else{
        return "";
    }
}

function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}

function get_duration($filename){
    $getID3 = new getID3;
    $file = $getID3->analyze($filename);
    return 30;
    return $file['playtime_seconds'];
}