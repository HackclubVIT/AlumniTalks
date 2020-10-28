<?php
require_once "pdo.php";
session_start();
session_destroy();
$msg="";
if(isset($_GET['x']) && $_GET['x']==1)
{
  $msg="1";
}
else
{
  $msg="The request is empty <br> <a href='index.php'>Go back to Home</a>";
}
if(isset($_GET['x']) && strlen($_GET['x'])==32)
{
	$sql= "SELECT process FROM resetpassword WHERE BINARY process=:p";
	$stmt= $pdo->prepare($sql);
	$stmt->execute(array(
		':p'=> $_GET['x'],
	));
	$row=$stmt->fetch(PDO::FETCH_ASSOC);
	if($row===false)
	{
    $msg="There is no such request <br> <a href='index.php'>Go back to Home</a>";
	}
  else
	{
    $msg="2";
	}
}

$errmsg="";

if(isset($_POST['email']))
{
  $sql= "SELECT email FROM users WHERE BINARY email=:e";
  $stmt= $pdo->prepare($sql);
  $stmt->execute(array(
    ':e'=> $_POST['email'],
  ));
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
  if($row!==false)
  {
    $hash=hash('md5', microtime());
    $sql= "INSERT Into resetpassword(process,email) values(:pr,:e)";
    $stmt= $pdo->prepare($sql);
    $stmt->execute(array(
      ':pr'=> $hash,
      ':e'=> $_POST['email'],
    ));
    $to = $_POST['email'];
    $subject = 'Reset Password';
    $message = "Click on the link to reset password. ".$site."/AlumniTalks/resetPassword.php?x=".$hash;
    if(mail($to, $subject, $message)){
        $msg= 'An email has been sent to your mail id for resetting password.<br> It might take upto 10 minutes. <br> Please close the page.';
    } else{
        $msg= 'Unable to send email to your email address. Please try again.';
    }
    $_POST = array();
  }
  else
  {
    $errmsg="Email account not registered.";
    $msg="1";
  }

}

if(isset($_POST['psw']) && isset($_POST['psw-repeat']) && isset($_GET['x']) and strlen($_GET['x'])==32)
{
  $msg="2";
  if($_POST['psw']==$_POST['psw-repeat'] && strlen($_POST['psw'])>=7)
	{
    $email;
		$sql= "SELECT process FROM resetpassword WHERE BINARY process=:p";
		$stmt= $pdo->prepare($sql);
		$stmt->execute(array(
			':p'=> $_GET['x'],
		));
		$row=$stmt->fetch(PDO::FETCH_ASSOC);
		if($row!==false)
		{
      $sql= "SELECT email FROM resetpassword WHERE BINARY process=:p";
  		$stmt= $pdo->prepare($sql);
  		$stmt->execute(array(
  			':p'=> $_GET['x'],
  		));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach ( $rows as $row ) {
          $email=$row['email'];
        }
			$sql= "UPDATE users SET  pass = :p WHERE email = :e";
			$stmt= $pdo->prepare($sql);
			$stmt->execute(array(
				':p'=> $_POST['psw'],
        ':e'=> $email,
			));
      $sql="SELECT email FROM blocked WHERE BINARY email=:e";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
        ':e'=> $email,
      ));
      $rows = $stmt->fetch(PDO::FETCH_ASSOC);
      if($rows!==FALSE)
      {
        $sql="DELETE FROM blocked WHERE email = :e";
        $stmt=$pdo->prepare($sql);
        $stmt->execute(array(
          ':e'=>$email,
        ));
      }
      $msg="Your Password has been reset. <br> Please <a href='login.php'>Login</a>";
      $sql="DELETE FROM resetpassword WHERE BINARY process = :p";
      $stmt=$pdo->prepare($sql);
      $stmt->execute(array(
        ':p'=>$_GET['x'],
      ));
		}
	}
  else
  {
    $errmsg="Passwords dont match";
  }
  if(strlen($_POST['psw'])<7)
  {
    $errmsg="Password too short";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/styles.css">
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="signin_up.css">
</head>
<body>
    <?php
    if($msg=="1")
    {
      echo('<form class="form-signin" action="resetPassword.php" method="post">');
      echo('<h2">Reset Password</h2>');
      if($errmsg!="")
      {
        echo("<p>".$errmsg."</p><br>");
      }
      echo('<input class="form-control" type="text" placeholder="Enter Registered Email" name="email" required>');
      echo('<br>');
      echo('<button type="submit">Reset?</button>');
      echo('<button type="button" class="cancelbtn" onclick="window.location.href=\'index.php\';">Cancel</button>');
      echo('<br>');
      echo('</form>');
    }
    if($msg=="2")
    {
      echo('<form class="form-signin" action="resetPassword.php?x='.$_GET['x'].'" method="post">');
      echo('<h2>Reset Password</h2>');
      if($errmsg!="")
      {
        echo("<p>".$errmsg."</p><br>");
      }
      echo('<input class="form-control" type="password" placeholder="Enter Password" name="psw" required>');
      echo('<input class="form-control" type="password" placeholder="Confirm Password" name="psw-repeat" required>');
      echo('<br>');
      echo('<button type="submit">Reset Paswword</button>');
      echo('<button type="button" onclick="window.location.href=\'index.php\';">Cancel</button>');
      echo('<br>');
      echo('</form>');
    }
    elseif($msg!="" && $msg!="1" && $msg!="2")
    {
      echo("<div class=\"jumbotron\">");
    	echo("<h1 style=\"text-align:center;\">AlumniTalks!!!</h1>");
    	echo("<p style=\"text-align:center;\"><i>\"A place you can trust.\"</i></p>");
    	echo("</div>");
      echo("<div class=\"container\" style=\"text-align:center;\">".$msg."</div>");
    }
    ?>

</body>
</html>
