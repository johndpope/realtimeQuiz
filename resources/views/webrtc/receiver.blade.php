<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="{{asset('webrtc/style.css')}}">
    </head>
    <body>
        <div>
            <input placeholder="Enter username..."
                    type="text"
                    id="username-input" /><br>
            <button onclick="joinCall()">Join Call</button>
        </div>
        <div id="video-call-div">
            <video muted id="local-video" autoplay></video>
            <video id="remote-video" autoplay></video>
            <div class="call-action-div">
                <button onclick="muteVideo()">Mute Video</button>
                <button onclick="muteAudio()">Mute Audio</button>
            </div>
        </div>
        <script src="{{asset('webrtc/receiver.js')}}"></script>
    </body>

</html>
