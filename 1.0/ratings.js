/***** Ratings 1.0 *****/

function $(a){
  return document.getElementById(a);
}

var n = 0;
var avg = 0;
var currRating = -1;

window.onload = function (){
  setListener($("rating-bar"), "mousemove", function (e){
    if (username != ""){
      e = e || window.event; // IE-ism
      var x = e.clientX;
      
      var offsets = getOffsets($("rating-bottom"));
      x -= offsets.left;
      
      var currRating = x * 5 / 150;
      currRating = ceilTo(0.5, currRating);
      if (currRating > 5)currRating = 5;
      if (currRating < 0)currRating = 0;
      
      window.currRating = currRating;
      
      dispRating(currRating);
      
      if (rating == "-1"){
        setYour("Rate as " + getStarText(currRating));
      } else {
        setYour("Change to " + getStarText(currRating));
      }
    } else {
      setYour("Sign in to rate");
    }
  });
  
  setListener($("rating-bar"), "mouseleave", function (){
    if (username != ""){
      currRating = -1;
      dispRating(avg);
    }
    setYour(getYourText());
  });
  
  if (username != ""){
    setListener($("rating-bar"), "click", function (){
      sendRating();
    });
  }
  
  getRatings();
}

function addDeleteListeners(){
  setListener($("rating-your"), "mouseenter", function (){
    var html = "<a href=\"javascript:void(0)\" onclick=\"deleteRating()\">";
    html += "Delete Rating";
    html += "</a>";
    setYour(html);
  });
  
  setListener($("rating-your"), "mouseleave", function (){
    setYour(getYourText());
  });
}

function removeDeleteListeners(){
  removeListener($("rating-your"), "mouseenter");
  removeListener($("rating-your"), "mouseleave");
}

function dispRating(rating){
  var topWidth = rating * (150 / 5);
  $("rating-top").style.width = topWidth + "px";
}

function setStatus(text){
  $("rating-status").innerHTML = text;
}

function setYour(text){
  $("rating-your").innerHTML = text;
}

function getStatusText(){
  if (n == 0){
    return "No ratings. Click to rate.";
  } else {
    return getStarText(avg) + " | " + getRatingText(n);
  }
}

function getYourText(){
  if (rating == "-1")return "";
  else return "Your rating: " + getStarText(rating);
}

function getStarText(stars){
  if (stars == 1)return "1 star";
  else return stars + " stars";
}

function getRatingText(ratings){
  if (ratings == 1)return "1 rating";
  else return ratings + " ratings";
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

function getRatings(){
  setStatus("Getting Ratings...");
  var file = "ratings.php";
  var params = "type=getRatings";
  var func = function (resp){
    var nums = resp.split("|");
    n = nums[0];
    avg = nums[1];
    avg = roundr(avg, 2);
    dispRating(avg);
    setStatus(getStatusText());
    setYour(getYourText());
    if (rating != "-1")addDeleteListeners();
  };
  var type = "GET";
  var async = true;
  
  ajaxRequest(file, params, func, type, async);
}

function sendRating(){
  var currRating = window.currRating;
  if (currRating == -1)return;
  setYour("Sending...");
  var file = "ratings.php";
  var params = "type=sendRating&rating=" + currRating;
  var func = function (resp){
    if (resp == "1"){
      rating = currRating;
      setYour(getYourText());
      getRatings();
    } else {
      setYour("Error! " + resp);
    }
  };
  var type = "POST";
  var async = true;
  
  ajaxRequest(file, params, func, type, async);
}

function deleteRating(){
  setYour("Deleting...");
  var file = "ratings.php";
  var params = "type=deleteRating";
  var func = function (resp){
    if (resp == "1"){
      rating = "-1";
      setYour(getYourText());
      removeDeleteListeners();
      getRatings();
    } else {
      setYour("Error! " + resp);
    }
  };
  var type = "POST";
  var async = true;
  
  ajaxRequest(file, params, func, type, async);
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
