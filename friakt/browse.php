<?php
  include "php/connect.php";
  include "php/identify.php";
  include "php/get_act.php";
 ?>

<!DOCTYPE html>
<html>
  <head>
    <title>Browse Activeties</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="img/placeholder.png">
    <link rel="stylesheet" href="css/activeties.css">
    <link rel="stylesheet" href="css/nav.css">
  </head>
  <body>
    <div id="nav">
      <?php
      if ($user_gender == "female") {
        echo '<img class="profile" src="img/female_icon.png"/>';
      }
      else {
        echo  '<img class="profile" src="img/male_icon.png"/>';
      }
       ?>
      <h3><?php echo $user_name . ' ' . $user_lastname ?></h3>
        <a id="a1" href="http://localhost/sm/post_act.php"><img src="img/pluss_icon.png"/><p>Create</P></a><br>
        <a id="a2" class="active" href="http://localhost/sm/browse.php"><span><img src="img/browse_icon.png"/><p>Browse</p></span></a><br>
        <a id="a3" href="http://localhost/sm/php/logout.php"><img src="img/leave_icon.png"/><p>Log out</p></a>
    </div>
    <img class="logo" src="img/placeholder.png"/>
    <div id="activeties">
         <?php
          for ($i=0; $i < $acts_affected; $i++) {

            $person = $pdo->prepare("SELECT fornavn, surname, gender FROM networksusers WHERE id = ?");
            $person->execute([($acts_fetched[$i]["poster_id"])]);
            $person_fetched = $person->fetchAll();
            $avatar = "";
            if ($person_fetched[0]["gender"] == "female") {
              $avatar = "img/female_icon.png";
            }
            else {
              $avatar = "img/male_icon.png";
            }
            $class = "";
            if (!empty($acts_fetched[$i]["min_age"])) {
              $class = 'age';
            }
            else {
              $class = "noage";
            }
            $class2 = "";
            if($i == 1 || $i==2 || $i==5|| $i==6|| $i==9|| $i==10|| $i==13|| $i==14|| $i==17|| $i==18|| $i==21|| $i==22){
              $class2 = " color";
            }
            $cost = "nocost";
            if (!empty($acts_fetched[$i]["act_cost"])) {
              $cost = "cost";
            }
            #skal styles forskjellig i forhold til hvor mye informasjon det er
#<span>' . $acts_fetched[$i]["posted_time"] . '</span>
            echo '<div id="nr'. $i . '" class="act ' . $class . $class2 . '">
              <h1 id="overskrift">' . $acts_fetched[$i]["activety"]  .'</h1>
              <span id="profile"><img src="' . $avatar . '"/><p>' . $person_fetched[0]["fornavn"] . ' ' . $person_fetched[0]["surname"] .
              '</p></span>
              <span id="part"><img src="img/participants.png"/> <h4>Participants</h4><br>Minimum: ' . $acts_fetched[$i]["min_joiners"] . '<br>Maximum: ' . $acts_fetched[$i]["max_joiners"] . '</span>';
              if ($class === 'age') {
                echo '<span id="age"><img src="img/age_icon.png"/><h4>Age range</h4><br>From: ' . $acts_fetched[$i]["min_age"] . '<br>To: ' . $acts_fetched[$i]["max_age"] . '</span>';
              }
              echo '<span id="datetime" class=" ' . $cost . '"><img src="img/calendar_icon.png"/>' . $acts_fetched[$i]["act_date"] . '<img src="img/time_icon.png"/>' . $acts_fetched[$i]["act_time"] . '<img src="img/money_icon.png"/>' . $acts_fetched[$i]["act_cost"] . '</span>';
              #echo '<ul id="datetime"><li><img src="img/calendar_icon.png"/>' . $acts_fetched[$i]["act_date"] . '</li><li><img src="img/time_icon.png"/>' . $acts_fetched[$i]["act_time"] . '</li></ul>';



            echo '<h6 id="show'. $i . '">Show Location<span></span><span></span></h6><span id="join' . $i . '" class="joinbtn">Join</span></div>';
          }
         ?>
    </div>
    <script type="text/javascript" src="js/nav.js"></script>
    <script type="text/javascript" src="js/activeties.js"></script>

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGXj1oFD6Da0jWk0PXOUArpkY5DyCHv5A" type="text/javascript"></script>
  </body>
</html>
