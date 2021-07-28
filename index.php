<?php
  require_once 'connection.php';
  session_start();

  if(isset($_SESSION["user_login"]))
  {
    header("location: welcome.php");
  }

  if(isset($_REQUEST['btn_login']))
  {
    $username	=strip_tags($_REQUEST["txt_username_email"]);
    $email		=strip_tags($_REQUEST["txt_username_email"]);
    $password	=strip_tags($_REQUEST["txt_password"]);

    if(empty($username)){$errorMsg[]="enter username";}
    else if(empty($email)){$errorMsg[]="enter email";}
    else if(empty($password)){$errorMsg[]="enter password";}
    else
    {
      try
      {
        $select_stmt=$db->prepare("SELECT * FROM tbl_user WHERE username=:uname OR email=:uemail");
        $select_stmt->execute(array(':uname'=>$username, ':uemail'=>$email));
        $row=$select_stmt->fetch(PDO::FETCH_ASSOC);

        if($select_stmt->rowCount() > 0)
        {
          if($username==$row["username"] OR $email==$row["email"])
          {
            if(password_verify($password, $row["password"]))
            {
              $_SESSION["user_login"] = $row["user_id"];
              $loginMsg = "login OK";
              header("refresh:1; welcome.php");			
            }
            else
            {
              $errorMsg[]="wrong password";
            }
          }
          else
          {
            $errorMsg[]="wrong username or email";
          }
        }
        else
        {
          $errorMsg[]="wrong username or email";
        }
      }
      catch(PDOException $e)
      {
        $e->getMessage();
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
          <div class="alert alert-danger">
            <b><?php echo $error; ?></b>
          </div>
              <?php
        }
      }
      if(isset($loginMsg))
      {
      ?>
        <div class="alert alert-success">
          <b><?php echo $loginMsg; ?></b>
        </div>
          <?php
    }
?>


<form method="post">
	<input type="text" name="txt_username_email" placeholder="username">
	<input type="password" name="txt_password" placeholder="passwort">
	<br><br>
	<input type="submit" name="btn_login" value="Login"><br><br>
	<a class="register" href="register.php">Register</a>
</form>
