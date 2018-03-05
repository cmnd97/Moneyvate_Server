<?php
$db_name="moneyvate_users";
$mysql_username = "mv_client";
$mysql_password = "moneyvate_user";
$server_name="localhost";
$connect = mysqli_connect($server_name,$mysql_username, $mysql_password,$db_name);
$connect->set_charset("utf8");
?>