/***** Ratings 0.2 *****/

function $(a){
  return document.getElementById(a);
}

var width = 150;
var divisions = 10;
var partWidth = width / divisions;

// http://stackoverflow.com/questions/7790725/javascript-track-mouse-position
$("rating-overlay").onmousemove = function (event){
  event = event || window.event; // IE-ism
  
  var offsets = getOffsets($("rating-bottom"));
  
  var x = event.clientX - offsets.left;
  var y = event.clientY - offsets.top;
  
  var topWidth = Math.ceil(x / partWidth) * partWidth;
  if (topWidth > width)topWidth = width;
  if (topWidth < 0)topWidth = 0;
  
  $("rating-top").style.width = topWidth + "px";
}

$("rating-overlay").onmouseout = function (){
  $("rating-top").style.width = "0";
}

// http://stackoverflow.com/questions/442404/dynamically-retrieve-the-position-x-y-of-an-html-element
function getOffsets(elem){
  var x = 0;
  var y = 0;
  while (elem && !isNaN(elem.offsetLeft) && !isNaN(elem.offsetTop)){
    x += elem.offsetLeft - elem.scrollLeft;
    y += elem.offsetTop - elem.scrollTop;
    elem = elem.offsetParent;
  }
  return {top: y, left: x};
}
