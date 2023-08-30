<?php 
require('lib/db_config.php');
require('lib/func.php');

    $filtered = array(
        'car_number'=>mysqli_real_escape_string($conn, $_POST['car_number'])
    );

    $sql = select_customer_id($filtered['car_number']);
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) === 0) {
        // 출차 반려 처리
        echo "등록되지 않은 차량입니다. 나중에 다시 시도해주세요.<br>";
        echo "<a href='calculate_fee.php'>정산하기</a>";
    }
    else {
        // customer_id 출력
        $sql = select_customer_id($filtered['car_number']);
        $row = result_array($conn, $sql);
        $customer_id = $row['customer_id'];

        // customer_id에 exit_time 값 입력
        $sql = update_exit_time($customer_id);
        mysqli_query($conn, $sql);

        // entry_time, exit_time 검색
        $sql = select_time($customer_id);
        $row = result_array($conn, $sql);
        $entry_time = $row['entry_time'];
        $exit_time = $row['exit_time'];

        // parkingslot_id 출력
        $sql = 
            "   SELECT  r.floor_id, r.slot_id
                FROM    reservation AS r
                JOIN    customer ON r.customer_id = customer.customer_id
                WHERE   r.customer_id = {$customer_id}
            ";

        $row = result_array($conn, $sql);
        $floor_id = $row['floor_id'];
        $slot_id = $row['slot_id'];

        // hourly_rate 출력
        $sql = 
            "   SELECT  hourly_rate
                FROM    slot
                WHERE   floor_id = {$floor_id}
                AND     slot_id = {$slot_id}
            ";

        $row = result_array($conn, $sql);
        $hourly_rate = $row['hourly_rate'];

        $parking_info = calculateParkingInfo($entry_time, $exit_time, $hourly_rate);

        if($result === false) {
            echo '정산하는 과정에서 문제가 생겼습니다. 관리자에 문의해주세요.<br>';
            error_log(mysqli_error($conn));
            echo "<a href = 'index.php'>홈으로</a>";
        } else {
            session_start();
            $_SESSION['car_number'] = $filtered['car_number'];
            $_SESSION['parking_info'] = $parking_info;
            $_SESSION['floor_id'] = $floor_id;
            $_SESSION['slot_id'] = $slot_id;

            header("Location: process_exit.php");
        }
    }
    
?>