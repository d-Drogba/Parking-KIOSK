<?php
require('lib/db_config.php');
    
    session_start();
    $parkingslot_id = $_SESSION['parkingslot_id'];

    // 자리정보 회복과정
    $sql = 
    "   UPDATE parkingslot
        SET slot_status = 'Y'
        WHERE parkingslot_id = $parkingslot_id
    ";
    mysqli_query($conn, $sql);

    $sql = 
        "   SELECT floor.floor_number
            FROM parkingslot
            JOIN floor ON parkingslot.slot_floor = floor.floor_number
            WHERE parkingslot_id = $parkingslot_id
        ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    $sql = 
    "   UPDATE floor
        SET current_parked_count = current_parked_count - 1,
            floor_status = 
            CASE
                WHEN current_parked_count < max_parking_capacity THEN 'Y'
                ELSE 'N'
            END
        WHERE floor_id = {$row['floor_number']};
    ";
    mysqli_query($conn, $sql);

    // reservation_id 검색
    $sql = 
        "   SELECT  reservation_id
            FROM    reservation
            WHERE   reservation.parkingslot_id = $parkingslot_id
        ";
    
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result); 

    // reservation_id table 삭제
    $sql = 
        "   DELETE  FROM    reservation
            WHERE   reservation_id = {$row['reservation_id']}
        ";
    
    $result = mysqli_query($conn, $sql);

    if($result === false) {
        echo '출차 처리과정에서 문제가 생겼습니다. 관리자에 문의해주세요';
        error_log(mysqli_error($conn));
        echo "<a href = 'index.html'>홈으로</a>";
    } else {
        header("Location: exit.php");
    }

?>

