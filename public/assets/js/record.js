var msg_box_outro = document.getElementById( 'msg_box_outro' ),
button_outro = document.getElementById( 'recorder_outro' ),    
canvas_outro = document.getElementById( 'canvas_outro' ),
lang_outro = {
    'mic_error': 'Microphone not found.', //Ошибка доступа к микрофону
    'press_to_start': 'Press to start recording', //Нажмите для начала записи
    'recording': 'Recording', //Запись
    'play': 'Play', //Воспроизвести
    'stop': 'Stop', //Остановить
    'download': '', //Скачать
    'use_https': 'This application in not working over insecure connection. Try to use HTTPS'
},
time;


msg_box_outro.innerHTML = lang_outro.press_to_start;

if ( navigator.mediaDevices === undefined ) {
    navigator.mediaDevices = {};
}


if ( navigator.mediaDevices.getUserMedia === undefined ) {
    navigator.mediaDevices.getUserMedia = function ( constrains ) {
        var getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia
        if ( !getUserMedia )  {
            return Promise.reject( new Error( 'getUserMedia is not implemented in this browser' ) );
        }

        return new Promise( function( resolve, reject ) {
            getUserMedia.call( navigator, constrains, resolve, reject );
        } );
    }
}


if ( navigator.mediaDevices.getUserMedia ) {
        var btn_status_outro = 'inactive',
        mediaRecorder_outro,
        chunks_outro = [],
        audio_outro = new Audio(),
        mediaStream_outro,
        audioSrc_outro,
        type_outro = {
            'type': 'audio/ogg,codecs=opus'
        },
        ctx_outro,
        analys_outro,
        blob_outro;

        if($("#recorder_outro").length != 0) {
            button_outro.onclick = function () {
                if ( btn_status_outro == 'inactive' ) {
                    play_video_outro()
                    setTimeout(function(){ start_outro(); }, 3000);
                } else if ( btn_status_outro == 'recording' ) {
                    clear_interval_outro();
                    stop_outro();
                }
        }
    }

    function parseTime_outro( sec ) {
        var h = parseInt( sec / 3600 );
        var m = parseInt( sec / 60 );
        var sec = sec - ( h * 3600 + m * 60 );

        h = h == 0 ? '' : h + ':';
        sec = sec < 10 ? '0' + sec : sec;

        return h + m + ':' + sec;
    }


    function start_outro() {
        navigator.mediaDevices.getUserMedia( { 'audio': true } ).then( function ( stream ) {
            mediaRecorder_outro = new MediaRecorder( stream );
            mediaRecorder_outro.start();

            button_outro.classList.add( 'recording' );
            btn_status_outro = 'recording';

            msg_box_outro.innerHTML = lang_outro.recording;
          
            if ( navigator.vibrate ) navigator.vibrate( 150 );

            time = Math.ceil( new Date().getTime() / 1000 );


            mediaRecorder_outro.ondataavailable = function ( event ) {
                chunks_outro.push( event.data );
            }

            mediaRecorder_outro.onstop = function () {
                stream.getTracks().forEach( function( track ) { track.stop() } );

                blob_outro = new Blob( chunks_outro, type );
                audioSrc = window.URL.createObjectURL( blob_outro );

                audio.src = audioSrc;
                upload_blob_outro(blob_outro);
                chunks_outro = [];
            }   

            
            
        } ).catch( function ( error ) {console.log(error);
            if ( location.protocol != 'https:' ) {
              msg_box_outro.innerHTML = lang_outro.mic_error + '<br>'  + lang_outro.use_https;
            } else {
              msg_box_outro.innerHTML = lang_outro.mic_error; 
            }
            button_outro.disabled = true;
        });
    }

    function stop_outro() {
        mediaRecorder_outro.stop();
        button_outro.classList.remove( 'recording' );
        btn_status_outro = 'inactive';
      
        if ( navigator.vibrate ) navigator.vibrate( [ 200, 100, 200 ] );

        var now = Math.ceil( new Date().getTime() / 1000 );

        var t = parseTime( now - time );

        msg_box_outro.innerHTML = '<a href="#" onclick="play(); return false;" class="txt_btn">' + lang_outro.play + ' (' + t + 's)</a><br>' +
                            '<a href="#" onclick="save(); return false;" class="txt_btn">' + lang_outro.download + '</a>'
    }

    

    function play_outro() {
        play_video_to_preview_outro();
        audio_outro.play();
        msg_box.innerHTML = '<a href="#" onclick="pause(); return false;" class="txt_btn">' + lang_outro.stop + '</a><br>' +
                            '<a href="#" onclick="save(); return false;" class="txt_btn">' + lang_outro.download + '</a>';
    }

    function pause_outro() {
        audio_outro.pause();
        audio_outro.currentTime = 0;
        msg_box_outro.innerHTML = '<a href="#" onclick="play(); return false;" class="txt_btn">' + lang_outro.play + '</a><br>' +
                            '<a href="#" onclick="save(); return false;" class="txt_btn">' + lang_outro.download + '</a>'
    }

    function roundedRect_outro(ctx, x, y, width, height, radius, fill) {
        ctx.beginPath();
        ctx.moveTo(x, y + radius);
        ctx.lineTo(x, y + height - radius);
        ctx.quadraticCurveTo(x, y + height, x + radius, y + height);
        ctx.lineTo(x + width - radius, y + height);
        ctx.quadraticCurveTo(x + width, y + height, x + width, y + height - radius);
        ctx.lineTo(x + width, y + radius);
        ctx.quadraticCurveTo(x + width, y, x + width - radius, y);
        ctx.lineTo(x + radius, y);
        ctx.quadraticCurveTo(x, y, x, y + radius);
        
        ctx.fillStyle = fill;
        ctx.fill();
    }

    function save_outro() {
        var a = document.createElement( 'a' );
        a.download = 'record.ogg';
        a.href = audioSrc_outro;
        document.body.appendChild( a );
        a.click();

        document.body.removeChild( a );
    }

} else {
    if ( location.protocol != 'https:' ) {
      msg_box_outro.innerHTML = lang_outro.mic_error + '<br>'  + lang_outro.use_https;
    } else {
      msg_box_outro.innerHTML = lang_outro.mic_error; 
    }
    if($("#recorder_outro").length != 0) {
        button_outro.disabled = true;
    }
}




