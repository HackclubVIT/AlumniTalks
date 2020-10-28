<?php
require_once "pdo.php";
session_start();
if(!isset($_SESSION['user']) OR $_SESSION['user']==0)
{
  die("Sign in first");
}
$sql="SELECT title,sr_no,dateCreated FROM datacontent WHERE BINARY user_id = :ui";
$stmt= $pdo->prepare($sql);
$stmt->execute(array(
  ':ui'=> $_SESSION['user'],
));
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
require_once "head.php";
?>
<div class="container-fluid">
  <br>
<div class="row">
  <?php require_once "leftcolumn.php"; ?>
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body" style="min-height:400px;">
        <h1>
          My Posts
        </h1>
        <br>
        <table class="table-bordered" style="width:100%;">
          <tr>
            <th>Title</th>
            <th>Posted On</th>
            <th>Manage</th>
          </tr>
        <?php
        foreach ( $rows as $row ) {
            echo "<tr><td>";
            echo(htmlentities($row['title']." "));
            echo("</td><td>");
            echo(htmlentities($row['dateCreated']." "));
            echo("</td><td>");
            echo("<a href='manage.php?x=".$row['sr_no']."'style='text-align:center;;'>Manage</a></div></br>");
            echo("</td></tr>\n");
        }
        ?>
        </table>
    </div>
  </div>
</div>
  <?php require_once "rightcolumn.php"; ?>
</div>
</div>
<?php require_once "footer.php"; ?>
