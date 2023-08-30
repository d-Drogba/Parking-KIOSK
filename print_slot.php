<?php 
require('lib/db_config.php');
    
    $filtered_floor_id = '';

    if (isset($_GET['floor_id'])) {
        $filtered_floor_id = mysqli_real_escape_string($conn, $_GET['floor_id']);
        settype($filtered_floor_id, 'integer');
        }
        
?>

<?php 
require('view/top.php');
?>
    <body>
        <h1>자리 예약 현황</h1>
        <p>현재 보시고 계시는 층은 <strong><?=$filtered_floor_id?>층</strong>입니다.</p>
        <table border='1'>
            <tr>
                <td>자리 번호</td><td>자리 특징</td><td>시간당 요금</td><td>현재 예약상태</td><td>선택</td>
                <?php
                    
                    $sql = 
                        "   SELECT *
                            FROM parkingslot
                            JOIN floor ON parkingslot.slot_floor = floor.floor_number
                            WHERE floor.floor_number = {$filtered_floor_id}
                        ";
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_array($result)){
                        $filtered = array(
                            'parkingslot_id'=>htmlspecialchars($row['parkingslot_id']),
                            'slot_number'=>htmlspecialchars($row['slot_number']),
                            'slot_type'=>htmlspecialchars($row['slot_type']),
                            'hourly_rate'=>htmlspecialchars($row['hourly_rate']),
                            'slot_status'=>htmlspecialchars($row['slot_status'])
                        );
                        $update_link = '';
                        
                        if ($filtered['slot_status'] === 'Y'){
                            $update_link = 
                                "<form action='process_reserve_slot.php' method='POST'
                                onsubmit=\"return confirm('예약하시겠습니까?');\">
                                <input type='hidden' name='parkingslot_id' value='{$filtered['parkingslot_id']}'>
                                <input type='hidden' name='floor_id' value='{$filtered_floor_id}'>
                                <input type='submit'value='예약'>
                                </form>
                                ";
                            }
                ?>
                        <tr>
                            <td><?=$row['slot_number']?></td>
                            <td><?=$row['slot_type']?></td>
                            <td><?=$row['hourly_rate']?></td>
                            <td><?=$row['slot_status']?></td>
                            <td><?=$update_link?></td>
                        </tr>
                    <?php
                    }?>
            </tr>
        </table>
        <a href = 'print_floor.php'>뒤로가기</a>
<?php 
require('view/bottom.php');
?>