<?php 
require "conn.php";
$user_id = $_POST["user_id"];
$fetchAction = $_POST["fetchAction"];

if($fetchAction == "fetchUserName") fetchUserName();
if($fetchAction == "fetchUserTasks") fetchUserTasks();
if($fetchAction == "fetchTagLocations") fetchTagLocations();

function fetchUserName()
{
	global $connect, $user_id;

	$name_query = mysqli_query($connect,"SELECT first_name,last_name from users where USER_ID like '$user_id';");
	$row=mysqli_fetch_assoc($name_query);
 	echo nl2br ($row["first_name"]."\n".$row["last_name"]);
 	mysqli_close($connect);
}

function fetchUserTasks()
{
	global $connect, $user_id;

	$task_query = mysqli_query($connect,"SELECT task_id,tag_id,deadline,status from tasks where USER_ID like '$user_id' AND status IN ('PENDING', 'STARTED') order by deadline;");
	while($row = mysqli_fetch_assoc($task_query))
		{	
		  echo nl2br ($row["task_id"]."\n".$row["tag_id"]."\n".$row["status"]."\n".$row["deadline"]."\n");
	}
	mysqli_close($connect);
}

function fetchTagLocations()
{
	global $connect;
	$loc_query = mysqli_query($connect,"SELECT tag_id,description,loc_lat,loc_long from tags order by tag_id;");
	while($loc = mysqli_fetch_assoc($loc_query))
		{	
		  echo nl2br ($loc["tag_id"]."\n".$loc["description"]."\n".$loc["loc_lat"]."\n".$loc["loc_long"]."\n");
	}
	mysqli_close($connect);

}


?>
