/***** Ratings 0.5 *****/

function $(a){
  return document.getElementById(a);
}

var n = 0;
var avg = 0;

window.onload = function (){
  $("rating-overlay").onmousemove = processMouseMove;
  $("rating-overlay").onmouseout = processMouseOut;
  $("rating-overlay").onclick = function (){
    sendRating(rating);
  }
  
  getRating();
}

var rating = -1;
// http://stackoverflow.com/questions/7790725/javascript-track-mouse-position
function processMouseMove(event){
  event = event || window.event; // IE-ism
  rating = getRatingFromMousePosition(event.clientX);
  dispRating(rating);
  $("rating-text1").innerHTML = "Rate as " + rating + "/5";
}

function processMouseOut(){
  rating = -1;
  dispRating(avg);
  $("rating-text1").innerHTML = getRatingText();
}

// http://stackoverflow.com/questions/442404/dynamically-retrieve-the-position-x-y-of-an-html-element
function getOffsets(elem){
  var x = 0; var y = 0;
  while (elem && !isNaN(elem.offsetLeft) && !isNaN(elem.offsetTop)){
    x += elem.offsetLeft - elem.scrollLeft;
    y += elem.offsetTop - elem.scrollTop;
    elem = elem.offsetParent;
  }
  return {top: y, left: x};
}

function ceilTo(n, a){
  return Math.ceil(a / n) * n;
}

function getRatingFromMousePosition(x){
  var offsets = getOffsets($("rating-bottom"));
  x -= offsets.left;
  
  var rating = x * 5 / 150;
  rating = ceilTo(0.5, rating);
  if (rating > 5)rating = 5;
  if (rating < 0)rating = 0;
  
  return rating;
}

function dispRating(rating){
  var topWidth = rating * (150 / 5);
  $("rating-top").style.width = topWidth + "px";
}

function getRatingText(){
  if (n == 0){
    return "No ratings. Click to rate.";
  } else if (n == 1){
    if (avg == 1)return "1 star | 1 rating";
    else return avg + " stars | 1 rating";
  } else {
    if (avg == 1)return "1 star | " + n + " ratings";
    else return avg + " stars | " + n + " ratings";
  }
}

var timeout;
function sendRating(){
  $("rating-text2").innerHTML = "Sending...";
  var file = "ratings.php";
  var params = "type=sendRating&rating=" + rating;
  var func = function (resp){
    if (resp == "1"){
      $("rating-text2").innerHTML = "Successfully Submitted!";
      clearTimeout(timeout);
      timeout = setTimeout(function (){$("rating-text2").innerHTML = "";}, 2000);
      getRating();
    } else {
      $("rating-text2").innerHTML = "Error! " + resp;
    }
  };
  var type = "POST";
  var async = true;
  
  ajaxRequest(file, params, func, type, async);
}

function getRating(){
  $("rating-text1").innerHTML = "Getting Ratings...";
  var file = "ratings.php";
  var params = "type=getRating";
  var func = function (resp){
    var nums = resp.split("|");
    n = nums[0];
    avg = nums[1];
    avg = roundr(avg, 2);
    dispRating(avg);
    $("rating-text1").innerHTML = getRatingText();
  };
  var type = "GET";
  var async = true;
  
  ajaxRequest(file, params, func, type, async);
}

function ajaxRequest(file, params, func, type, async){
  var ajax;
  if (window.XMLHttpRequest){
    ajax = new XMLHttpRequest();
  } else {
    ajax = new ActiveXObject("Microsoft.XMLHTTP");
  }
  
  if (async == undefined)async = true;
  if (async){
    ajax.onreadystatechange = function (){
      if (ajax.readyState == 4){
        if (ajax.status == 200){
          func(ajax.responseText);
        } else if (ajax.status != 0){
          alert("An error has occurred! Status: " + ajax.status);
        }
      }
    }
  }
  
  if (type == "GET"){
    ajax.open("GET", file + "?" + params, true);
    ajax.send();
  } else if (type == "POST"){
    ajax.open("POST", file, true);
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax.send(params);
  }
  
  if (!async)func(ajax.responseText);
}

// import roundr from prec-math 1.7.1
function roundr(a, nprec){
  a = String(a);
  a = trimZeros(a);
  
  var sign = "";
  if (isNeg(a)){
    a = remNeg(a);
    sign = "-";
  }
  
  var alen = a.length;
  var adot = a.indexOf(".");
  if (nprec == undefined)nprec = 0;
  if (!isInt(nprec))throw "Error: roundr(a, nprec): nprec must be an integer";
  
  if (nprec < 0){
    if (adot == -1)adot = alen;
    if (nprec <= -(adot+1))return "0";
    
    if (Number(a[adot+nprec]) >= 5){
      for (var i = adot-1+nprec; i >= 0; i--){
        if (a[i] != '9'){
          a = a.substring(0, i) + (Number(a[i])+1);
          for (var d = adot-(i+1); d >= 1; d--)a += "0";
          break;
        }
      }
      if (i == -1){
        a = "1";
        for (var d = adot; d >= 1; d--)a += "0";
      }
    } else {
      a = a.substring(0, adot+nprec);
      for (var d = -nprec; d >= 1; d--)a += "0";
    }
  } else {
    if (adot == -1 || adot+nprec+1 >= alen)return sign + a;
    
    if (Number(a[adot+nprec+1]) >= 5){
      for (var i = adot+nprec; i >= 0; i--){
        if (a[i] == '.')continue;
        if (a[i] != '9'){
          a = a.substring(0, i) + (Number(a[i])+1);
          for (var d = adot-(i+1); d >= 1; d--)a += "0";
          break;
        }
      }
      if (i == -1){
        a = "1";
        for (var d = adot; d >= 1; d--)a += "0";
      }
    } else {
      a = a.substring(0, adot+nprec+1);
    }
  }
  
  return trimZeros(sign + a);
}

function trimZeros(a){
  a = String(a);
  
  var sign = "";
  if (isNeg(a)){
    a = remNeg(a);
    sign = "-";
  }
  
  for (var i = 0; i <= a.length; i++){
    if (a[i] != '0'){
      if (i == a.length)a = "0";
      else if (i != 0)a = a.substring(i, a.length);
      break;
    }
  }
  
  if (isDec(a)){
    if (a[0] == '.')a = "0" + a;
    for (var i = a.length-1; i >= 0; i--){
      if (a[i] != '0'){
        if (a[i] == '.')a = a.substring(0, i);
        else if (i != a.length-1)a = a.substring(0, i+1);
        break;
      }
    }
  }
  
  if (a != "0")a = sign + a;
  
  return a;
}

function isInt(a){
  a = String(a);
  return (a.indexOf(".") == -1);
}

function isDec(a){
  a = String(a);
  return (a.indexOf(".") != -1);
}

function isNeg(a){
  a = String(a);
  return (a[0] == '-');
}

function remNeg(a){
  return a.substring(1, a.length);
}
