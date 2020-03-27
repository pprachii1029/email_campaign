<?php

class FFMpeg{
    protected $temp_file;
    protected $final;
    protected $TEMP_PATH;
    protected $APP_PATH;

    function  genrate(){
        $this->temp_file    = time().rand(1234567,9999999);
        $this->final        = time().rand(1234,9999).".mp4";
        $this->final_pic    = time().rand(1234,9999).".jpg";
        $this->final_mp3    = time().rand(1234,9999).".mp3";
        $this->APP_PATH="/var/www/html/email_campaign/";
        $this->TEMP_PATH="/var/www/html/email_campaign/public/template/";
    }

    protected function destroy($file,$ext,$q){
        for($i=0;$i<$q;$i++){
            try{
                unlink($this->TEMP_PATH.$file--.$ext);
            }catch(Exception $e){
                echo "Error";
            }
        }
    }

    public function splitVideo($video,$start,$duration,$mute){
        $this->genrate();
        if($mute == 1){
            exec("ffmpeg -i ".$this->APP_PATH.$video." -ss ".$start." -t ".$duration." -async 1 -an ".$this->TEMP_PATH.$this->temp_file.".mp4");
            exec("ffmpeg -i ".$this->TEMP_PATH.$this->temp_file.".mp4 -f lavfi -i anullsrc -shortest ".$this->TEMP_PATH.++$this->temp_file.".mp4");
            exec("ffmpeg -i ".$this->TEMP_PATH.$this->temp_file.".mp4 -c:v libx264 -c:a aac -b:a 192k ".$this->TEMP_PATH.++$this->temp_file.".mp4");
            exec("ffmpeg -i ".$this->TEMP_PATH.$this->temp_file.".mp4 -c:a aac -ar 48000 -ac 2 -c:v copy -video_track_timescale 600 ".$this->TEMP_PATH.$this->final);
            
            $this->destroy($this->temp_file,".mp4",3);
        }else{
            exec("ffmpeg -i ".$this->APP_PATH.$video." -ss ".$start." -t ".$duration." -async 1 ".$this->TEMP_PATH.$this->temp_file.".mp4");
            exec("ffmpeg -i ".$this->TEMP_PATH.$this->temp_file.".mp4 -c:v libx264 -c:a aac -b:a 192k ".$this->TEMP_PATH.++$this->temp_file.".mp4");
            exec("ffmpeg -i ".$this->TEMP_PATH.$this->temp_file.".mp4 -c:a aac -ar 48000 -ac 2 -c:v copy -video_track_timescale 600 ".$this->TEMP_PATH.$this->final);

            $this->destroy($this->temp_file,".mp4",2);
        }
        return 'public/template/'.$this->final;
    }

    public function photoToVideo($pic,$duration){
        $this->genrate();
        exec("ffmpeg -loop 1 -i ".$this->APP_PATH.$pic." -f lavfi -i anullsrc -c:v libx264 -t ".$duration." -vf scale=1280:720 ".$this->TEMP_PATH.$this->temp_file.".mp4");
        exec("ffmpeg -i ".$this->TEMP_PATH.$this->temp_file.".mp4 -c:v libx264 -c:a aac -b:a 192k ".$this->TEMP_PATH.++$this->temp_file.".mp4");
        exec("ffmpeg -i ".$this->TEMP_PATH.$this->temp_file.".mp4 -c:a aac -ar 48000 -ac 2 -c:v copy -video_track_timescale 600 ".$this->TEMP_PATH.$this->final);
        
        $this->destroy($this->temp_file,".mp4",2);
        
        return 'public/template/'.$this->final;
    }

    public function concatVideos($video){
        $this->genrate();
        $text = "";
        foreach ($video as $value) {
            $text .= "file '".$this->APP_PATH.$value."'\n";
        }
        
        $file = $this->TEMP_PATH.$this->temp_file.".txt";
        file_put_contents($file,$text);
        chmod($file, 0777);
        exec("ffmpeg -safe 0 -f concat -i ".$file." -c copy ".$this->TEMP_PATH.$this->final);
        
        try{
            unlink($file);
        }catch(Exception $e){

        }


        return 'public/template/'.$this->final;
    }

