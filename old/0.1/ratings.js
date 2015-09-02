/***** Ratings 0.1 *****/

function $(a){
  return document.getElementById(a);
}

$("rating-overlay").onmousemove = function (event){
  event = event || window.event; // IE-ism
  
  var x = event.clientX - $("rating").offsetLeft;
  var y = event.clientY - $("rating").offsetTop;
  
  $("rating-top").style.width = x + "px";
}

$("rating-overlay").onmouseout = function (){
  $("rating-top").style.width = "0";
}
