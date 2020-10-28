<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=AlumniTalks', 'EnterUserName', 'EnterPassword');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$site='http://mysite.com';    /*please change the site to the site that you want to use with*/
