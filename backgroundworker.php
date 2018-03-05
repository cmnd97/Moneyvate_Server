<?php
// note: use a cron job to run this script once every 5 minutes or so, to invalidate tasks that go past the deadline!
require "conn.php";
date_default_timezone_set('Europe/Bucharest');
$server_timestamp = strtotime(date("Y-m-d H:i:s"));
loadAndCheck();
function loadAndCheck()
{	global $connect, $server_timestamp;
	$task_query = mysqli_query($connect,"SELECT task_id,deadline,status,start_time FROM tasks WHERE status IN ('PENDING', 'STARTED')  ORDER BY deadline;");
	while($current_task = mysqli_fetch_assoc($task_query))
	{	$task_id = $current_task['task_id'];
		if($server_timestamp - strtotime($current_task['deadline'])>0 && ( $current_task['start_time'] == '1970-01-01 00:00:00'))

		 mysqli_query($connect,"UPDATE tasks SET status='FAILED' WHERE task_id LIKE '$task_id';");

		 if(($server_timestamp - strtotime($current_task['start_time']) >21600) && ( $current_task['start_time'] != '1970-01-01 00:00:00')) 
				mysqli_query($connect,"UPDATE tasks SET status='FAILED' WHERE task_id LIKE '$task_id';");	
	}

	mysqli_close($connect);
}

?>