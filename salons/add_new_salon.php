<?php
require "/admin/v1.0.0/database_config.php";
require $REQUIRE_SQL_QUERY;

$conn1 = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME_ADMIN);
$conn2 = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME_USER_DATA);
mysqli_set_charset($conn1, "utf8");
mysqli_set_charset($conn2, "utf8");
date_default_timezone_set('Asia/Tehran');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST["user_id"];
    $token = $_POST["token"];
    $logo = $_POST["logo"];
    $bio = $_POST["bio"];
    $name = $_POST["name"];
    $branch = $_POST["branch"];
    $address = $_POST["address"];
    $location_latitude = $_POST["location_latitude"];
    $location_longitude = $_POST["location_longitude"];
    $province_id = $_POST["province_id"];
    $city_id = $_POST["city_id"];
    $establish_date_day = $_POST["establish_date_day"];
    $establish_date_month = $_POST["establish_date_month"];
    $establish_date_year = $_POST["establish_date_year"];
    $scheduled_closes = $_POST["scheduled_closes"];
    $phones = $_POST["phones"];
    $websites = $_POST["websites"];
    $socials = $_POST["socials"];
    $work_hours = $_POST["work_hours"];
    $services = $_POST["services"];

    if (empty($user_id)) {
        error(600, "User id invalid.");
    }

    if (strlen($token) != $CONST_TOKEN_LENGHT) {
        error(601, "Token invalid.");
    }

    if (empty($logo)) {
        error(602, "User id invalid.");
    }

    if (empty($bio)) {
        error(603, "Bio invalid.");
    }

    if (empty($name)) {
        error(604, "Name invalid.");
    }

    if (empty($branch)) {
        error(605, "Branch invalid.");
    }

    if (empty($address)) {
        error(606, "Address invalid.");
    }

    if (empty($location_latitude)) {
        error(607, "Location latitude invalid.");
    }

    if (empty($location_longitude)) {
        error(608, "Location longitude invalid.");
    }

    if (empty($province_id)) {
        error(609, "Province id invalid.");
    }

    if (empty($city_id)) {
        error(610, "City id invalid.");
    }

    if (empty($establish_date_day)) {
        error(611, "Establish date day invalid.");
    }

    if (empty($establish_date_month)) {
        error(612, "Establish date month invalid.");
    }

    if (empty($establish_date_year)) {
        error(613, "Establish date year invalid.");
    }

    if (empty($scheduled_closes)) {
        error(614, "Scheduled closes invalid.");
    }

    if (empty($phones)) {
        error(615, "Phones invalid.");
    }

    if (empty($websites)) {
        error(616, "Websites invalid.");
    }

    if (empty($socials)) {
        error(617, "Socials invalid.");
    }

    if (empty($work_hours)) {
        error(618, "Work hours invalid.");
    }

    if (empty($services)) {
        error(619, "Services invalid.");
    }


    $TABLE_SESSIONS = $user_id . $CONST_USERS_DATA_TABLE_SUFFIX_SESSIONS;
    $sql1 = select("SELECT * FROM $TABLE_SESSIONS WHERE token='$token'", $conn2);
    if ($sql1) {

        $sql1 = insert("INSERT INTO $TABLE_SALONS (admin_id, username, logo, bio, name, branch, address, location_latitude, location_longitude, province_id, city_id, establish_date_day, establish_date_month, establish_date_year, scheduled_closes, phones, websites, socials, work_hours, services) 
        VALUES ($user_id, '$username', '$logo', '$bio', '$name', '$branch', '$address', $location_latitude, $location_longitude, $province_id, $city_id, $establish_date_day, $establish_date_month, $establish_date_year, $scheduled_closes, $phones, $websites, $socials, $work_hours, $services)", $conn1);
        if ($sql1) {

            success(200, null);
        } else {
            error(300, "Inserting new salon to salons table failed.");
        }
    } else {
        error(500, "Token invalid.");
    }
} else {

    error(502, "Request invalid.");
}

function error($code, $error_message)
{

    $array["code"] = $code;
    $array["error_message"] = $error_message;

    echo json_encode($array);
    mysqli_close($conn1);
    mysqli_close($conn2);
    exit();
}

function success($code, $array)
{

    $array["code"] = $code;
    $array["error_message"] = null;

    echo json_encode($array);
    mysqli_close($conn1);
    mysqli_close($conn2);
    exit();
}
