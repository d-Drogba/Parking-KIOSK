<?php 
require('lib/db_config.php');
    
    $sql = "SELECT * FROM floor";
    $result = mysqli_query($conn, $sql);
    $floor = '';

    while($row = mysqli_fetch_array($result)){
        $escaped_floor_id = htmlspecialchars($row['floor_id']);
        $escaped_floor_status = htmlspecialchars($row['floor_status']);
        $escaped_current_parked_count = htmlspecialchars($row['current_parked_count']);
        $escaped_max_parking_capacity = htmlspecialchars($row['max_parking_capacity']);
        
        if ($escaped_floor_status === 'N'){
            $floor_status = '<strong>현재 만석입니다.</strong>';
        } else {
            $floor_status = '';
        }

        $floor = $floor."<li>
        <a href=\"print_slot.php?floor_id={$escaped_floor_id}\">{$escaped_floor_id}층</a>
         ({$escaped_current_parked_count}/{$escaped_max_parking_capacity}) {$floor_status}</li>";
    }
?>

<?php 
require('view/top.php');
?>
    <body>
        <h1>원하시는 층을 선택하여주세요.</h1>
        <ol><?=$floor?></ol>
<?php 
require('view/bottom.php');
?>