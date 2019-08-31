<?php
include "php/connect.php";
#pass på at kjekse ikke eksisterer

if (isset($_COOKIE["sessionkjeksen"])) {
try {
    $yourCookie = $_COOKIE["sessionkjeksen"];
    $yourCookieHashed = sha1($yourCookie);

    $compliance = $pdo->prepare("SELECT id FROM kjeksentillogin WHERE kjekse = ?");
    $compliance->execute([$yourCookieHashed]);
    #virker sam man ikke kan bruke fetch og fetctAll på samme query da blir det error!

    if ($compliance->fetch()) {
      $userOnline = $pdo->prepare("SELECT netuser_id FROM kjeksentillogin WHERE kjekse = ?");
      $userOnline->execute([$yourCookieHashed]);
      $online = ($userOnline->fetchAll())[0]["netuser_id"];
    #  echo $online;
      $findName = $pdo->prepare("SELECT fornavn FROM networksusers WHERE id = ?");
      $findName->execute([$online]);
      $navn = ($findName->fetchAll())[0]["fornavn"];
    #echo "<br><br>Hei $navn det er godt å se at du er tilbake";
     #header('location: http://localhost/sm/login.php');

    }
    else {
      echo "Hacker!";
    }
} catch (Exception $e) {
  echo "error: " . $e->getMessage();
}

}
#else {
  if (isset($_POST["create"])) {
   # echo "noe er her";
    $theName = $_POST["name"];
    $theSurname = $_POST["lastname"];
    $theBirth = $_POST["birth"];
    #$male = $_POST["male"];
    #$female = $_POST["female"];
    $theEmail = $_POST["email"];
    $mainPassword = $_POST["password"];
    $checkerPassword = $_POST["repassword"];

    #echo $theBirth;

    $mailExistence = $pdo->prepare("SELECT epost FROM networksusers WHERE epost=?");
    $mailExistence->execute([$theEmail]);
    if (filter_var($theEmail, FILTER_VALIDATE_EMAIL)) { #sjekker om det er rett email format
      if (!$mailExistence->fetch()) { #hvis emailen existerer vil fetch returne true derfor er det '!'
        if ($mainPassword === $checkerPassword) { #sjekker om passordene sammsvarer
          if (!preg_match('/[^a-zA-ZæøåÆØÅ ]/', $theName)) { #sjekker om det er bare bokstaver A til Z og a til z og mellomrom
            if (!preg_match('/[^a-zA-ZæøåÆØÅ ]/', $theSurname)) { #sjekker samme som over for etternavn
              if (strlen($theName) < 70 && strlen($theSurname) < 70) { # 69 blir max
                if (strlen($mainPassword) >= 8 && strlen($mainPassword) <= 60) {
                  if (!empty($theBirth)) {
                    if (!empty($_POST["female"]) xor !empty($_POST["male"])) {
                     if (!empty($_POST["female"])) {
                       $newAccountIncoming = $pdo->prepare("INSERT INTO networksusers (fornavn, surname, epost, password, birth, gender) VALUES (?, ?, ?, ?, ?, ?)");
                       $newAccountIncoming->execute([$theName, $theSurname, $theEmail, password_hash($checkerPassword, PASSWORD_BCRYPT), $theBirth, $_POST["female"]]);

                       header("location: http://localhost/sm/php/setCookie.php?email=$theEmail");
                     }
                     else {
                       $newAccountIncoming = $pdo->prepare("INSERT INTO networksusers (fornavn, surname, epost, password, birth, gender) VALUES (?, ?, ?, ?, ?, ?)");
                       $newAccountIncoming->execute([$theName, $theSurname, $theEmail, password_hash($checkerPassword, PASSWORD_BCRYPT), $theBirth, $_POST["male"]]);

                       header("location: http://localhost/sm/php/setCookie.php?email=$theEmail");
                     }
                    }
                    else {
                      echo "you need to set a gender";
                    }
                   // $newAccountIncoming = $pdo->prepare("INSERT INTO networksusers (fornavn, surname, epost, password, birth) VALUES (?, ?, ?, ?, ?)");
                   // $newAccountIncoming->execute([$theName, $theSurname, $theEmail, password_hash($checkerPassword, PASSWORD_BCRYPT), $theBirth]);
                   //
                   // header("location: http://localhost/sm/php/setCookie.php?email=$theEmail");
                   //
                  # echo "succsess";
                  }
                  else {
                    echo "You need to set a birthdate";
                  }

                  #mail kode som ikke fungerer start
                  // use ..\PHPmailer\PHPmailer\PHPMailer;
                  // use ..\PHPmailer\PHPmailer\Exception;
                  // require '../PHPmailer/src/Exception.php';
                  // require '../PHPmailer/src/PHPMailer.php';
                  // require '../PHPmailer/src/SMTP.php';
                  //
                  // $mail = new PHPMailer();
                  // $mail->isSMTP();
                  // $mail->SMTPAuth = true;
                  // $mail->SMTPSecure = 'ssl';
                  // $mail->Host = 'smtp.gmail.com';
                  // $mail->Port = '465';
                  // $mail->isHTML();
                  // $mail->username = "friaktsbackend@gmail.com";
                  // $mail->Password = "hansruler345";
                  // $mail->SetFrom('no-reply@friakt.com');
                  // $mail->Subject = "hello world";
                  // $mail->Body = "a test email";
                  // $mail->AddAdress("jorgenjensvold@gmail.com");
                  //
                  // $mail->Send();

                  #mailkode som ikke fungerer slutt

                  #if (correct onetime code) {
                    #$newAccountIncoming = $pdo->prepare("INSERT INTO networksusers (fornavn, surname, epost, password) VALUES (?, ?, ?, ?, ?)");
                    #$newAccountIncoming->execute([$theName, $theSurname, $theEmail, password_hash($checkerPassword, PASSWORD_BCRYPT), STR_TO_DATE("$theBirth", "%m %d %Y")]);
                    #echo "succsess";
                  #}

                }
                else {
                  echo "Your password must have a length in the range 8-60";
                }
              }
              else {
                echo "your lastname and firstname cant be over 69 letthers";
              }
            }
            else {
              echo "invalid characters in youre lastname";
            }
          }
          else {
            echo "invalid characters in your firstname";
          }
        }
        else {
          echo "<h2>The passwords does not match!</h2>";
        }
      }
      else {
        echo "Email allready exists!";
      }
    }
    else {
      echo "Wrong Email format!";
    }
  }
