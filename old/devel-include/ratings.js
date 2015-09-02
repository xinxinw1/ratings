/***** Ratings Devel *****/

/* requires "events.js" */
/* requires "ajax.js" */
/* requires "prec-math.js" */

function $(a){
  return document.getElementById(a);
}

var n = 0;
var avg = 0;
var currRating = -1;

window.onload = getRatings;

function addClickListener(){
  setListener($("rating-bar"), "click", function (){
    sendRating();
  });
}

function removeClickListener(){
  removeListener($("rating-bar"), "click");
}

function addRatingBarListeners(){
  var a;
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
}

function removeRatingBarListeners(){
  removeListener($("rating-bar"), "mousemove");
  removeListener($("rating-bar"), "mouseleave");
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

function removeAllListeners(){
  removeClickListener();
  removeRatingBarListeners();
  removeDeleteListeners();
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
  var file = "/codes/ratings/devel/ratings-backend.php";
  var params = "type=getRatings&id=" + id;
  var func = function (resp){
    var nums = resp.split("|");
    n = nums[0];
    avg = nums[1];
    avg = roundr(avg, 2);
    dispRating(avg);
    setStatus(getStatusText());
    setYour(getYourText());
    addRatingBarListeners();
    if (username != "")addClickListener();
    if (rating != "-1")addDeleteListeners();
  };
  var type = "GET";
  var async = true;
  
  ajaxRequest(file, params, func, type, async);
}

function sendRating(){
  var currRating = window.currRating;
  if (currRating == -1)return;
  removeAllListeners();
  setYour("Sending...");
  var file = "/codes/ratings/devel/ratings-backend.php";
  var params = "type=sendRating&id=" + id + "&rating=" + currRating;
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
  removeAllListeners();
  setYour("Deleting...");
  var file = "/codes/ratings/devel/ratings-backend.php";
  var params = "type=deleteRating&id=" + id;
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
