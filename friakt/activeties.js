// window.onload = function(){
//   console.log("goes like");
//   var xhr = new XMLHttpRequest();
//   xhr.onreadystatechange = function() {
//       if (this.readyState == 4 && this.status == 200) {
//         document.getElementById("activeties") = this.responseText;
//       }
//   };
//   xhr.open("GET", "php/get_act.php", true);
//   xhr.send();
// }

var mapActive = false;
var oldNr = "";
document.onclick = function (e) {
  id = e.target.id;
  var idPart = id.slice(0, 4);

  if (idPart == "show") {
    var nr = id.slice(4, 5);
   if (nr != oldNr) {

     document.getElementById("nr" + nr).style.height = '24vw';
     var div = document.createElement("div");
     div.setAttribute('id', 'map' + nr);
     document.getElementById("nr" + nr).appendChild(div);
     document.getElementById("show" + nr).innerHTML = 'Hide Location<span class="minus"></span>';


     map(nr);
       if (mapActive) {
         //fjern det gamle kartet
         document.getElementById("map" + oldNr).remove();
         document.getElementById("nr" + oldNr).style.height = "12vw";
         document.getElementById("show" + oldNr).innerHTML = 'Show Location<span></span><span></span>';
         }
      mapActive = true;
    }
    else {
      //fjern kartet
      if (mapActive) {
      document.getElementById("map" + nr).remove();
      document.getElementById("nr" + nr).style.height = "12vw";
      document.getElementById("show" + nr).innerHTML = 'Show Location<span></span><span></span>';
      mapActive = false;
    }
    else {
      document.getElementById("nr" + nr).style.height = '24vw';
      var div = document.createElement("div");
      div.setAttribute('id', 'map' + nr);
      document.getElementById("nr" + nr).appendChild(div);
      document.getElementById("show" + nr).innerHTML = 'Hide Location<span class="minus"></span>';
      map(nr);
      mapActive = true;
    }
    }
    oldNr = nr;
  }
  // else if (idPart == "join") {
  //   var joinNr = id.slice(4, 5);
  //
  //   var joining = new XMLHttpRequest();
  //   joining.onreadystatechange = function() {
  //       if (this.readyState == 4 && this.status == 200) {
  //           response = this.responseText;
  //           console.log(response);
  //       }
  //   };
  //   console.log(joinNr);
  //   joining.open("GET", "php/join.php?nr=" + joinNr, true);
  //   joining.send();
  // }
}


function map(z){
  var xmlTrue = false;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
          lat = this.responseText;
          xmlTrue = true;
          setMap();
      }
  };
  xmlhttp.open("GET", "php/kord.php?z=" + z, true);
  xmlhttp.send();
  var httpTrue = false;
  var http = new XMLHttpRequest();
  http.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
          lng = this.responseText;
          httpTrue = true;
          setMap();
      }
  };
  http.open("GET", "php/kord2.php?z=" + z, true);
  http.send();

  function setMap(){
    if (xmlTrue && httpTrue) {
        var myLatlng = new google.maps.LatLng(lat, lng);
        var mapOptions = {
          zoom: 11, // denne setter perspektivet på kartet når siden startes
          center: myLatlng // her er kordinatene
        };
        var map = new google.maps.Map(document.getElementById("map" + z), mapOptions);

        var marker = new google.maps.Marker({
          position: myLatlng
        });

        marker.setMap(map); //setter markøren
      }
    }


}
