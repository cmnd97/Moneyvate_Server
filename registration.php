<?php
if($_SERVER["REQUEST_METHOD"]=="POST") {
require "conn.php";
createUser();
}

function createUser()
{
	global $connect;
	$user_id = $_POST["user_id"];
	$user_password=$_POST["user_password"];
	$first_name= $_POST["first_name"];
	$last_name=$_POST["last_name"];
	$query="Insert into users(user_id,user_password,first_name,last_name) values ('$user_id','$user_password','$first_name','$last_name');";
	mysqli_query($connect,$query) or die(mysqli_error($connect));
	echo "OK";
	mysqli_close($connect);
}
?>