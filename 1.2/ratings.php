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
    $id = mysqlEscape($_GET['id']);
    $sql = "SELECT rating FROM ratings WHERE item_id='$id'";
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
    $id = mysqlEscape($_POST['id']);
    $rating = mysqlEscape($_POST['rating']);
    
    $sql = "SELECT rating FROM ratings WHERE username='$username' AND item_id='$id'";
    $result = mysql_query($sql, $conn);
    
    if (!$result){
      die(mysql_error());
    } else {
      if (mysql_num_rows($result) == 0){
        $sql = "INSERT INTO ratings (username, item_id, rating) VALUES ('$username', '$id', '$rating')";
      } else {
        $sql = "UPDATE ratings SET rating='$rating' WHERE username='$username' AND item_id='$id'";
      }
      $result = mysql_query($sql, $conn);
      
      if (!$result){
        die(mysql_error());
      } else {
        die("1");
      }
    }
  }
  
  if ($_REQUEST['type'] == "deleteRating"){
    if (!isset($_SESSION['username'])){
      die("Sign in to rate");
    }
    $username = mysqlEscape($_SESSION['username']);
    $id = mysqlEscape($_POST['id']);
    $sql = "DELETE FROM ratings WHERE username='$username' AND item_id='$id'";
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

$username = "";
if (isset($_SESSION['username'])){
  $username = $_SESSION['username'];
}

$id = "";
if (isset($_GET['id'])){
  $id = $_GET['id'];
}

$rating = "-1";
if ($username != "" && $id != ""){
  $usernamee = mysqlEscape($username);
  $ide = mysqlEscape($id);
  $sql = "SELECT rating FROM ratings WHERE username='$usernamee' AND item_id='$ide'";
  $result = mysql_query($sql, $conn);
  
  if ($result && $row = mysql_fetch_array($result)){
    $rating = $row['rating'];
  }
}
?>
<?php $updated = "Jul.28.2013.15.47"; ?>
<!DOCTYPE html>
<html>

<head>
  <title>Ratings 1.2</title>
  <meta charset="UTF-8">
  <style type="text/css">
  * {margin: 0; padding: 0; border: 0 none;}
  body {color: #000; text-align: left; font: 13px Tahoma, sans-serif;}
  .clear {clear: both;}
  
  input[type="text"] {border: 1px solid #AAA; background-color: #FFF; font: 13px Tahoma, sans-serif; padding: 1px;}
  input[type="submit"] {border: 1px solid #000; background-color: #C0C0C0; font: 13px Tahoma, sans-serif; padding: 0 5px; color: #000;}
  
  #login-logout, #id {margin: 10px auto; width: 150px;}
  #login-logout form {display: inline;}
  #login-logout input[type="text"] {width: 85px;}
  #id input[type="text"] {width: 146px;}
  
  #rating {margin-bottom: 10px;}
  #rating-bar {position: relative; overflow: hidden; width: 170px; margin: 0 auto;}
  #rating img {display: block; float: left;}
  #rating-bottom {margin: 0 10px;}
  #rating-top {position: absolute; top: 0; left: 0; width: 0; height: 100%; overflow: hidden; margin: 0 10px;}
  #rating-top-stars {width: 150px;}
  #rating-text {color: #B5B5B5; font-weight: bold; text-align: center; margin-top: 3px;}
  </style>
  <script src="/codes/libjs/events/2.x/events.js?<?php echo $updated ?>"></script>
  <script src="/codes/libjs/ajax/1.x/ajax.js?<?php echo $updated ?>"></script>
  <script src="/codes/libjs/prec-math/1.7.1/prec-math.js?<?php echo $updated ?>"></script>
  <script src="ratings.js?<?php echo $updated ?>" defer></script>
  <script>
  username = "<?php echo $username ?>";
  rating = "<?php echo $rating ?>";
  id = "<?php echo $id ?>";
  </script>
</head>

<body>
  <div id="login-logout">
    <form action="ratings.php?id=<?php echo $id ?>" method="post">
      <input type="text" name="username" value="<?php echo $username ?>" placeholder="Username">
    </form>
    <form action="ratings.php?id=<?php echo $id ?>" method="post">
      <input type="submit" name="logout" value="Logout">
    </form>
  </div>
  <div id="id">
    <form action="ratings.php" method="get">
      <input type="text" name="id" value="<?php echo $id ?>" placeholder="Item Id">
    </form>
  </div>
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
