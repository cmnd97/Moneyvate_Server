<?php
require "conn.php";

date_default_timezone_set('Europe/Bucharest');
$server_date = date('Y-m-d');

$user_id = $_POST["user_id"];		
$tag_id = $_POST["tag_id"];
$deadline = $_POST["deadline"];
$deadline_date=date('Y-m-d', strtotime($deadline));

if(!($server_date == $deadline_date))
checkIfTaskOnDate();
else echo "Tasks can not be created for the current day.";

function checkIfTaskOnDate()
{
	global $connect, $user_id, $deadline_date, $busyday, $busytaskid;
	$busyday=0;
	$task_query = mysqli_query($connect,"SELECT task_id,deadline from tasks where USER_ID like '$user_id' order by deadline;");
	while($compare_task = mysqli_fetch_assoc($task_query))
		{	
			$ct_date= date('Y-m-d', strtotime($compare_task['deadline']));
					if($ct_date == $deadline_date)
					{	$busyday=1; $busytaskid=$compare_task["task_id"]; break;}
				
	}
			if($busyday==0)
				createTask();
			else
				echo "There is already task " . $busytaskid . " on this day. Please choose another day.";

	mysqli_close($connect);
	
}

function createTask()
{	global $connect, $deadline, $user_id, $tag_id;

	$task_query = mysqli_query($connect,"INSERT INTO tasks(user_id,tag_id,deadline) VALUES ('$user_id','$tag_id','$deadline');");
	echo "Task created successfully!";

}

?>