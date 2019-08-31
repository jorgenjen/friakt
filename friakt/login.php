<?php
  include("php/connect.php");

  #lag noe som sierifra om at personer allerede er logget in!

    if (isset($_POST["login"])) {
      $loggedMail = $_POST["email"];
      $loggedPassword = $_POST["password"];

      $mailExistence = $pdo->prepare("SELECT epost FROM networksusers WHERE epost = ?");
      $mailExistence->execute([$loggedMail]);

      if ($mailExistence->fetch()) {
        $passHash = $pdo->prepare("SELECT password FROM networksusers WHERE epost = ?");
        $passHash->execute([$loggedMail]);
        $thePassword = $passHash->fetchAll();

        if (password_verify($loggedPassword, $thePassword[0]['password'])) {
          $chars = array_merge(range('a','z'), range(0,9), range('A','Z'));
          $userCookie = '';
          $lengde = rand(65, 75);
          for ($i=0; $i < $lengde; $i++) {
            $randNum = rand(0, (count($chars)-1));
            $userCookie .= $chars[$randNum];
          }
          $userIdQuery = $pdo->prepare("SELECT id FROM networksusers WHERE epost = ?");
          $userIdQuery->execute([$loggedMail]);
          $userId = ($userIdQuery->fetchAll())[0]["id"];

          $kjeks = $pdo->prepare("INSERT INTO kjeksentillogin(kjekse, netuser_id) VALUES (?, ?)");
          $kjeks->execute([sha1($userCookie), $userId]);
          setcookie("sessionkjeksen", $userCookie, time() + (60*60*24*60), '/');
          setcookie("theUnknownNinjaCookie", "wasd", time() + (60*60*24*40), '/');
          header("refresh:0.1;url=http://localhost/sm/browse.php");
          #echo "your logged in! <br> And this is your cookie: $userCookie <br> med en lengde på" . strlen($userCookie);
        }
        else {
          echo "Incorrect password";
        }
      }
      else {
        echo "The email does not exist.";
      }
    }
 ?>
<h1>Login</h1>
<form  action="login.php" method="post">
  <input type="email" name="email" placeholder="Your email"><br><br>
  <input type="password" name="password" placeholder="Your password"><br><br>
  <input type="submit" name="login" value="Login">
</form>
