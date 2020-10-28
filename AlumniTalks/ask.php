<?php
require_once "pdo.php";
session_start();
if($_SESSION['user']==0)
{
  die("Sign in first");
}
if(isset($_POST['title']) && isset($_POST['des']))
{

	  $sql= "INSERT Into datacontent(title,user_id,description) values(:t,:u,:d)";
		$stmt= $pdo->prepare($sql);
    $content = nl2br(htmlentities($_POST['des'], ENT_QUOTES, 'UTF-8'));
		$stmt->execute(array(
			':t'=> $_POST['title'],
      ':u'=> $_SESSION['user'],
			':d'=> $content,
		));
	}
  require_once "head.php";
 ?>

 <div class="container-fluid">
  <div class="row">
    <?php require_once "leftcolumn.php"; ?>
    <div class="col-sm-6">
      <div class="card">
        <div class="card-body">
        <h1>Ask</h1>
          <form action="ask.php" method="POST">
              <label for="title"><b>Title</b></label><br>
              <input type="text" name="title" required><br><br>
              <div class="form-group">
                <label for="des">Description:</label>
                <textarea class="form-control" id="des" name="des"></textarea>
              </div>
              <br>
              <button type="button" onclick="window.location.href='index.php';">Back</button>
              <button type="submit">Ask</button>
           </form>
        </div>
        </div>
        <br>
      </div>
      <?php require_once "rightcolumn.php"; ?>
   </div>
  </div>

  <?php require_once "footer.php"; ?>
