window.onload = function () {
  var all = document.getElementById("all");
  var sliderLeft = 0;
  var sliderStart = setInterval(slider, 4000 );
  var fin;
  function slider() {
    if (sliderLeft == -300) {
      sliderLeft = 0;
      all.style.transition = "0s";
      all.style.marginLeft = sliderLeft;
      setTimeout(slider, 15);
    }
    else {
      all.style.transition = "margin-left 1s";
      sliderLeft -= 100;
      all.style.marginLeft = sliderLeft + "vw";
    }
  }
  //word changer
  var word = document.getElementById("word");
  var ord = "self";

  var words = ["Activities", "Areas", "People", "Sports"];
  words.push(ord); //denner er bare med for å få med array metoder
  var writeTimer;
  setTimeout(del, 250);
  var wordTimer = setInterval(del, 2600);
  var wordCounter = 0;
  var charCounter = 0;
  var e = 0;
  function del() {
    word.style.backgroundColor = "rgba(63, 78, 217, 0.94)";
    word.style.color = "#eee";
    setTimeout(wordMaker, 200);
  }
  function wordMaker(){
    word.innerHTML = " ";
    word.style.backgroundColor = "rgba(63, 78, 217, 0) ";
    word.style.color = "#363535";
    if (wordCounter >= words.length) {
      wordCounter = 0;
    }
    writeTimer = setInterval(write, 150);
  }
  function write() {
    e = words[wordCounter].length;
    print(e);
  }
  function print(e) {
    if (charCounter >= e) {
      clearInterval(writeTimer);
      wordCounter++;
      charCounter = 0;
    }
    else {
      word.innerHTML += words[wordCounter][charCounter];
      charCounter++;
    }
  }
// cursor

  var cursorTimer = setInterval(cursor, 600);
  var down = true;
  function cursor() {
    //console.log("gogogo");
    if (down) {
      word.style.borderRight = "0.1vw solid rgba(54, 53, 53, 0)";
      down = false;
    }
    else {
      word.style.borderRight = "0.1vw solid rgba(54, 53, 53, 1)";
      down = true;
    }
  }
}
