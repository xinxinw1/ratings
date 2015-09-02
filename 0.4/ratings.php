<?php header("Cache-Control: no-cache"); ?>
<?php
if (isset($_REQUEST['type'])){
  $dbhost = "localhost";
  $dbuser = "ourspe5_ratings";
  $dbpass = "ratings";
  $dbname = "ourspe5_Ratings";
  
  $conn = mysql_connect($dbhost, $dbuser, $dbpass) OR die(mysql_error());
  mysql_select_db($dbname, $conn) OR die(mysql_error());
  
  function mysqlEscape($str){
    return mysql_real_escape_string($str);
  }
  
  if ($_REQUEST['type'] == "sendRating"){
    $rating = mysqlEscape($_POST['rating']);
    $sql = "INSERT INTO ratings (rating) VALUES ('$rating')";
    $result = mysql_query($sql, $conn);
    
    if (mysql_affected_rows($conn) <= 0){
      die(mysql_error());
    } else {
      die("1");
    }
  }
  
  if ($_REQUEST['type'] == "getRating"){
    $sql = "SELECT rating FROM ratings";
    $result = mysql_query($sql, $conn);
    
    if (!$result){
      die(mysql_error());
    } else {
      $n = 0;
      $sum = 0;
      while ($rows = mysql_fetch_array($result)){
        $n++;
        $sum += $rows['rating'];
      }
      $avg = ($n != 0)?($sum / $n):0;
      die("$n|$avg");
    }
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Ratings Devel</title>
  <meta charset="UTF-8">
  <style type="text/css">
  * {margin: 0; padding: 0; border: 0;}
  body {color: #000; text-align: left; font: 13px Tahoma, sans-serif;}
  img {display: block; float: left;}
  .clear {clear: both; width: 1px; height: 1px;}
  
  #rating {position: relative; width: 170px;}
  #rating-bottom {margin: 0 10px;}
  #rating-top {position: absolute; top: 0; width: 0; overflow: hidden; margin: 0 10px;}
  #rating-top-stars {width: 150px;}
  #rating-overlay {position: absolute; top: 0; width: 100%; height: 30px; background-color: #FFF; opacity: 0; filter: alpha(opacity=0);}
  #rating-text {color: #B5B5B5; font-weight: bold; text-align: center; margin-top: 2px;}
  </style>
  <script src="ratings.js" defer></script>
</head>

<body>
  <div id="rating">
    <div id="rating-bottom">
      <img src="../images/star-empty.png">
      <img src="../images/star-empty.png">
      <img src="../images/star-empty.png">
      <img src="../images/star-empty.png">
      <img src="../images/star-empty.png">
    </div>
    <div class="clear"></div>
    <div id="rating-top">
      <div id="rating-top-stars">
        <img src="../images/star-filled.png">
        <img src="../images/star-filled.png">
        <img src="../images/star-filled.png">
        <img src="../images/star-filled.png">
        <img src="../images/star-filled.png">
      </div>
      <div class="clear"></div>
    </div>
    <div id="rating-overlay"></div>
    <div id="rating-text">
      <div id="rating-text1">No rating. Click to rate.</div>
      <div id="rating-text2"></div>
    </div>
  </div>
</body>

</html>
