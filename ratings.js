/***** Ratings 2.0 *****/

/* require tools 4.5.1 */
/* require ajax 4.4.0 */
/* require prec-math 4.3.1 */

var aget = $.aget;
var apost = $.apost;
var rnd = R.rnd;

var n = 0;
var avg = 0;
var currRating = -1;

window.onload = getRatings;

function addClickListener(){
  $("rating-bar").onclick = function (){
    sendRating();
  };
}

function removeClickListener(){
  $("rating-bar").onclick = null;
}

function addRatingBarListeners(){
  $("rating-bar").onmousemove = function (e){
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
  };
  
  $("rating-bar").onmouseleave = function (){
    if (username != ""){
      currRating = -1;
      dispRating(avg);
    }
    setYour(getYourText());
  };
}

function removeRatingBarListeners(){
  $("rating-bar").onmousemove = null;
  $("rating-bar").onmouseleave = null;
}

function addDeleteListeners(){
  $("rating-your").onmouseenter = function (){
    var html = "<a href=\"javascript:void(0)\" onclick=\"deleteRating()\">";
    html += "Delete Rating";
    html += "</a>";
    setYour(html);
  };
  
  $("rating-your").onmouseleave = function (){
    setYour(getYourText());
  };
}

function removeDeleteListeners(){
  $("rating-your").onmouseenter = null;
  $("rating-your").onmouseleave = null;
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
  aget("ratings.php", {type: "getRatings", id: id}, function (resp){
    var nums = resp.split("|");
    n = nums[0];
    avg = nums[1];
    avg = rnd(avg, 2);
    dispRating(avg);
    setStatus(getStatusText());
    setYour(getYourText());
    addRatingBarListeners();
    if (username != "")addClickListener();
    if (rating != "-1")addDeleteListeners();
  });
}

function sendRating(){
  var currRating = window.currRating;
  if (currRating == -1)return;
  removeAllListeners();
  setYour("Sending...");
  apost("ratings.php", {type: "sendRating", id: id, rating: currRating}, function (resp){
    if (resp == "1"){
      rating = currRating;
      setYour(getYourText());
      getRatings();
    } else {
      setYour("Error! " + resp);
    }
  });
}

function deleteRating(){
  removeAllListeners();
  setYour("Deleting...");
  apost("ratings.php", {type: "deleteRating", id: id}, function (resp){
    if (resp == "1"){
      rating = "-1";
      setYour(getYourText());
      removeDeleteListeners();
      getRatings();
    } else {
      setYour("Error! " + resp);
    }
  });
}