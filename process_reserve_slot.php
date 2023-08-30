<?php
require('lib/db_config.php');
    
    session_start();
    $customer_id = $_SESSION['customer_id'];
        
    $filtered = array(
        'customer_id'=>mysqli_real_escape_string($conn, $customer_id),
        'floor_id'=>mysqli_real_escape_string($conn, $_POST['floor_id']),
        'slot_id'=>mysqli_real_escape_string($conn, $_POST['slot_id'])
    );

    $sql = "
        INSERT INTO reservation
            (customer_id, floor_id, slot_id)
            VALUES(  
                '{$filtered['customer_id']}',
                '{$filtered['floor_id']}',
                '{$filtered['slot_id']}'
            )
    ";
    mysqli_query($conn, $sql);

    $sql = 
    "   UPDATE  slot
        SET     slot_status = 'N'
        WHERE   floor_id = {$filtered['floor_id']}
        AND     slot_id = {$filtered['slot_id']}
    ";
    mysqli_query($conn, $sql);

    $sql = 
    "   UPDATE floor
        SET current_parked_count = current_parked_count + 1,
            floor_status = 
            CASE
                WHEN current_parked_count = max_parking_capacity THEN 'N'
                ELSE 'Y'
            END
        WHERE floor_id = {$filtered['floor_id']}
    ";
    $result = mysqli_query($conn, $sql);

    if($result === false) {
        echo '예약하는 과정에서 문제가 생겼습니다. 관리자에 문의해주세요';
        error_log(mysqli_error($conn));
        echo "<a href = 'index.php'>홈으로</a>";
    } else {
        echo "정상적으로 예약 되었습니다. 감사합니다. <br>
        <a href = 'index.php'>홈으로</a>";
    }
?>