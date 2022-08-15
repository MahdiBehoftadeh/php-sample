<?php
    require "/admin/v1.0.0/database_config.php";
    require $REQUIRE_SQL_QUERY;
    
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME_ADMIN);
    mysqli_set_charset($conn, "utf8");
    date_default_timezone_set('Asia/Tehran');


    $phone = $_POST["phone"];
    $security_token = $_POST["security_token"];
    
    $code = rand(100000,999999);
    $created = time();//seconds
    $expires = $created + (10 * 60);//seconds

    if (strlen($phone) != 11){
        
        $array["response"] = 503;
        $array["error"] = "Phone number is not valid.";
        echo json_encode($array);
        mysqli_close($conn);
        return;
        
    }

    $sql1 = select("SELECT * FROM $TABLE_USERS_VERIFICATION WHERE phone='$phone'", $conn);

    if ($sql1){
        
        $sql2 = update("UPDATE $TABLE_USERS_VERIFICATION SET code='$code', created='$created', expires=$expires WHERE phone='$phone'", $conn);
		if ($sql2) {
            $array["response"] = 200;
            $array["error"] = null;
		}else{
            $array["response"] = 501;
            $array["error"] = "There was a problem updating row.";
		}

        $message = "Welcome back,\nYour verification code is ".$code."\nThis verification code is only valid for 10 minutes.\n\nhttps://behoftadeh.ir\n".$security_token.$code;
   
    }else{

        $sql3 = insert("INSERT INTO $TABLE_USERS_VERIFICATION (phone, code, created, expires)VALUES ('$phone', '$code', $created, $expires)", $conn);
		if ($sql3) {
            $array["response"] = 200;
            $array["error"] = null;
		}else{
            $array["response"] = 502;
            $array["error"] = "There was a problem inserting row.";
		}

        $message = "Welcome,\nYour verification code is ".$code."\nThis verification code is only valid for 10 minutes.\n\nhttps://behoftadeh.ir\n".$security_token.$code;

    }
    
    echo json_encode($array);

    mysqli_close($conn);

?>