<?php
include "php/connect.php";
include "php/identify.php";

if (isset($_POST["post"])) {
$activety = $_POST["activety"];
$minJoiners = $_POST["minpart"];
$maxJoiners = $_POST["maxpart"];
$date = $_POST["date"];
$clock = $_POST["time"];
$amount = $_POST["amount"];
$ageStart = $_POST["agestart"];
$ageEnd = $_POST["ageend"];
$lat1 = $_POST["lat"];
$lng2 = $_POST["lng"];

if (!empty($clock) && !empty($minJoiners) && !empty($maxJoiners) && !empty($date)) {
  if ($minJoiners <= $maxJoiners) {
    if (!empty($_POST["setAge"])) {
      if (!empty($ageStart) && !empty($ageEnd)) {
        if ($ageStart <= $ageEnd) {
          if (!empty($_POST["cost"])) {
            if (!empty($amount)) {
              #med aldersgrense og penger
              $all = $pdo->prepare("INSERT INTO posted_act (activety, lat, lng, min_joiners, max_joiners, act_cost, act_date, act_time, min_age, max_age, poster_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
              $all->execute([$activety, $lat1, $lng2, $minJoiners, $maxJoiners, $amount, $date, $clock, $ageStart, $ageEnd, $user_id]);
              header('location: http://localhost/sm/browse.php');
            }
            else {
              echo "you need to set the amount of money";
            }

          }
          else {
            #med aldersgrense men uten penger
            echo "utenpenger";
            $noMoney = $pdo->prepare("INSERT INTO posted_act (activety, lat, lng, min_joiners, max_joiners, act_date, act_time, min_age, max_age, poster_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $noMoney->execute([$activety, $lat1, $lng2, $minJoiners, $maxJoiners, $date, $clock, $ageStart, $ageEnd, $user_id]);
          }
      }
      else {
        echo "The start age can't be greater than the end age!";
      }
    }
    else if(!empty($ageStart) xor !empty($ageEnd)){
      echo "you need to set both ages";
    }
    else {
     echo "You need to set the ages";
    }
  }
    else {
      if (!empty($_POST["cost"])) {
        if (!empty($amount)) {
          #uten aldersgrense men med penger
          $noAge = $pdo->prepare("INSERT INTO posted_act (activety, lat, lng, min_joiners, max_joiners, act_cost, act_date, act_time, min_age, max_age, poster_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
          $noAge->execute([$activety, $lat1, $lng2, $minJoiners, $maxJoiners, $amount, $date, $clock, $ageStart, $ageEnd, $user_id]);
        }
        else {
          echo "you need to set the amount of money";
        }

      }
      else {
        #uten aldergsgrense og penger
        $nothing = $pdo->prepare("INSERT INTO posted_act (activety, lat, lng, min_joiners, max_joiners, act_date, act_time, poster_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $nothing->execute([$activety, $lat1, $lng2, $minJoiners, $maxJoiners, $date, $clock, $user_id]);
      }
    }
  }
  else {
    echo "Minimum participants can't be more than maximum";
  }
}
else {
  echo "you need to fill out the form";
}
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="img/placeholder.png">
  <title>Create Activity</title>
  <style media="screen">
  *{
    margin: 0;
    padding: 0;
  }
  #wrapper{
    margin-left: 20vw;
  }
  </style>
  <link rel="stylesheet" href="css/nav.css">
</head>
<body>
  <div id="nav">
    <?php
    if ($user_gender == "female") {
      echo '<img class="profile" src="img/female_icon.png"/>';
    }
    else {
      echo  '<img/ class="profile" src="img/male_icon.png"/>';
    }
     ?>
    <h3><?php echo $user_name . ' ' . $user_lastname ?></h3>
      <a id="a1" class="active" href="http://localhost/sm/post_act.php"><img src="img/pluss_icon.png"/><p>Create</P></a><br>
      <a id="a2"  href="http://localhost/sm/browse.php"><span><img src="img/browse_icon.png"/><p>Browse</p></span></a><br>
      <a id="a3" href="http://localhost/sm/php/logout.php"><img src="img/leave_icon.png"/><p>Log out</p></a>
  </div>
  <div id="wrapper">

  <h1>Post a new activity!</h1>
  <form  action="post_act.php" method="post">
    Choose a activety: <select name="activety">
      <option>Choose one!</option>
      <?php
        $table_act = $pdo->prepare("SELECT activety FROM activities");
        $table_act->execute();
        $act_count = $table_act->rowCount();
        $act = $table_act->fetchAll();
        for ($i=0; $i < $act_count ; $i++) {
          echo "<option>" . $act[$i]["activety"] . "</option>";
        }
     ?>

    </select>
    <br>
    Minimum participants: <input id="min" type="range" min="2" max="40" name="minpart" value=""><br>
    Maximum participants: <input id="max" type="range" min="2" max="40" name="maxpart"><br>
    Place the marker where you want the participants to meet up: <span style="color:lightgrey">(be precise)</span>
    <span id="map" style="width:40vw;height:30vh;display:block"></span>
    <input id="lat" type="hidden" name="lat">
    <input id="lng" type="hidden" name="lng">

    Activety costs money? <input type="checkbox" id="cost" name="cost"><br> <!--her skal det være en switch-->
    <span id="amount" style="opacity:0"><input type="number" name="amount" placeholder="the amout in usd  "><br></span>
    The activetys date: <input type="date" name="date" value=""><br>
    The time: <input type="time" name="time" value=""><br>
    Specific age group: <input id="age" type="checkbox" name="setAge" value="true"><br> <!--her skal det være en switch-->
    <span id="ageRange" style="opacity:0">from: <input  type="number" name="agestart" value="">
    To: <input type="number" name="ageend" value=""></span><br>
    <input type="submit" id="post" name="post" value="Post!">

  </form>

</div>
  <script type="text/javascript">
  //google maps start
  function initMap(){
    var myLatlng = new google.maps.LatLng(16.978816035043504, 7.983670359680218);
    var mapOptions = {
      zoom: 4, // denne setter perspektivet på kartet når siden startes
      center: myLatlng // her er kordinatene
    };
    var map = new google.maps.Map(document.getElementById("map"), mapOptions);

    var marker = new google.maps.Marker({
      position: myLatlng,
      label: 'A',
      draggable: true
    });

    marker.setMap(map); //setter markøren

    document.getElementById("post").onmouseover = function(){
      document.getElementById("lat").value = marker.getPosition().lat();
      document.getElementById("lng").value = marker.getPosition().lng();
    }
  }

  // google maps end
    var amount = document.getElementById("amount");
    var cost = document.getElementById("cost");
    cost.onchange = function() {
      if (cost.checked) {
        amount.style.opacity = 1;
      }
      else {
        amount.style.opacity = 0;
      }
    }
    var ageRange = document.getElementById("ageRange");
    var age = document.getElementById("age");
    age.onchange = function () {
      if (age.checked) {
        ageRange.style.opacity = 1;
      }
      else {
        ageRange.style.opacity = 0;
      }
    }


 </script>
 <script type="text/javascript" src="js/nav.js">

 </script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGXj1oFD6Da0jWk0PXOUArpkY5DyCHv5A&callback=initMap" type="text/javascript"></script>
</body>
</html>
