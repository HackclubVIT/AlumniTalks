<?php
require_once "pdo.php";
session_start();
if($_SESSION['user']==0)
{
  die("Sign in first");
}
$fname="";
$lname="";
$gender="";
$email="";
$cat="";

$sql="SELECT user_id FROM users WHERE user_id = :u";
$stmt= $pdo->prepare($sql);
$stmt->execute(array(
  ':u'=> $_SESSION['user'],
));
$data = $stmt->fetch(PDO::FETCH_ASSOC);
if($data===FALSE)
{
  die("Cannot find the given user.");
}

$msg="";

if(isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['gender']))
{
  $sql="SELECT email FROM users WHERE BINARY email = :e AND user_id != :ui";
  $stmt= $pdo->prepare($sql);
  $stmt->execute(array(
    ':e'=> $_POST['email'],
    ':ui'=> $_SESSION['user'],
  ));
  $data = $stmt->fetch(PDO::FETCH_ASSOC);
  if($data===FALSE)
  {
    $sql= "UPDATE users SET  fn = :f, ln = :l, gender = :g WHERE user_id = :ui";
		$stmt= $pdo->prepare($sql);
		$stmt->execute(array(
			':f'=> $_POST['fname'],
			':l'=> $_POST['lname'],
      ':g'=> $_POST['gender'],
      ':ui'=>$_SESSION['user'],
		));
    $msg="Your changes have been saved.";
  }

}

  $sql="SELECT fn, ln, gender, email, category FROM users WHERE user_id= :ui";
  $stmt= $pdo->prepare($sql);
  $stmt->execute(array(
    ':ui'=> $_SESSION['user'],
  ));
  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach ($data as $value) {
    $fname=$value['fn'];
    $lname=$value['ln'];
    $gender=$value['gender'];
    $email=$value['email'];
    $cat=$value['category'];
  }
  require_once "head.php";
 ?>
<div class="container-fluid">
  <br>
 <div class="row">
   <?php require_once "leftcolumn.php"; ?>
   <div class="col-sm-6">
     <div class="card">
       <div class="card-body">
       <h1>Edit Account</h1>
         <form action="myaccount.php" method="POST">
             <label for="fname"><b>First Name</b></label><br>
             <input type="text" value="<?php echo(htmlentities($fname)); ?>" name="fname" required><br><br>
             <label for="uname"><b>Last Name</b></label><br>
             <input type="text" value="<?php echo(htmlentities($lname)); ?>" name="lname" required><br><br>
             <b>Gender</b><br>
             <input type="radio" id="m" name="gender" value="m" <?php echo($gender=='m'?'checked':'')?>>
             <label for="P">Male</label><br>
             <input type="radio" id="f" name="gender" value="f" <?php echo($gender=='f'?'checked':'')?>>
             <label for="">Female</label><br>
             <input type="radio" id="o" name="gender" value="o" <?php echo($gender=='o'?'checked':'')?>>
             <label for="other">Others</label><br><br>
             <br>
             <b>Category:</b> <span><?php echo($cat=='S'?'Student':'Alumni'); ?></span><br>
             <b>Email:</b> <span><?php echo(htmlentities($email)." "); ?></span><button type="button" onclick="window.location.href='changemail.php';">Update</button>
             <br>
             <br>
             <b>Reset Password? </b><button type="button" onclick="window.location.href='resetPassword.php?x=1';">Reset</button><span> (You would be logged out to reset password)</span>
             <br>
             <br>
             <button type="button" onclick="window.location.href='index.php';">Back</button>
             <button type="submit">Change</button>
             <?php echo("<br><p>".$msg."</p><br>"); ?>
          </form>
       </div>
       </div>
     </div>
     <?php require_once "rightcolumn.php"; ?>
  </div>
 </div>

 <?php require_once "footer.php"; ?>
