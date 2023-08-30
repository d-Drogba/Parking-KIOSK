<?php 
require_once('lib/db_config.php');

function result_array($conn, $sql){
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    
    return $row;
}

function update_exit_time($customer_id){
    // customer_id에 exit_time 값 입력
    $sql = 
        "   UPDATE customer
            SET exit_time = NOW()
            WHERE customer.customer_id = {$customer_id}
        ";
    
    return $sql;
}
function select_customer_id($car_number){
    // customer_id 출력
    $sql = 
    "   SELECT  customer_id
        FROM    customer
        WHERE   customer.car_number = '{$car_number}'
        AND     exit_time IS NULL
    ";

    return $sql;
}

function select_time($customer_id){
    // entry_time, exit_time 검색
    $sql = 
        "   SELECT entry_time, exit_time
            FROM customer
            WHERE customer.customer_id = '{$customer_id}'
        ";

    return $sql;
}

function calculateParkingInfo($entry_time, $exit_time, $hourly_rate) {
    $time_difference = strtotime($exit_time) - strtotime($entry_time);
    $minutes_parked = $time_difference / 60;

    $hours = floor($minutes_parked / 60); // 시간 계산
    $minutes = round($minutes_parked % 60); // 분 계산 (반올림)

    // 주차 시간에 따른 주차 요금 계산
    $parking_fee = ceil($minutes_parked / 60) * $hourly_rate;

    return array(
        "hours" => $hours,
        "minutes" => $minutes,
        "parking_fee" => $parking_fee
    );
}

?>

