<?php

    if($_POST['method']=='make_video'){
        $data      = json_decode($_POST['data'],true);
        $time      = time().rand(123456789,999999999);
        $video     = [];
       
        foreach($data as $key => $val){
            if($val['content']=='video'){
                $start  = gmdate("H:i:s", $val['start']);
                $end    = gmdate("H:i:s", ($val['duration']));
                $audio  = ($val['mute']==1) ? ' -an ' : ' ';
                $vdo = rand(123456789,999999999).md5(time());
                
                exec("ffmpeg -i ".$val['video']." -ss ".$start." -t ".$end." -async 1".$audio."".$vdo.".mp4");
                exec("ffmpeg -i ".$vdo.".mp4 -c:v libx264 -c:a aac -b:a 192k ".$time.".mp4");
                unlink($vdo.".mp4");
                $video[] = $time.".mp4";
                $time++;
            }else if($val['content']=='photo'){
                $vdo  = rand(123456789,999999999).md5(time()).".mp4";
                $vdo2 = rand(123456789,999999999).md5(time())."_1.mp4";
                exec("ffmpeg -loop 1 -i ".$val['photo']." -f lavfi -i anullsrc -c:v libx264 -t ".$val['duration']." -pix_fmt yuv420p -vf scale=640:480 ".$vdo);
                exec("ffmpeg -i ".$vdo." -f lavfi -i anullsrc -c:v libx264 -video_track_timescale 30k -c:a aac -ac 6 -ar 44100 -shortest ".$vdo2);
                exec("ffmpeg -i ".$vdo2." -c:v libx264 -c:a aac -b:a 192k ".$time.".mp4");
                unlink($vdo);
                unlink($vdo2);

                $video[] = $time.".mp4";
                $time++;
            }else if($val['content']=='url'){
                $vdo  = rand(123456789,999999999).md5(time()).".mp4";
                $vdo2 = rand(123456789,999999999).md5(time())."_1.mp4";
                exec("ffmpeg -loop 1 -i ".$val['url']." -f lavfi -i anullsrc -c:v libx264 -t ".$val['duration']." -pix_fmt yuv420p -vf scale=640:480 ".$vdo);
                exec("ffmpeg -i ".$vdo." -f lavfi -i anullsrc -c:v libx264 -video_track_timescale 30k -c:a aac -ac 6 -ar 44100 -shortest ".$vdo2);
                exec("ffmpeg -i ".$vdo2." -c:v libx264 -c:a aac -b:a 192k ".$time.".mp4");
                unlink($vdo);
                unlink($vdo2);

                $video[] = $time.".mp4";
                $time++;
            }else if($val['content']=='snapshot'){
                $vdo = rand(123456789,999999999).md5(time()).".mp4";
                $vdo2 = rand(123456789,999999999).md5(time())."_1.mp4";
                exec("ffmpeg -loop 1 -i ".$val['snapshot_ss']." -f lavfi -i anullsrc -c:v libx264 -t ".$val['duration']." -pix_fmt yuv420p -vf scale=640:480 ".$vdo);
                exec("ffmpeg -i ".$vdo." -f lavfi -i anullsrc -c:v libx264 -video_track_timescale 30k -c:a aac -ac 6 -ar 44100 -shortest ".$vdo2);
                exec("ffmpeg -i ".$vdo2." -c:v libx264 -c:a aac -b:a 192k ".$time.".mp4");
                unlink($vdo);
                unlink($vdo2);
                
                $video[] = $time.".mp4";
                $time++;
            }
        }

        $result = [];
        if(count($video)){
            $text = "";
            foreach ($video as $value) {
                $text .= "file '".$value."'\n";
            }

            $file = time().rand(123456789,999999999).".txt";
            file_put_contents($file,$text);
            chmod($file, 0777);
            
            $result = exec("ffmpeg -f concat -i ".$file." -c copy ".$_POST['path']);
            
            unlink($file);
            foreach($video as $r){
              unlink($r);
            }
        }

        echo json_encode(['status'=>200,'data'=>$result]);
    }



    if($_POST['method']=='add_audio_in_video'){
        $audio  = $_POST['audio'];
        $video  = $_POST['video'];
        $path   = $_POST['path'];

        $command = 'ffmpeg -i '.$video.' -i '.$audio.' -filter_complex " [1:0] apad " -shortest '.$path;
        $result = exec($command);

        echo json_encode(['status'=>200,'data'=>$result]);
    }

    if($_POST['method']=='video_to_mp3'){
        $audio      = $_POST['link'];
        $command    = "ffmpeg -i ".$audio." -b:a 192K -vn ".$audio.".mp3";
        $result     = exec($command);
        unlink($audio);
        echo json_encode(['status'=>200,'data'=>$audio.".mp3"]);
    }

    if($_POST['method']=='make_video_with_user_audio'){
        $audio      = $_POST['audio'];
        $picture    = $_POST['picture'];
        $path       = $_POST['path'];
        $snapshot   = time().rand(123123,666999);
        exec("ffmpeg -ss 00:00:03 -i ".$picture." -vframes 1 -q:v 2 ".$snapshot.".jpg");
        $command    = "ffmpeg -loop 1 -i ".$snapshot.".jpg -i ".$audio." -c:v libx264 -tune stillimage -c:a aac -b:a 192k -pix_fmt yuv420p -shortest ".$path;
        $result     = exec($command);
        unlink($snapshot.".jpg");
        echo json_encode(['status'=>200,'data'=>$path]);
    }

    if($_POST['method']=='join_two_videos'){
        $video1    = $_POST['video1'];
        $video2    = $_POST['video2'];
        $path      = $_POST['path'];
        $v1        = time();
        $v2        = $v1+1;
        exec("ffmpeg -i ".$video1." -c:v libx264 -c:a aac -b:a 192k ".$v1.".mp4");
        exec("ffmpeg -i ".$video2." -c:v libx264 -c:a aac -b:a 192k ".$v2.".mp4");

        $text      = "";
        $text     .= "file '".$v1.".mp4'\n";
        $text     .= "file '".$v2.".mp4'\n";

        $file = time().rand(123456789,999999999).".txt";
        file_put_contents($file,$text);
        chmod($file, 0777);
        
        exec("ffmpeg -f concat -i ".$file." -c copy ".$path);
        
        unlink($video1);
        unlink($v1.".mp4");
        unlink($v2.".mp4");
        unlink($video2);
        unlink($file);

        echo json_encode(['status'=>200,'data'=>$path]);
    }


    if($_POST['method']=='capture_thumb'){
        $video      = $_POST['video'];
        $path       = $_POST['path'];
        exec("ffmpeg -ss 00:00:03 -i ".$video." -vframes 1 -q:v 2 ".$path);
        echo json_encode(['status'=>200,'data'=>$path]);
    }