#}

 ?>
 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <title>Sign up</title>
     <link rel="shortcut icon" href="img/placeholder.png">
   </head>
   <body>
      <h1>Create account!</h1>
     <form  action="signup.php" method="post">
       <input type="text" name="name" placeholder="Firstname"><br><br>
       <input type="text" name="lastname" placeholder="Lastname"><br><br>
       Birth date: <input type="date" name="birth" placeholder="Date of birth"><br><br>
       Gender: Male <input type="checkbox" name="male" value="male"> female <input type="checkbox" name="female" value="female"><br><br>
       <input type="email" name="email" placeholder="Email"><br><br>
       <input id="pass" type="password" name="password" placeholder="password"><br><br>
       <input id="repass" type="password" name="repassword" placeholder="Retype password"><br> <span id="message"></span> <br>
       <span id="message"></span>
       <input type="submit" name="create" value="Sign up!">
     </form>
     <a href="login.php"><p>I allready have a account</p></a>
     <script type="text/javascript">
       var melding = document.getElementById("message");
       document.getElementById("repass").oninput = function samsvar() {
         if (document.getElementById("pass").value.length > 7) {
             if (document.getElementById("repass").value != document.getElementById("pass").value) {
               melding.innerHTML = "The passwords doesn't match";
              }
             else{
               melding.innerHTML = "The passwords match";
             }
        }
        else {
          melding.innerHTML = "The password must be longer than 7 characters!";
        }
       }
     </script>
   </body>
 </html>
