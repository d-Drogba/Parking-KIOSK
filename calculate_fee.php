<?php 
require('view/top.php');
?>
    <body>
        <h1>요금 정산</h1>
        <form action="process_calculate_fee.php" method="POST">
            <p><input type="text" name="car_number" placeholder="12가3456"></p>
            <p><input type="submit" value="다음"></p>
        </form>
<?php 
require('view/bottom.php');
?>