<?php
require_once "pdo.php";
session_start();
if($_SESSION['user']==0)
{
  die("Sign in first");
}
$title;
$content;

$sql="SELECT title FROM datacontent WHERE user_id = :u AND sr_no = :s";
$stmt= $pdo->prepare($sql);
$stmt->execute(array(
  ':u'=> $_SESSION['user'],
  ':s'=> $_GET['x'],
));
$data = $stmt->fetch(PDO::FETCH_ASSOC);
if($data===FALSE)
{
  die("Cannot find article with the current user");
}

if(isset($_POST['title']) && isset($_POST['des']))
{

	  $sql= "UPDATE datacontent SET title = :t,user_id = :u, description = :d WHERE sr_no = :s";
		$stmt= $pdo->prepare($sql);
    $content = nl2br(htmlentities($_POST['des'], ENT_QUOTES, 'UTF-8'));
		$stmt->execute(array(
			':t'=> $_POST['title'],
      ':u'=> $_SESSION['user'],
			':d'=> $content,
      ':s'=> $_GET['x'],
		));
	}
  $sql="SELECT title, description FROM datacontent WHERE user_id = :u AND sr_no = :s";
  $stmt= $pdo->prepare($sql);
  $stmt->execute(array(
    ':u'=> $_SESSION['user'],
    ':s'=> $_GET['x'],
  ));
  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach ($data as $value) {
    $title=$value['title'];
    $content=$value['description'];

  }
  if(isset($_POST['del']) && $_POST['del']=="Delete")
  {
    $sql="DELETE FROM datacontent WHERE sr_no = :s";
    $stmt=$pdo->prepare($sql);
    $stmt->execute(array(
      ':s'=>$_GET['x'],
    ));
    header("Location: mycontent.php");
  }
  require_once "head.php";
 ?>

 <div class="container-fluid">
  <div class="row">
    <?php require_once "leftcolumn.php"; ?>
    <div class="col-sm-6">
      <div class="card">
        <div class="card-body">
        <h1>Add Item</h1>
          <form action="manage.php?x=<?php echo($_GET['x'])?>" method="POST">
            <label for="title"><b>Title</b></label><br>
              <input type="text" name="title" required value="<?php echo(htmlentities($title)); ?>"><br><br>
              <div class="form-group">
                <label for="des">Description:</label>
                <textarea class="form-control" id="des" name="des"><?php echo(htmlentities($content)); ?></textarea>
              </div>
              <br>
              <button type="button" onclick="window.location.href='index.php';">Back</button>
              <button type="submit">Update</button>
              <input type='submit' value='Delete' name='del'>
           </form>
        </div>
        </div>
        <br>
      </div>
      <?php require_once "rightcolumn.php"; ?>
   </div>
  </div>

  <?php require_once "footer.php"; ?>
