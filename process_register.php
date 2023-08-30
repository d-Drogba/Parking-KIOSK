<?php 
require('lib/db_config.php');
require('lib/func.php');
    
    $filtered = array(
        'car_number'=>mysqli_real_escape_string($conn, $_POST['car_number']),
        'phone_number'=>mysqli_real_escape_string($conn, $_POST['phone_number'])
    );

    $sql = select_customer_id($filtered['car_number']);
    $result = mysqli_query($conn, $sql);
    if ($result->num_rows > 0) {
        // 등록 반려 처리
        echo "이미 등록된 차량입니다. 나중에 다시 시도해주세요.<br>";
        echo "<a href='register.php'>차량 등록</a>";
    }
    else {
        $sql = 
        "   INSERT INTO customer
                (car_number, phone_number, entry_time)
            VALUES(
                '{$filtered['car_number']}',
                '{$filtered['phone_number']}',
                NOW()
            )
        ";
        mysqli_query($conn, $sql);
    
        $sql = select_customer_id($filtered['car_number']);
        $row = result_array($conn, $sql);
    
        if($row === false) {
            echo '저장하는 과정에서 문제가 생겼습니다. 관리자에 문의해주세요';
            error_log(mysqli_error($conn));
        } else { 
            session_start();
            $_SESSION['customer_id'] = $row['customer_id'];
            header('Location: print_floor.php');
        }
    }
    
?>