<?php
    require "/admin/v1.0.0/database_config.php";
    require $REQUIRE_SQL_QUERY;
    
    $conn1 = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME_ADMIN);
    $conn2 = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME_USER_DATA);
    mysqli_set_charset($conn1, "utf8");
    mysqli_set_charset($conn2, "utf8");
    date_default_timezone_set('Asia/Tehran');
    
    $time = time();
      

    if($_SERVER["REQUEST_METHOD"] == "POST") {

        $user_id = $_POST["user_id"];
        $token = $_POST["token"];
        $image = $_POST["image"];
        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        $birth_day = $_POST["birth_day"];
        $birth_month = $_POST["birth_month"];
        $birth_year = $_POST["birth_year"];
        $province_id = $_POST["province_id"];
        $city_id = $_POST["city_id"];
        
        if(empty($user_id)){
            error(600, "User id invalid.");
        }

        if(empty($token)){
            error(601, "Token invalid.");
        }

        if(empty($first_name)){
            error(602, "First name invalid.");
        }

        if(empty($last_name)){
            error(603, "Last name invalid.");
        }

        if(empty($birth_day)){
            error(604, "Birth day invalid.");
        }

        if(empty($birth_month)){
            error(605, "Birth month invalid.");
        }

        if(empty($birth_year)){
            error(606, "Birth year invalid.");
        }

        if(empty($province_id)){
            error(607, "Province id invalid.");
        }
        
        if(empty($city_id)){
            error(608, "City id invalid.");
        }
        
        $SESSIONS_TABLE = $user_id . $CONST_USERS_DATA_TABLE_SUFFIX_SESSIONS;
        $sql1 = select("SELECT * FROM $SESSIONS_TABLE WHERE token='$token'", $conn2);
        if(!$sql1){
            error(609, "Token invalid.");
        }
        

        $home_path = "/home/";
        $directory_path = "/profile_images/" . $user_id . "_" . $time . ".jpeg";
        $url_path = "https://" . $directory_path;
        $file_path = $home_path . $directory_path;

        if (file_put_contents($file_path, base64_decode($image))) {
            
            $sql = update("UPDATE $TABLE_USERS SET image='$url_path', first_name='$first_name', last_name='$last_name', birth_day=$birth_day, birth_month=$birth_month, birth_year=$birth_year, province_id=$province_id, city_id=$city_id  WHERE user_id=$user_id", $conn1);

            if($sql){
                
                success(200, $user_id, $url_path, $first_name, $last_name, $birth_day, $birth_month, $birth_year, $province_id, $city_id);
                
            }else{
                
                error(500, "Updating data in user's table failed.");
                
            }

        }else {
            
            error(501, "Uploading image file failed.");
        
        }

    }else{
    
        error(502, "Request invalid.");
    
    }


    function success($code, $user_id, $image, $first_name, $last_name, $birth_day, $birth_month, $birth_year, $province_id, $city_id){
        
        $array["code"] = $code;
        $array["error"] = null;
        $array["user_id"] = $user_id;
        $array["image"] = $image;
        $array["first_name"] = $first_name;
        $array["last_name"] = $last_name;
        $array["birth_day"] = $birth_day;
        $array["birth_month"] = $birth_month;
        $array["birth_year"] = $birth_year;
        $array["province_id"] = $province_id;
        $array["city_id"] = $city_id;
        
        echo json_encode($array);

        mysqli_close($conn1);
        mysqli_close($conn2);

        exit();
    }

    function error($code, $error){
        
        $array["code"] = $code;
        $array["error"] = $error;
        $array["user_id"] = null;
        $array["image"] = null;
        $array["first_name"] = null;
        $array["last_name"] = null;
        $array["birth_day"] = null;
        $array["birth_month"] = null;
        $array["birth_year"] = null;
        $array["province_id"] = null;
        $array["city_id"] = null;
        
        echo json_encode($array);

        mysqli_close($conn1);
        mysqli_close($conn2);

        exit();
    }

?>