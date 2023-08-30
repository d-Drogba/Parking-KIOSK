<?php
require('lib/db_config.php');
    
    session_start();
    $floor_id = $_SESSION['floor_id'];
    $slot_id = $_SESSION['slot_id'];
    
    // 자리정보 회복과정
    $sql = 
    "   UPDATE  slot
        SET     slot_status = 'Y'
        WHERE   floor_id = $floor_id
        AND     slot_id = $slot_id
    ";
    mysqli_query($conn, $sql);

    $sql = 
        "   SELECT  floor.floor_id
            FROM    slot
            JOIN    floor ON slot.floor_id = floor.floor_id
            WHERE   floor_id = $floor_id
            AND     slot_id = $slot_id
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
        WHERE floor_id = {$row['floor_id']};
    ";
    mysqli_query($conn, $sql);

    // reservation_id 검색
    $sql = 
        "   SELECT  reservation_id
            FROM    reservation
            WHERE   floor_id = $floor_id
            AND     slot_id = $slot_id
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
        echo "<a href = 'index.php'>홈으로</a>";
    } else {
        header("Location: exit.php");
    }

?>

