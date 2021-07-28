<?php
require_once "connection.php";
require_once "header.php";

if(isset($_REQUEST['btn_register']))
{
	$username	= strip_tags($_REQUEST['txt_username']);
	$password	= strip_tags($_REQUEST['txt_password']);

	if(empty($username)){$errorMsg[]="enter username";}
	else if(empty($password)){$errorMsg[]="enter password";}
	else if(strlen($password) < 6){$errorMsg[] = "6 zeichen lang";
	}
	else
	{
		try
		{
			$select_stmt=$db->prepare("SELECT username FROM tbl_user WHERE username=:uname");
			$select_stmt->execute(array(':uname'=>$username));
			$row=$select_stmt->fetch(PDO::FETCH_ASSOC);

			if(isset($row["username"]) ==$username){
				$errorMsg[]="username ist vergeben";

			}

			else if(!isset($errorMsg))
			{
				$new_password = password_hash($password, PASSWORD_DEFAULT);

				$insert_stmt=$db->prepare("INSERT INTO tbl_user	(username,password) VALUES (:uname,:upassword)");

				if($insert_stmt->execute(array(	':uname' =>$username, ':upassword'=>$new_password))){

					$registerMsg="ok log in";
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
}
?>

<?php
		if(isset($errorMsg))
		{
			foreach($errorMsg as $error)
			{
?>
<b>WRONG ! <?php echo $error; ?></b>

<?php
		}
		}
		if(isset($registerMsg))
		{
		?>

	<b><?php echo $registerMsg; ?></b>

	<?php
		}
?>

<form>
	<input type="text" name="txt_username"  placeholder="neuer username"><br><br>
	<input type="password" name="txt_password"  placeholder="neues password"><br><br>
	<input type="submit"  name="btn_register"  value="Register"><br><br>
	<a href="index.php">Login Account</a>
</form>
