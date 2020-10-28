<?php
require_once "pdo.php";
if (!isset($_SESSION['user'])) {
  $_SESSION['user']=0;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>AlumniTalks</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand-md sticky-top bg-white">
        <div class="container-fluid"><a class="navbar-brand" href="index.php"><strong>AlumniTalks</strong></a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div
                class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav ml-auto">
                  <?php
                  if($_SESSION['user']!=0)
                  {
                    echo("<li class=\"nav-item\"><a class=\"nav-link active\" href=\"ask.php\">Ask</a></li>");
                    echo("<li class=\"nav-item\"><a class=\"nav-link active\" href=\"mycontent.php\">My Q&amp;A</a></li>");
                    echo("<li class=\"nav-item\"><a class=\"nav-link active\" href=\"myaccount.php\">My Account</a></li>");
                    echo("<li class=\"nav-item\"><a class=\"nav-link active\" href=\"logout.php\">Log Out</a></li>");
                  }
                  if($_SESSION['user']==0)
                  {
                    echo("<li class=\"nav-item\"><a class=\"nav-link active\" href=\"login.php\">Sign In</a></li>");
                    echo("<li class=\"nav-item\"><a class=\"nav-link active\" href=\"signup.php\">Sign Up</a></li>");
                  }
                  ?>
                </ul>
        </div>
        </div>
    </nav>
    <div id="promo">
        <div class="jumbotron">
            <h1>AlumniTalks</h1>
            <p>All your legendary seniors are a click away...</p>
            <p>A product of HackClub VIT Chennai</p>
        </div>
    </div>
