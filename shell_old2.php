<?php
    if($_POST['method']=='make_video'){
        $data      = json_decode($_POST['data'],true);
        $time      = time().rand(123456789,999999999);
        $video     = [];
        
        foreach($data as $key => $val){
            if($val['content']=='video'){
                $start  = $val['start'];
                $end    = $val['duration'];
                
                if($val['mute']==1){
                    $v1 = $time++;
                    $v2 = $time++;
                    $f = $time++;
                    exec("ffmpeg -i ".$val['video']." -ss ".$start." -t ".$end." -async 1 -an ".$v1.".mp4");
                    exec("ffmpeg -i ".$v1.".mp4 -i mute.mp3 -ac 6 -ar 44100 -shortest ".$v2.".mp4");
                    exec("ffmpeg -i ".$v2.".mp4 -vcodec libx264 -acodec aac ".$f.".mp4");
                    unlink($v1.".mp4",$v2.".mp4");
                }else{
                    $v1 = $time++;
                    $v2 = $time++;
                    $v3 = $time++;
                    $f = $time++;
                    exec("ffmpeg -i ".$val['video']." -ss ".$start." -t ".$end." -async 1 ".$v1.".mp4");
                    exec("ffmpeg -i ".$val['video']." -ss ".$start." -t ".$end." -async 1 ".$v2.".mp3");
                    exec("ffmpeg -i ".$v1.".mp4 -i ".$v2.".mp3 -ac 6 -ar 44100 -shortest ".$v3.".mp4");
                    exec("ffmpeg -i ".$v3.".mp4 -vcodec libx264 -acodec aac ".$f.".mp4");
                    unlink($v1.".mp4",$v2.".mp3",$v2.".mp4");
                }

                $video[] = $f.".mp4";
                $time++;
            }else if($val['content']=='photo'){
                $v1 = $time++;
                $v2 = $time++;
                $f = $time++;
                exec("ffmpeg -loop 1 -i ".$val['photo']." -t ".$val['duration']." -r 30 -pix_fmt yuv420p ".$v1.".mp4");
                exec("ffmpeg -i ".$v1.".mp4 -i mute.mp3 -ac 6 -ar 44100 -shortest ".$v2.".mp4");
                exec("ffmpeg -i ".$v2.".mp4 -vcodec libx264 -acodec aac ".$f.".mp4 ");
                unlink($v1.".mp4",$v2.".mp4");

                $video[] = $f.".mp4";
                $time++;
            }else if($val['content']=='url'){
                $v1 = $time++;
                $v2 = $time++;
                $f = $time++;
                exec("ffmpeg -loop 1 -i ".$val['url']." -t ".$val['duration']." -r 30 -pix_fmt yuv420p ".$v1.".mp4");
                exec("ffmpeg -i ".$v1.".mp4 -i mute.mp3 -ac 6 -ar 44100 -shortest ".$v2.".mp4");
                exec("ffmpeg -i ".$v2.".mp4 -vcodec libx264 -acodec aac ".$f.".mp4 ");
                unlink($v1.".mp4",$v2.".mp4");
                
                $video[] = $f.".mp4";
                $time++;
            }else if($val['content']=='snapshot'){
                $v1 = $time++;
                $v2 = $time++;
                $f = $time++;
                exec("ffmpeg -loop 1 -i ".$val['snapshot']." -t ".$val['duration']." -r 30 -pix_fmt yuv420p ".$v1.".mp4");
                exec("ffmpeg -i ".$v1.".mp4 -i mute.mp3 -ac 6 -ar 44100 -shortest ".$v2.".mp4");
                exec("ffmpeg -i ".$v2.".mp4 -vcodec libx264 -acodec aac ".$f.".mp4 ");
                unlink($v1.".mp4",$v2.".mp4");
                
                $video[] = $f.".mp4";
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
        
        // $command = "ffmpeg -i ".$video." -i ".$audio." -c copy -map 0:v:0 -map 1:a:0 ".$path;
        // $command = "ffmpeg -i ".$video." -i ".$audio." -map 0:v -map 1:a -c copy -shortest ".$path;
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
        $command    = "ffmpeg -loop 1 -i ".$picture." -i ".$audio." -c:v libx264 -tune stillimage -c:a aac -b:a 192k -pix_fmt yuv420p -shortest ".$path;
        $result     = exec($command);
        
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

    function dump($arr){
        echo "<pre>";
        print_r($arr);exit;
    }