function play_video_outro(){
    $('#counter_outro').text(3);
    setTimeout(function(){ $('#counter_outro').text(2); },1000);
    setTimeout(function(){ $('#counter_outro').text(1); },2000);

    setTimeout(function(){ 
        $('#counter_outro').hide();
        start_counter_outro();
    },3000);
}

function start_counter_outro(){
    if($('#counter_box_outro').text()){
        j = 0;
        $('#counter_box_outro').text(j)
        $('#counter_box_outro').show();
    }else{
        j = 0;
        // $('#counter_box').show();
        $('#counter_box_outro').text(j++);
        setInterval(function(){ $('#counter_box_outro').text(j++) },1000);
    }
}

function retake_outro(){
    if ( btn_status_outro == 'recording' ){
        clear_interval_outro();
        stop_outro();  
    }   
    msg_box.innerHTML = '';
    $('#outro_audio').val('');
    $('#counter_outro').show();
    $('#counter_outro').text('');
    $('#recorder_outro').removeAttr('disabled');
}

function clear_interval_outro(){
    $('#counter_box_outro').hide();
}

function upload_blob_outro(blob){
    var data = new FormData();
    data.append('file', blob);
    hold_on();
    $.ajax({
        url: "{{ route('upload_blob') }}",
        type:"post",
        data: data,
        contentType: false,
        processData: false,
        success: function(data) {
            $('#outro_audio').val(data);
            hold_off();
        }    
    });
}