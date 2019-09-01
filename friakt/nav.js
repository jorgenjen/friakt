

var a1 = document.getElementById("a1");
var a2 = document.getElementById("a2");
var a3 = document.getElementById("a3");

a1.addEventListener("mouseover", nav);
a2.addEventListener("mouseover", nav);
a3.addEventListener("mouseover", nav);
function nav(){
  if (this == a1) {
    a2.style.paddingLeft = "0vw";
    a3.style.paddingLeft = "0vw";
  }
  else if(this == a2){
    a1.style.paddingLeft = "0vw";
    a3.style.paddingLeft = "0vw";
  }
  else{
    a1.style.paddingLeft = "0vw";
    a2.style.paddingLeft = "0vw";
  }
  this.style.paddingLeft = "1vw";
}

var active = document.getElementsByClassName("active");

a1.addEventListener("mouseout", standard);
a2.addEventListener("mouseout", standard);
a3.addEventListener("mouseout", standard);
function standard(){
  a1.style.paddingLeft = "0vw";
  a2.style.paddingLeft = "0vw";
  a3.style.paddingLeft = "0vw";
  active[0].style.paddingLeft = "1vw";
}
