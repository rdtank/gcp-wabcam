<?php
include_once 'config.php';

$action = filter_var(trim($_REQUEST['action']), FILTER_SANITIZE_STRING);
if ($action == 'upload') {
    if(isset($_POST['image'])){
        $img = $_POST['image'];
        $filename = date("Y-m-d") . "-" . time() . '.jpeg';
        $filepath = 'uploads/';

        $file=$filepath.$filename;
        if(!is_dir($filepath))
            mkdir($filepath);
        if(isset($_FILES['webcam'])){   
            move_uploaded_file($_FILES['webcam']['tmp_name'], $filepath.$filename);
        }
        $response['code'] = "200";
        //set which bucket to work in
        $bucketName = "internship-cloud-test-bucket";
        // get file for upload testing
        $fileContent = file_get_contents($_POST['image']);
        // NOTE: if 'folder' or 'tree' is not exist then it will be automatically created !
        $cloudPath = 'uploads/' . $filename;
 
        $isSucceed = uploadFile($bucketName, $fileContent, $cloudPath);
 
        if ($isSucceed == true) {
            $response['msg'] = 'SUCCESS: to upload ' . $cloudPath . PHP_EOL;
            // TEST: get object detail (filesize, contentType, updated [date], etc.)
            $response['data'] = getFileInfo($bucketName, $cloudPath);
        } else {
            $response['code'] = "201";
            $response['msg'] = 'FAILED: to upload ' . $cloudPath . PHP_EOL;
        }
    }
    header("Content-Type:application/json");
    echo json_encode($response);
    exit();
}
?>