    public function convertAndConcatVideos($video){
        $this->genrate();
        $text = "";
        
        foreach ($video as $value) {
            exec("ffmpeg -i ".$this->APP_PATH.$value." -c:v libx264 -c:a aac -b:a 192k ".$this->TEMP_PATH.++$this->temp_file.".mp4");
            exec("ffmpeg -i ".$this->TEMP_PATH.$this->temp_file.".mp4 -c:a aac -ar 48000 -ac 2 -c:v copy -video_track_timescale 600 ".$this->TEMP_PATH.++$this->temp_file.".mp4");
            $text .= "file '".$this->TEMP_PATH.$this->temp_file.".mp4'\n";
        }
        
        $file = $this->TEMP_PATH.$this->temp_file.".txt";
        file_put_contents($file,$text);
        chmod($file, 0777);
        exec("ffmpeg -safe 0 -f concat -i ".$file." -c copy ".$this->TEMP_PATH.$this->final);
        
        try{
            unlink($file);
        }catch(Exception $e){

        }


        return 'public/template/'.$this->final;
    }

    public function addAudioInVideo($video,$audio){
        $this->genrate();
        exec('ffmpeg -i '.$this->APP_PATH.$video.' -i '.$this->APP_PATH.$audio.' -filter_complex " [1:0] apad " -shortest '.$this->TEMP_PATH.$this->final);

        return 'public/template/'.$this->final;
    }

    public function addAudioInFinalVideo($video,$audio){
        $this->genrate();
        exec("ffmpeg -i ".$this->APP_PATH.$video." ".$this->TEMP_PATH.$this->temp_file.".mp3");
        exec('ffmpeg -y -i '.$this->TEMP_PATH.$this->temp_file.'.mp3 -i '.$this->APP_PATH.$audio.' -filter_complex "[0:0][1:0] amix=inputs=2:duration=longest" -c:a libmp3lame '.$this->TEMP_PATH.++$this->temp_file.'.mp3');
        exec('ffmpeg -i '.$this->APP_PATH.$video.' -i '.$this->TEMP_PATH.$this->temp_file.'.mp3 -filter_complex " [1:0] apad " -shortest '.$this->TEMP_PATH.$this->final);

        $this->destroy($this->temp_file,".mp3",2);

        return 'public/template/'.$this->final;
    }

    public function captureThumb($vdo){
        $this->genrate();
        exec("ffmpeg -ss 00:00:03 -i ".$this->APP_PATH.$vdo." -vframes 1 ".$this->TEMP_PATH.$this->final_pic);
        return 'public/template/'.$this->final_pic;
    }
    
    public function addAudioInPhoto($pic,$audio){
        $this->genrate();
        exec("ffmpeg -loop 1 -i ".$this->APP_PATH.$pic." -i ".$this->APP_PATH.$audio." -c:v libx264 -tune stillimage -c:a aac -b:a 192k -shortest ".$this->TEMP_PATH.++$this->temp_file.".mp4");
        exec("ffmpeg -i ".$this->TEMP_PATH.$this->temp_file.".mp4 -c:v libx264 -c:a aac -b:a 192k ".$this->TEMP_PATH.++$this->temp_file.".mp4");
        exec("ffmpeg -i ".$this->TEMP_PATH.$this->temp_file.".mp4 -c:a aac -ar 48000 -ac 2 -c:v copy -video_track_timescale 600 ".$this->TEMP_PATH.$this->final);
        
        $this->destroy($this->temp_file,".mp4",2);

        return 'public/template/'.$this->final;
    }

    public function videoToAudio($video){
        $this->genrate();
        exec("ffmpeg -i ".$this->APP_PATH.$video." -b:a 192K -vn ".$this->TEMP_PATH.$this->final_mp3);
        
        return 'public/template/'.$this->final_mp3;
    }

    public function addVideoOverVideo($video,$mark){
        $this->genrate();
        exec("ffmpeg -i ".$this->APP_PATH.$mark." -vf scale=in_w/2:in_h/2 ".$this->TEMP_PATH.$this->temp_file.".mp4");
        exec('ffmpeg -i '.$this->APP_PATH.$video.' -i '.$this->TEMP_PATH.$this->temp_file.'.mp4 -filter_complex "[0:v][1:v] overlay=x=(main_w-overlay_w):y=(main_h-overlay_h)" -pix_fmt yuv420p -c:a copy '.$this->TEMP_PATH.$this->final);

        $this->destroy($this->temp_file,".mp4",2);

        return 'public/template/'.$this->final;
    }
}

