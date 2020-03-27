

<div>
    <button class="btn-start-recording">Start Recording</button>
    <button class="btn-stop-recording" disabled="disabled">Stop Recording</button>

    <video class="my-preview"  autoplay></video>
</div>

<div>
    <button class="btn-start-recording">Start Recording</button>
    <button class="btn-stop-recording" disabled="disabled">Stop Recording</button>

    <video class="my-preview"  autoplay></video>
</div>





<script src="https://cdn.webrtc-experiment.com/RecordRTC.js"></script>
<script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script type="text/javascript">
    var video;
    var recorder;
    var stream;

    $(".btn-start-recording").click(async function(){
        $(this)[0].disabled = true;
        parent = $(this).parent('div');
        video = $(parent).children('.my-preview')[0];

        stream = await navigator.mediaDevices.getUserMedia({
            video: true, 
            audio: true
        });
        
        setSrcObject(stream, video);
        video.play();
        video.muted = true;

        recorder = new RecordRTCPromisesHandler(stream,{
            type: 'video'
        });
        
        recorder.startRecording();

        $(parent).children('.btn-stop-recording')[0].disabled= false;
    });

    $('.btn-stop-recording').click(async function(){
        btn = this;
        $(btn)[0].disabled = true;
        parent = $(this).parent('div');

        await recorder.stopRecording();
        let blob = await recorder.getBlob();
        url = await upload_blob(blob,btn);
        
        stream.stop();

        $(parent).children('.btn-start-recording')[0].disabled= false;
    });

    function upload_blob(blob,btn){
        var vdo = $(btn).parent('div').children('.my-preview');
        var data = new FormData();
        data.append('file', blob);
        $.ajax({
            url: "{{ route('upload_blob_video') }}",
            type:"post",
            data: data,
            contentType: false,
            processData: false,
            success: function(data) {
               $(vdo).html('<source src="{{ URL('/') }}/'+data+'">');
               $(vdo).attr('controls','1');
            }    
        });
    }
</script>
<script>
    
    /*var recorder;
    var video;
    $(".btn-start-recording").click(function(){
        $(this)[0].disabled = true;
        parent = $(this).parent('div');
        video = $(parent).children('.my-preview')[0];
       
        navigator.mediaDevices.getUserMedia({
            audio: true, 
            video: true
        }).then(function(stream) {
            let recorder = RecordRTC(stream, {
                type: 'video/webm'
            });

            recorder.startRecording().then(function() {
                console.log('Recording video ...');
            }).catch(function(error) {
                console.log('Cannot start video recording: ', error);
            });

            recorder.stream = stream;
            
            $(parent).children('.btn-stop-recording')[0].disabled= false;
        }).catch(function(error) {
            console.error("Cannot access media devices: ", error);
        });
    });
    
    $('.btn-stop-recording').click(function(){
        $(this)[0].disabled = true;
        parent = $(this).parent('div');
        recorder.stream.stop();

        recorder.stopRecording().then(function() {
            let blob = recorder.getBlob();
            alert(blob);
            console.log(blob);
            url      = upload_blob(blob);
            video.innerHTML = '<source src="'+url+'">';
            video.play();
            video.muted = false;
            $(parent).children('.btn-start-recording')[0].disabled= false;
        }).catch(function(error){
            alert(error);
        });
       
    });

    function upload_blob(blob){
        var data = new FormData();
        data.append('file', blob);
        $.ajax({
            url: "{{ route('upload_blob') }}",
            type:"post",
            data: data,
            contentType: false,
            processData: false,
            success: function(data) {
                alert(data);
                return data;
            }    
        });
    }*/
</script>