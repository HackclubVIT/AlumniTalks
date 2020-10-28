<?php
session_start();
require_once "head.php";
?>
  <div class="container-fluid" id="welcome">
  <br>
  <div class="row">
    <?php require_once "leftcolumn.php"; ?>
      <div class="col-6">
        <div class="card">
          <div class="card-body" style="text-align:center;">
            <h4 class="card-title">Popular Q&A</h4>
            <?php
            $sql = "SELECT datacontent.sr_no,datacontent.title,datacontent.description,users.fn,users.ln FROM datacontent JOIN users ON datacontent.user_id=users.user_id ORDER BY likes DESC LIMIT 10";
            $stmt = $pdo->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($rows as $row)
            {
              echo("<div class=\"card\" style=\"text-align: left;\">");
              echo("<div class=\"card-body\">");
              echo("<h4 class=\"card-title\">".$row['title']."</h4>");
              echo("<h6 class=\"text-muted card-subtitle mb-2\">".$row['fn']." ".$row['ln']."</h6>");
              echo("<p class=\"card-text\">".$row['description']."</p>");
              echo("<a class=\"card-link\" href=\"view.php?x=".$row['sr_no']."\">Go to Post</a>");
              echo("</div>");
              echo("</div>");
            }
            ?>
          </div>
        </div>
      </div>
    <?php require_once "rightcolumn.php"; ?>
  </div>
</div>
<?php require_once "footer.php"; ?>
