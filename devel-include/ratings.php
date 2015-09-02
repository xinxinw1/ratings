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
?>
<?php $updated = time(); ?>
<!DOCTYPE html>
<html>

<head>
  <title>Ratings Devel</title>
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
  </style>
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
  <?php $ratings_test = true; ?>
  <?php require "ratings-include.php" ?>
</body>

</html>
