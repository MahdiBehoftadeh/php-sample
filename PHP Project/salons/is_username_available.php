<?php
require "/home/absolons/api.behoftadeh.ir/arayeshgar/admin/v1.0.0/database_config.php";
require $REQUIRE_SQL_QUERY;

$conn1 = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME_ADMIN);

$array["code"] = 500;
$array["message"] = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    
    if(strlen($username) < 4){
        
        $array["code"] = 300;
        $array["message"] = "این نام کاربری قبلا انتخاب شده است نام کاربری دیگری را انتخاب کنید.";
        echo json_encode($array);
        mysqli_close($conn1);
        exit();
        
    }
    
    $is_username_available = !select("SELECT salon_id FROM $TABLE_SALONS WHERE username='$username'", $conn1);
    
    if($is_username_available){
        
        $array["code"] = 200;
        $array["message"] = null;
        echo json_encode($array);
        mysqli_close($conn1);
        exit();

    }else{
        
        $array["code"] = 300;
        $array["message"] = "این نام کاربری قبلا انتخاب شده است نام کاربری دیگری را انتخاب کنید.";
        echo json_encode($array);
        mysqli_close($conn1);
        exit();

    }
    
}

?>