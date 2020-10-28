<?php
require_once "pdo.php";
session_start();
$errmsg="";
$blocked=FALSE;
if(isset($_GET['in']) && $_GET['in']==1)
{
  $_GET = array();
  $errmsg="No user exists with this password and user name";
}
if(isset($_POST['email']))
{
  $sql="SELECT email FROM blocked WHERE BINARY email=:e";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ':e'=> $_POST['email'],
  ));
  $rows = $stmt->fetch(PDO::FETCH_ASSOC);
  if($rows!==FALSE)
  {
    $sql="SELECT email,attempts FROM blocked WHERE BINARY email=:e";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ':e'=> $_POST['email'],
    ));
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row)
    {
      if($row['attempts']>=5)
      {
        $blocked=TRUE;
        $errmsg="The user has been blocked. Reset password to unblock.";
      }
    }
  }
}
if(isset($_POST['email']) && isset($_POST['psw']) && $blocked==FALSE)
{
  $sql="SELECT email FROM users WHERE BINARY email=:e AND BINARY pass=:p";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
		':e'=> $_POST['email'],
    ':p'=> $_POST['psw'],
	));
  $rows = $stmt->fetch(PDO::FETCH_ASSOC);
  if($rows===FALSE)
  {
    $sql="SELECT email FROM users WHERE BINARY email=:e";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
  		':e'=> $_POST['email'],
  	));
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    if($rows!==FALSE)
    {
      $sql="SELECT email FROM blocked WHERE BINARY email=:e";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
    		':e'=> $_POST['email'],
    	));
      $rows = $stmt->fetch(PDO::FETCH_ASSOC);
      if($rows!==FALSE)
      {
        $sql="SELECT attempts FROM blocked WHERE BINARY email=:e";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
      		':e'=> $_POST['email'],
      	));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $attempt;
        foreach($rows as $row)
        {
          $attempt=$row['attempts'];
        }
        $sql="UPDATE blocked SET attempts = :a WHERE BINARY email = :e";
        $stmt= $pdo->prepare($sql);
        $stmt->execute(array(
          ':a'=> $attempt+1,
      		':e'=> $_POST['email'],
      	));
      }
      if($rows===FALSE)
      {
        $sql="INSERT Into blocked(email,attempts) values(:e,:a)";
        $stmt= $pdo->prepare($sql);
        $stmt->execute(array(
          ':e'=> $_POST['email'],
          ':a'=> 1,
      	));
      }

    }
    $_POST = array();
    header("Location: login.php?in=1");
  }
  else
  {
    if($blocked==FALSE)
    {
      $sql="SELECT email FROM blocked WHERE BINARY email=:e";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
        ':e'=> $_POST['email'],
      ));
      $rows = $stmt->fetch(PDO::FETCH_ASSOC);
      if($rows!==FALSE)
      {
        $sql="DELETE FROM blocked WHERE email = :e";
        $stmt=$pdo->prepare($sql);
        $stmt->execute(array(
          ':e'=>$_POST['email'],
        ));
      }
      $sql="SELECT user_id, category, email, pass, fn FROM users WHERE BINARY email=:e AND BINARY pass=:p";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
    		':e'=> $_POST['email'],
        ':p'=> $_POST['psw'],
    	));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach ( $rows as $row ) {
          $_SESSION['user']=$row['user_id'];
          $_SESSION['fn']=$row['fn'];
          $_SESSION['cat']=$row['category'];
        }
        header("Location: index.php");
    }

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
<form class="form-signin"action="login.php" method="post">
    <h2>Log In</h2>
    <?php
    if($errmsg!="")
    {
      echo("<p style='color:black; padding:2px; text-align:center;'>".$errmsg."</p>");
    }
    ?>
    <input type="email" class="form-control" placeholder="Enter Email" name="email" required>
    <input type="password" class="form-control" placeholder="Enter Password" name="psw" required>
    <br>
    <button type="submit">Login</button>
    <button type="button" onclick="window.location.href='index.php';">Cancel</button>
    <br>
    <p><a class="text-danger" href="resetPassword.php?x=1">Reset Password?</a><br><br> Don't have an account? <br><a href="signup.php" style="color:#4CAF50">Sign Up</a></p>
    <br>
</form>

</body>
</html>
