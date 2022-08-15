<?php

    require "/admin/v1.0.0/database_config.php";
    require $REQUIRE_SQL_QUERY;
    
    $conn1 = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME_ADMIN);
    $conn2 = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME_USER_DATA);
    mysqli_set_charset($conn1, "utf8");
    mysqli_set_charset($conn2, "utf8");
    date_default_timezone_set('Asia/Tehran');
    
    $time = time();

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $id = $_POST['id'];
        $photo = $_POST['photo'];

        $path = "/profile_images/".$id.".jpeg";

        if (file_put_contents($path, base64_decode($photo))) {
            
            $array["code"] = 200;
            $array['error'] = null;

        }else {
        
            $array["code"] = 500;
            $array['error'] = "Uploading Image Failed.";
        
        }

    }else{
    
        $array["code"] = 501;
        $array['error'] = "Request unvalid.";
    
    }

    echo json_encode($array);
    mysqli_close($conn);

?>