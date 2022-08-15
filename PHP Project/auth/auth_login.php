<?php
    require "/admin/v1.0.0/database_config.php";
    require $REQUIRE_SQL_QUERY;

    $conn1 = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME_ADMIN);
    $conn2 = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME_USER_DATA);
    mysqli_set_charset($conn1, "utf8");
    mysqli_set_charset($conn2, "utf8");
    date_default_timezone_set('Asia/Tehran');


    $user_id = $_POST["user_id"];
    $token = $_POST["token"];

    $array["code"] = null;
    $array["error_message"] = null;
    $array["image"] = null;
    $array["first_name"] = null;
    $array["last_name"] = null;
    $array["birth_day"] = null;
    $array["birth_month"] = null;
    $array["birth_year"] = null;
    $array["province_id"] = null;
    $array["city_id"] = null;
    

    if (empty($user_id)){
        error(600, "User id invalid.");
    }
    
    if (strlen($token) != $CONST_TOKEN_LENGHT){
        error(601, "Token invalid.");
    }

    $TABLE_SESSIONS = $user_id . $CONST_USERS_DATA_TABLE_SUFFIX_SESSIONS;
    $sql1 = select("SELECT * FROM $TABLE_SESSIONS WHERE token='$token'", $conn2);
    if($sql1){
        
        $array_account_data = selectValue("SELECT image, first_name, last_name, birth_day, birth_month, birth_year, province_id, city_id FROM $TABLE_USERS WHERE user_id=$user_id", $conn1);

        $array["image"] = $array_account_data["image"];
        $array["first_name"] = $array_account_data["first_name"];
        $array["last_name"] = $array_account_data["last_name"];
        $array["birth_day"] = $array_account_data["birth_day"];
        $array["birth_month"] = $array_account_data["birth_month"];
        $array["birth_year"] = $array_account_data["birth_year"];
        $array["province_id"] = $array_account_data["province_id"];
        $array["city_id"] = $array_account_data["city_id"];

        success(200, $array);

    }else{
        error(500, "Token invalid.");
    }

    
    function error($code, $error_message){
        
        $array["code"] = $code;
        $array["error_message"] = $error_message;
        
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
    
    function success($code, $array){
        
        $array["code"] = $code;
        $array["error_message"] = null;

        echo json_encode($array);
        mysqli_close($conn1);
        mysqli_close($conn2);
        exit();
    }

?>