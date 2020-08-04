<html>
    <head>
        <meta charset="UTF-8">
        <title>GCP-Webcam-demo</title>
        <link href="css/style.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    </head>
    <body>
        <footer>
            <div class="container">
                <center><h1>Webcam Capture</h1></center>
            </div>
        </footer>
        <div id = "banner_image">
        <form id="fileUploadForm" method="post" enctype="multipart/form-data">
            <!-- <input type="file" name="file"/>
            <input type="submit" name="upload" value="Upload"/>  -->
            <div class="row"> 
            <div class="col-md-6">
                <div id="my_camera" style="padding-left: 10px;margin-left: 30px;"></div>
                <br/>
                <input type=button value="Take Snapshot" onClick="take_snapshot()">
                <input type="hidden" name="image" class="image-tag">
            </div>
            <div class="col-md-6">
                <div id="results" style="margin-right: 20px;">Image will appear here....</div>
                <br/>
                <input class="btn btn-success" type="submit" name="upload" value="Upload"/>
                <span id="uploadingmsg"></span>
                <br>
                <hr/>
                <strong style="color:#ADFF2F;">R e s p o n s e (JSON)</strong>
                <pre id="json" style="color:#FF00FF;font-weight:bold;font-size:16px;">json response will be shown here</pre>            
                <hr/>
                <div id="output"></div>
            </div>
        </div>
        </form>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script>
            $("#fileUploadForm").submit(function (e) {
                e.preventDefault();
                var action = "requests.php?action=upload";
                $("#uploadingmsg").html("Uploading...");
                var data = new FormData(e.target);
                $.ajax({            
                    type: 'POST',
                    url: action,
                    data: data, 
                    /*THIS MUST BE DONE FOR FILE UPLOADING*/
                    contentType: false,
                    processData: false,
                }).done(function (response) {   
                    $("#uploadingmsg").html("");
                    $("#json").html(JSON.stringify(response, null, 4));
                    //https://storage.googleapis.com/[BUCKET_NAME]/[OBJECT_NAME]
                    $("#output").html('<a href="https://storage.googleapis.com/' + response.data.bucket + '/' + response.data.name + '"><i>https://storage.googleapis.com/' + response.data.bucket + '/' + response.data.name + '</i></a>');
                    if(response.data.contentType === 'image/jpeg' || response.data.contentType === 'image/jpg' || response.data.contentType === 'image/png') {
                        $("#output").append('<br/><img src="https://storage.googleapis.com/' + response.data.bucket + '/' + response.data.name + '"/>');
                    }
                }).fail(function (data) {
                    //any message
                });
            });  
        </script>
        <script language="JavaScript">
            Webcam.set({
            width: 490,
            height: 390,
            image_format: 'jpeg',
            jpeg_quality: 100,
        });
        Webcam.attach( '#my_camera' );
        function take_snapshot() {
        Webcam.snap( function(data_uri) {
            $(".image-tag").val(data_uri);
            document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
        } );
    }
</script>
    </body>
</html>