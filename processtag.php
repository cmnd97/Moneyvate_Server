<?php
require "conn.php";

date_default_timezone_set('Europe/Bucharest');
$server_date = date('Y-m-d');
$server_time = date("H:i:s");
$server_td = date("Y-m-d H:i:s");


$tag_input= $_POST["tag_input"];
$user_id = $_POST["user_id"];

loadUpcomingTask();

$upcoming_task=loadUpcomingTask();
$ut_taskid = $upcoming_task["task_id"];

$dt = strtotime($upcoming_task["deadline"]);
$utd_date = date('Y-m-d', $dt);   //utd = upcoming task deadline
$utd_time = date('H:i:s', $dt);
$utd_td = date('Y-m-d H:i:s', $dt);

$st = strtotime($upcoming_task["start_time"]);
$utst_date = date('Y-m-d', $st);
$utst_time = date('H:i:s', $st);
$utst_td = date('Y-m-d H:i:s', $st);


echo whereAmI() . "#";

if(!is_null($upcoming_task))
tagProcessor();
else
echo "?#No task associated with this location.";



function loadUpcomingTask() //failed tasks aren't loaded
{
	global $connect, $user_id, $tag_input;

	$task_query = mysqli_query($connect,"SELECT task_id,tag_id,deadline,status,start_time FROM tasks WHERE USER_ID LIKE '$user_id' AND TAG_ID LIKE '$tag_input' AND status IN ('PENDING', 'STARTED')  ORDER BY deadline LIMIT 1;");
		$upcoming_task = mysqli_fetch_assoc($task_query);

		return $upcoming_task;
}


function tagProcessor()
{ global $connect,$utd_time,$utd_date,$utd_td,$server_time,$server_date, $server_td, $utst_time, $utst_date,$utst_td, $ut_taskid;
	$timewindow = strtotime($server_td) - strtotime($utst_td);

	if($utd_date == $server_date) //if it is due today
	if($utst_date == "1970-01-01") //if it wasnt started yet
		{ 
		mysqli_query($connect,"UPDATE tasks SET status='STARTED', start_time='$server_td' WHERE task_id LIKE '$ut_taskid';");
		echo "V#Task " .$ut_taskid . " started successfully!"; 
		}

		else  //if it was started before
		{
			
			if($timewindow <= 21600 && $timewindow>=1800) // if correctly ended
			{
				mysqli_query($connect,"UPDATE tasks SET status='DONE' WHERE task_id LIKE '$ut_taskid';");
				echo "V#Task " .$ut_taskid . " completed! Good job!";
			}
			else {  if ($timewindow < 1800) echo "X#Wait at least 30 minutes before finishing task " .$ut_taskid . " !". " Minutes to go: " . (int)((1800-$timewindow)/60);
					if($timewindow > 21600) echo "X#Task " .$ut_taskid. " was started more than 6 hours ago and was not ended!";
				}
		}
	else echo "?#Task " .$ut_taskid. " is not due today! Please do it on the day it is due!";

	mysqli_close($connect);

}
function whereAmI()
{ global $connect, $tag_input;
$loc=mysqli_fetch_assoc(mysqli_query($connect,"SELECT description FROM tags WHERE tag_id LIKE '$tag_input';"));
return $loc['description'];
}

?>