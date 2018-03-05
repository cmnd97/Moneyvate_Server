
<?php 
require "conn.php";
$user_id= $_POST["user_id"];
$user_pass = $_POST["user_password"];
$mysql_qry = "select * from users where USER_ID like '$user_id' and USER_PASSWORD like '$user_pass';";
$result = mysqli_query($connect ,$mysql_qry);
if(mysqli_num_rows($result) > 0)
echo "OK";
else 
echo "Login failed. Please try again.";
 
?>
