<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>주차 키오스크</title>
    </head>
    <body>
        <h1>고객 등록</h1>
        <form action="process_register.php" method="POST">
            <p><input type="text" name="car_number" placeholder="12가3456"></p>
            <p><input type="text" name="phone_number" placeholder="010-5047-1355"></p>
            <p><input type="submit" value="다음"></p>
        </form>
        <p>휴대폰 번호는 원치 않을 시, 적지 않아도 괜찮습니다.</p>
<?php 
require('view/bottom.php');
?>