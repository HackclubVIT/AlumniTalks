<?php
require_once "pdo.php";
session_start();
if($_SESSION['user']==0)
{
  die("Sign in first");
}
$msg="";
$errmsg="";
if(isset($_POST['email']))
{
  $sql= "SELECT email FROM users WHERE BINARY email=:e";
  $stmt= $pdo->prepare($sql);
  $stmt->execute(array(
    ':e'=> $_POST['email'],
  ));
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
  if($row===false)
  {
    $hash=hash('md5', microtime());
    $sql= "INSERT Into changemail(process,email,user_id) values(:pr,:e,:u)";
    $stmt= $pdo->prepare($sql);
    $stmt->execute(array(
      ':pr'=> $hash,
      ':e'=> $_POST['email'],
      ':u'=> $_SESSION['user'],
    ));
    $to = $_POST['email'];
    $subject = 'Update Email';
    $message = "Click on the link to confirm email. ".$site."/AlumniTalks/changemail.php?x=".$hash;
    if(mail($to, $subject, $message)){
        $msg= 'An email has been sent to your new mail for confirmation.<br> It might take upto 10 minutes. <br> Please close the page.';
    }
    else{
        $msg= 'Unable to send email to your email address. Please try again.';
    }
    $_POST = array();
  }
  else
  {
    $errmsg="An email account with this name is already registered.";
  }

}

if(isset($_GET['x']))
{
  $sql= "SELECT process FROM changemail WHERE BINARY process=:p";
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
    $sql= "SELECT email,user_id FROM changemail WHERE BINARY process=:p";
  	$stmt= $pdo->prepare($sql);
  	$stmt->execute(array(
  		':p'=> $_GET['x'],
  	));
  	$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
    $user_id;
    $email;
    foreach ( $rows as $row ) {
        $user_id=$row['user_id'];
        $email=$row['email'];
      }
    $sql= "UPDATE users SET email = :e WHERE user_id = :ui";
  	$stmt= $pdo->prepare($sql);
    $stmt->execute(array(
  	  ':e'=> $email,
      ':ui'=>$user_id,
  	));
		$msg="Your email has been updated. <br> <a href='index.php'>Go back to Home</a>";
    $sql="DELETE FROM changemail WHERE BINARY process = :p";
    $stmt=$pdo->prepare($sql);
    $stmt->execute(array(
      ':p'=>$_GET['x'],
    ));
	}
}
require_once "head.php";
?>
<div class="container-fluid">
 <div class="row">
   <?php require_once "leftcolumn.php"; ?>
   <div class="col-sm-6">
     <div class="card">
       <div class="card-body">
<?php
  if($errmsg!="")
  {
    echo("<p style='text-align:center;'>".$errmsg."</p><br>");
  }
  if($msg=="")
  {
    echo('<form action="changemail.php" method="post">');
    echo('<h2>Update Email</h2>');
    echo('<input type="text" placeholder="Enter New Email" name="email" required>');
    echo('<br>');
    echo('<button type="submit">Update</button>');
    echo('<button type="button" onclick="window.location.href=\'index.php\';">Cancel</button>');
    echo('<br>');
    echo('</form>');
  }
  elseif($msg!="")
  {
    echo("<p>".$msg."</p>");
  }
  ?>
  </div>
</div>
</div>
<?php require_once "rightcolumn.php"; ?>
</div>
</div>
<?php require_once "footer.php"; ?>
