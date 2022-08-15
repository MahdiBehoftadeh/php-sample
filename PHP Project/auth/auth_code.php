<?php
    require "/admin/v1.0.0/database_config.php";
    require $REQUIRE_SQL_QUERY;
    
    $conn1 = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME_ADMIN);
    $conn2 = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME_USER_DATA);
    mysqli_set_charset($conn1, "utf8");
    mysqli_set_charset($conn2, "utf8");
    date_default_timezone_set('Asia/Tehran');


    $phone = $_POST["phone"];
    $code = $_POST["code"];
    $android_id = $_POST["android_id"];
    $manufacturer = $_POST["manufacturer"];
    $model = $_POST["model"];
    $android_version = $_POST["android_version"];
    $local_ip = $_POST["local_ip"];

    $id = null;
    $time = time();
    $token = generateToken(100);
    $array["response"] = null;
    $array["error"] = null;
    $array["phone"] = $phone;
    $array["id"] = null;
    $array["user_type"] = 0;
    $array["token"] = null;

    if (strlen($phone) != 11){
        error(501, "Phone number is not valid.");
    }
    
    if (strlen($code) != 6){
        error(502, "Verification code is not valid.");
    }

    // Checking if code is valid or not expired
    $sql1 = select("SELECT * FROM $TABLE_USERS_VERIFICATION WHERE phone='$phone' AND code=$code AND $time <= expires", $conn1);
    if($sql1){
        
        // Checking if user has already registered
        $array_id = selectValue("SELECT user_id FROM $TABLE_USERS WHERE phone='$phone'", $conn1);
        $id = $array_id["user_id"];
        if($id == null){
            
            // NEW USER - USER TYPE: 0
            // Inserting new user to users table
            $sql2 = insert("INSERT INTO $TABLE_USERS (phone, register_date) VALUES ('$phone', $time)", $conn1);
            if($sql2){
                
                // NEW USER - USER TYPE: 0
                // Getting new users id
                $array_id = selectValue("SELECT user_id FROM $TABLE_USERS WHERE phone='$phone'", $conn1);
                $id = $array_id["user_id"];
                if($id == null){
                    error(503, "Select id failed.");
                }else{
                    
                    // NEW USER - USER TYPE: 0
                    // Checking if new user's sessions table exist
                    $user_table_name = $id . $CONST_USERS_DATA_TABLE_SUFFIX_SESSIONS;
                    $sql3 = select("SELECT * FROM $user_table_name", $conn2);
                    if($sql3){
                        
                        // NEW USER - USER TYPE: 0
                        // User's sessions table already exist and inserting new session to user's sessions table
                        $sql5 = insert("INSERT INTO $user_table_name (android_id, manufacturer, model, android_version, local_ip, login_time, token) VALUES ('$android_id', '$manufacturer', '$model', '$android_version', '$local_ip', $time, '$token')", $conn2);
                        if($sql5){

                            // NEW USER - USER TYPE: 0
                            success(200, 0, $id, $token, $phone);
                        
                        }else{
                            error(504, "Select id failed.");
                        }
                
                    }else{
                        
                        // NEW USER - USER TYPE: 0
                        // User's sessions table doesn't exist and creating new sessions table for new user
                        $sql4 = create("CREATE TABLE $user_table_name (
                                        `session_id` INT NOT NULL AUTO_INCREMENT,
                                        `android_id` VARCHAR(100),
                                        `manufacturer` VARCHAR(50),
	                                    `model` VARCHAR(50),
	                                    `android_version` VARCHAR(20),
	                                    `local_ip` VARCHAR(50),
	                                    `login_time` BIGINT,
	                                    `token` VARCHAR(100) NOT NULL,
                                        PRIMARY KEY (`session_id`)
                                    );", $conn2);
                
                        if($sql4){
                            
                            // NEW USER - USER TYPE: 0
                            // Inserting new session to new user's new sessions table
                            $sql5 = insert("INSERT INTO $user_table_name (android_id, manufacturer, model, android_version, local_ip, login_time, token) VALUES ('$android_id', '$manufacturer', '$model', '$android_version', '$local_ip', $time, '$token')", $conn2);
                            if($sql5){
                        
                                // NEW USER - USER TYPE: 0
                                success(200, 0, $id, $token, $phone);
                        
                            }else{
                                error(505, "Select id failed.");
                            }
                    
                        }else{
                            error(506, "Select id failed.");
                        }
                
                    }
                    
                }
                
            }else{
                error(507, "Inserting user failed.");
            }
            
        }else{
            // OLD USER - USER TYPE: 1
            // Checking if old user's sessions table already exist
            $user_table_name = $id . $CONST_USERS_DATA_TABLE_SUFFIX_SESSIONS;
            $sql3 = select("SELECT * FROM $user_table_name", $conn2);
            if($sql3){

                // OLD USER - USER TYPE: 1
                // Inserting new session to old user's old sessions table
                $sql5 = insert("INSERT INTO $user_table_name (android_id, manufacturer, model, android_version, local_ip, login_time, token) VALUES ('$android_id', '$manufacturer', '$model', '$android_version', '$local_ip', $time, '$token')", $conn2);
                if($sql5){
                    
                    // OLD USER - USER TYPE: 1
                    success(200, 1, $id, $token, $phone);
                        
                }else{
                    error(508, "Select id failed.");
                }
                
            }else{
                
                // OLD USER - USER TYPE: 1
                // Creating new sessions table for old user
                $sql4 = create("CREATE TABLE $user_table_name (
                                `session_id` INT NOT NULL AUTO_INCREMENT,
                                `android_id` VARCHAR(100),
                                `manufacturer` VARCHAR(50),
	                            `model` VARCHAR(50),
	                            `android_version` VARCHAR(20),
	                            `local_ip` VARCHAR(50),
	                            `login_time` BIGINT,
	                            `token` VARCHAR(100) NOT NULL,
                                PRIMARY KEY (`session_id`)
                            );", $conn2);
                
                if($sql4){
                    
                    // OLD USER - USER TYPE: 1
                    // Inserting new session to old user's new sessions table
                    $sql5 = insert("INSERT INTO $user_table_name (android_id, manufacturer, model, android_version, local_ip, login_time, token) VALUES ('$android_id', '$manufacturer', '$model', '$android_version', '$local_ip', $time, '$token')", $conn2);
                    if($sql5){
                        
                    // OLD USER - USER TYPE: 1
                    success(200, 1, $id, $token, $phone);
                        
                    }else{
                        error(509, "Select id failed.");
                    }
                    
                }else{
                    error(510, "Select id failed.");
                }
                
            }
            
        }
        
    }else{
        
        // Code is either not valid or expired
        error(300, "Code is not valid.");
        
    }

    
    function error($response, $error){
        
        $array["response"] = $response;
        $array["error"] = $error;
        $array["user_type"] = null;
        $array["id"] = null;
        $array["token"] = null;
        $array["phone"] = null;
        echo json_encode($array);
        mysqli_close($conn1);
        mysqli_close($conn2);
        return;
        
    }
    
    function success($response, $user_type, $id, $token, $phone){
        
        $array["response"] = $response;
        $array["error"] = $error;
        $array["user_type"] = $user_type;
        $array["id"] = $id;
        $array["token"] = $token;
        $array["phone"] = $phone;
        echo json_encode($array);
        mysqli_close($conn1);
        mysqli_close($conn2);
        return;
        
    }
    
    function generateToken($length = 25) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

?>