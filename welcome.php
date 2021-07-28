<?php
	require_once 'connection.php';
	session_start();

	if(!isset($_SESSION['user_login']))
	{
		header("location: index.php");
	}

	$id = $_SESSION['user_login'];

	$select_stmt = $db->prepare("SELECT * FROM tbl_user WHERE user_id=:uid");
	$select_stmt->execute(array(":uid"=>$id));

	$row=$select_stmt->fetch(PDO::FETCH_ASSOC);

	if(isset($_SESSION['user_login']))
	{
	?>
	welcome MR,
	<?php
		echo $row['username']."<br>";
		?> <a href="logout.php">Logout</a>
		<?php

		}
?>
