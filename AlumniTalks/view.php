<?php
session_start();
require_once "pdo.php";
if(!isset($_SESSION['user']))
{
  $_SESSION['user']=0;
}
if(isset($_GET['x']))
{
  $sql = "SELECT sr_no FROM datacontent WHERE sr_no = :s";
  $stmt = $pdo->prepare($sql);
  $stmt -> execute(array(
    ':s' => $_GET['x'],
  ));
  $data = $stmt->fetch(PDO::FETCH_ASSOC);
  if($data===False)
  {
    die("Page does not exist");
  }
}
/*Updating views*/
if($_SESSION['user']!=0 && isset($_GET['x']))
{
  $sql = "SELECT * FROM views WHERE sr_no = :s AND user_id= :u";
  $stmt = $pdo->prepare($sql);
  $stmt -> execute(array(
    ':s' => $_GET['x'],
    ':u' => $_SESSION['user'],
  ));
  $data = $stmt->fetch(PDO::FETCH_ASSOC);
  if($data===False)
  {
    $sql= "INSERT Into views(sr_no,user_id) values(:s,:u)";
    $stmt= $pdo->prepare($sql);
    $stmt->execute(array(
      ':s' => $_GET['x'],
      ':u' => $_SESSION['user'],
    ));
    $sql= "SELECT views FROM datacontent WHERE sr_no = :s";
    $stmt= $pdo->prepare($sql);
    $stmt->execute(array(
      ':s' => $_GET['x'],
    ));
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $value1=0;
    foreach ($data as $value) {
      $value1=$value['views'];
    }
    $value1=$value1+1;
    $sql= "UPDATE datacontent SET views = :v, dateEdited=dateEdited WHERE sr_no = :s";
    $stmt= $pdo->prepare($sql);
    $stmt->execute(array(
      ':v' => $value1,
      ':s' => $_GET['x'],
    ));
  }
}

/*Updating likes*/
if($_SESSION['user']!=0 && isset($_GET['x']) && isset($_POST['like']))
{
  if($_POST['like']=="Like")
  {
    $sql = "SELECT * FROM likes WHERE sr_no = :s AND user_id= :u";
    $stmt = $pdo->prepare($sql);
    $stmt -> execute(array(
      ':s' => $_GET['x'],
      ':u' => $_SESSION['user'],
    ));
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if($data===False)
    {
      $sql= "INSERT Into likes(sr_no,user_id) values(:s,:u)";
      $stmt= $pdo->prepare($sql);
      $stmt->execute(array(
        ':s' => $_GET['x'],
        ':u' => $_SESSION['user'],
      ));
      $sql= "SELECT likes FROM datacontent WHERE sr_no = :s";
      $stmt= $pdo->prepare($sql);
      $stmt->execute(array(
        ':s' => $_GET['x'],
      ));
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $value1=0;
      foreach ($data as $value) {
        $value1=$value['likes'];
      }
      $value1=$value1+1;
      $sql= "UPDATE datacontent SET likes = :l, dateEdited=dateEdited WHERE sr_no = :s";
      $stmt= $pdo->prepare($sql);
      $stmt->execute(array(
        ':l' => $value1,
        ':s' => $_GET['x'],
      ));
    }
  }
  if($_POST['like']=="Unlike")
  {
    $sql = "SELECT * FROM likes WHERE sr_no = :s AND user_id= :u";
    $stmt = $pdo->prepare($sql);
    $stmt -> execute(array(
      ':s' => $_GET['x'],
      ':u' => $_SESSION['user'],
    ));
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if($data!==False)
    {
      $sql= "DELETE FROM likes WHERE sr_no = :s AND user_id= :u";
      $stmt= $pdo->prepare($sql);
      $stmt->execute(array(
        ':s' => $_GET['x'],
        ':u' => $_SESSION['user'],
      ));
      $sql= "SELECT likes FROM datacontent WHERE sr_no = :s";
      $stmt= $pdo->prepare($sql);
      $stmt->execute(array(
        ':s' => $_GET['x'],
      ));
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $value1=0;
      foreach ($data as $value) {
        $value1=$value['likes'];
      }
      $value1=$value1-1;
      $sql= "UPDATE datacontent SET likes = :l, dateEdited=dateEdited WHERE sr_no = :s";
      $stmt= $pdo->prepare($sql);
      $stmt->execute(array(
        ':l' => $value1,
        ':s' => $_GET['x'],
      ));
    }
  }
  header("Location: view.php?x=".$_GET['x']);
}

/*like button display*/
$sql = "SELECT * FROM likes WHERE sr_no = :s AND user_id= :u";
$stmt = $pdo->prepare($sql);
$stmt -> execute(array(
  ':s' => $_GET['x'],
  ':u' => $_SESSION['user'],
));
$data = $stmt->fetch(PDO::FETCH_ASSOC);
if($data===False)
{
  $likebutton=0;
}
else
{
  $likebutton=1;
}


/*Extracting content from database as per the get value*/
$sql = "SELECT datacontent.likes,datacontent.views,datacontent.dateCreated,datacontent.dateEdited,datacontent.title,datacontent.description,datacontent.sr_no,users.fn,users.ln FROM datacontent JOIN users ON datacontent.user_id=users.user_id Where sr_no = :s";
$stmt = $pdo->prepare($sql);
$stmt -> execute(array(
  ':s' => $_GET['x'],
));
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
require_once "head.php";
 ?>
 <div class="container-fluid">
   <br>
 <div class="row">
   <?php require_once "leftcolumn.php"; ?>
     <div class="col-sm-6">
       <div class="card">
         <div class="card-body" style="min-height:450px;">
       <p>
         <?php
         foreach ( $data as $row ) {
             echo("<p>Views : ".$row['views']."&emsp;Likes : ".$row['likes']."<p>");
         }
         ?>
       </p>
         <?php
         foreach ( $data as $row ) {
             echo("<h1><b>".htmlentities($row['title'])."</b></h1>");
             echo($row['description']);
             echo("<br>");
             echo("<br>");
             echo("<p style='float:right;'> <b>Asked by : </b>".htmlentities($row['fn']." ".$row['ln'])."
             <br><b><i>Created on : ".htmlentities($row['dateCreated'])."&emsp;<br>
             Last Modified : ".htmlentities($row['dateEdited'])."</i></b></p><br>");
          }
         ?>
       </div>
       <p>
         <?php
         if($_SESSION['user']==0)
         {
           echo("<button style='margin-left:5px;' onclick='window.location.href=\"login.php\";' >Like</button>");
         }
         if($_SESSION['user']!=0 && $likebutton==0)
         {
           echo("<form method='post'>");
           echo("<input style='margin-left:10px;' type='submit' value='Like' name='like'>");
           echo("</form>");
         }
         if($_SESSION['user']!=0 && $likebutton==1)
         {
           echo("<form method='post'>");
           echo("<input style='margin-left:10px;' type='submit' value='Unlike' name='like'>");
           echo("</form>");
         }
         ?>

     </p>
     </div>
     </div>
      <?php require_once "rightcolumn.php"; ?>


</div>
 <?php require_once "footer.php"; ?>
