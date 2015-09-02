<?php header("Cache-Control: no-cache"); ?>
<?php session_start(); ?>
<?php
$dbhost = "localhost";
$dbuser = "ourspe5_ratings";
$dbpass = "ratings";
$dbname = "ourspe5_Ratings";

$conn = mysql_connect($dbhost, $dbuser, $dbpass) OR die(mysql_error());
mysql_select_db($dbname, $conn) OR die(mysql_error());

function mysqlEscape($str){
  return mysql_real_escape_string($str);
}

if (isset($_REQUEST['type'])){
  if ($_REQUEST['type'] == "getRatings"){
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
  
  if ($_REQUEST['type'] == "sendRating"){
    if (!isset($_SESSION['username'])){
      die("Sign in to rate");
    }
    $username = mysqlEscape($_SESSION['username']);
    $rating = mysqlEscape($_POST['rating']);
    $sql = "INSERT INTO ratings (rating, username) " .
           "VALUES ('$rating', '$username') " .
           "ON DUPLICATE KEY UPDATE rating='$rating'";
    $result = mysql_query($sql, $conn);
    
    if (!$result){
      die(mysql_error());
    } else {
      die("1");
    }
  }
  
  if ($_REQUEST['type'] == "deleteRating"){
    if (!isset($_SESSION['username'])){
      die("Sign in to rate");
    }
    $username = mysqlEscape($_SESSION['username']);
    $sql = "DELETE FROM ratings WHERE username='$username'";
    $result = mysql_query($sql, $conn);
    
    if (!$result){
      die(mysql_error());
    } else {
      die("1");
    }
  }
}

if (isset($_POST['username'])){
  $_SESSION['username'] = $_POST['username'];
}

if (isset($_POST['logout'])){
  session_destroy();
  session_start();
}

$username = ""; $rating = "-1";
if (isset($_SESSION['username'])){
  $username = mysqlEscape($_SESSION['username']);
  $sql = "SELECT rating FROM ratings WHERE username='$username'";
  $result = mysql_query($sql, $conn);
  
  if ($result && $row = mysql_fetch_array($result)){
    $rating = $row['rating'];
  }
  $username = $_SESSION['username'];
}

?>
<!DOCTYPE html>
<html>

<head>
  <title>Ratings 1.0</title>
  <meta charset="UTF-8">
  <style type="text/css">
  * {margin: 0; padding: 0; border: 0 none;}
  body {color: #000; text-align: left; font: 13px Tahoma, sans-serif;}
  .clear {clear: both;}
  
  form {display: inline;}
  input[type="text"] {border: 1px solid #AAA; background-color: #FFF; font: 13px Tahoma, sans-serif; padding: 1px; width: 85px; margin: 10px 0 10px 10px;}
  input[type="submit"] {border: 1px solid #000; background-color: #C0C0C0; font: 13px Tahoma, sans-serif; padding: 0 5px; color: #000;}
  
  #rating {width: 170px;}
  #rating-bar {position: relative; overflow: hidden;}
  #rating img {display: block; float: left;}
  #rating-bottom {margin: 0 10px;}
  #rating-top {position: absolute; top: 0; left: 0; width: 0; height: 100%; overflow: hidden; margin: 0 10px;}
  #rating-top-stars {width: 150px;}
  #rating-text {color: #B5B5B5; font-weight: bold; text-align: center; margin-top: 3px;}
  </style>
  <script src="../../events/2.x/events.js"></script>
  <script src="../../ajax/1.x/ajax.js"></script>
  <script src="ratings.js" defer></script>
  <script>
  username = "<?php echo $username ?>";
  rating = "<?php echo $rating ?>";
  </script>
</head>

<body>
  <form action="ratings.php" method="post">
    <input type="text" name="username" value="<?php echo $username ?>" placeholder="Username">
  </form>
  <form action="ratings.php" method="post">
    <input type="submit" name="logout" value="Logout">
  </form>
  <div id="rating">
    <div id="rating-bar">
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
    </div>
    <div id="rating-text">
      <div id="rating-status"></div>
      <div id="rating-your"></div>
    </div>
  </div>
</body>

</html>
