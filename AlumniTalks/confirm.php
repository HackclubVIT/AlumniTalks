<?php
require_once "pdo.php";
$msg="";
if(isset($_GET['x']))
{
	$sql= "SELECT process FROM temp WHERE BINARY process=:p";
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
    $sql= "SELECT category,fn,ln,gender,email,pass FROM temp WHERE BINARY process=:p";
  	$stmt= $pdo->prepare($sql);
  	$stmt->execute(array(
  		':p'=> $_GET['x'],
  	));
  	$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
		$cat;
    $fn;
    $ln;
    $gender;
    $email;
    $pass;
    foreach ( $rows as $row ) {
			  $cat=$row['category'];
        $fn=$row['fn'];
        $ln=$row['ln'];
        $gender=$row['gender'];
        $email=$row['email'];
        $pass=$row['pass'];
      }
		$sql= "INSERT Into users(category,fn,ln,gender,email,pass) values(:c,:f,:l,:g,:e,:p)";
		$stmt= $pdo->prepare($sql);
		$stmt->execute(array(
			':c'=> $cat,
			':f'=> $fn,
			':l'=> $ln,
			':g'=> $gender,
			':e'=> $email,
			':p'=> $pass,
		));
		$msg="Your account has been created. <br> Please <a href='login.php'>Log In</a>";
    $sql="DELETE FROM temp WHERE BINARY process = :p";
    $stmt=$pdo->prepare($sql);
    $stmt->execute(array(
      ':p'=>$_GET['x'],
    ));
	}
}
else
{
  $msg="The request is empty <br> <a href='index.php'>Go back to Home</a>";
}
?>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="jumbotron">
	  <h1 style="text-align:center;">AlumniTalks You!!!</h1>
	  <p style="text-align:center;"><i>"Connecting VITians"</i></p>
	</div>
	<br>
	<div class="row">
		<div class="col-sm-12" style="text-align:center;">
			<?php
			if($msg!="")
			{
				echo($msg);
			}
			?>
		</div>
	</div>
</body>
</html>
