<?php 
    session_start();
    $car_number = $_SESSION['car_number'];
    $parking_info = $_SESSION['parking_info'];
?>

<?php 
require('view/top.php');
?>
    <body>
        <h1>요금 정산</h1>
        <h2>차량번호: <?=$car_number?></h2>
        <p>주차 시간은 <?=$parking_info['hours']?>시간 <?=$parking_info['minutes']?>분 입니다.</p>
        <p>따라서 요금은 <?=$parking_info['parking_fee']?> 원 입니다.</p>
        <p>이용해주셔서 감사합니다.</p>
        <a href="index.php">홈</a>
<?php 
require('view/bottom.php');
?>