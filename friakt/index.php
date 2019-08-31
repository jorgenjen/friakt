<?php
include "php/connect.php";

if (isset($_COOKIE["theUnknownNinjaCookie"])) {
  if (isset($_COOKIE["sessionkjeksen"])) {

    $hashedCookie = sha1($_COOKIE["sessionkjeksen"]);

    $compliance = $pdo->prepare("SELECT id FROM kjeksentillogin WHERE kjekse = ?"); #denne er bare til for å sjekke om kjeksene samsvarer
    $compliance->execute([$hashedCookie]);
    if ($compliance->fetch()) {
      header('location: http://localhost/sm/browse.php');
     }

  }
}


 ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Friakt</title>
    <link rel="shortcut icon" href="img/placeholder.png">
    <link rel="stylesheet" href="css/fa.css">
  </head>
  <body>
    <!-- image slider -->
    <div id="view">
      <div id="tekst">
        <ul class="nav">
          <li></li>
          <li></li>
          <li></li>
        </ul>
        <h1>Explore new</h1>
        <div id="holder"><span id="word"></span></div>
      </div>
      <div id="all">
        <ul>
          <li><img src="img/1.jpg"/></li>
          <li><img src="img/2.jpg"/></li>
          <li><img src="img/3.jpg"/></li>
          <li><img src="img/1.jpg"/></li>
        </ul>
      </div>
    </div>
    <h1 class="header">Welcome to Friakt!</h1>
    <div id="what">
      <img src="img/placeholder.png"/>
      <h2>What is it?</h2>
      <p>Friakt is a community where people who loves sports get together and enjoy their favorite activeties!</p>

    </div>
    <div id="how">
        <img src="img/placeholder.png"/>
      <h2>How does it work?</h2>
      <p>Sign up find a activety you enjoy and meetup!</p>
    </div>
    <a href="signup.php" id="btn">Join the communety!</a>
    <div id="footer">
      <p>All rights reserved 2017 &copy;</p>
    </div>
    <script type="text/javascript" src="js/fa.js">

    </script>
  </body>
</html>
