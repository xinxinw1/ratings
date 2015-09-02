<?php header("Cache-Control: no-cache"); ?>
<?php session_start(); ?>
<?php
if (isset($_REQUEST['conn']) && $_REQUEST['conn'] == "test"){
  $dbhost = "localhost";
  $dbuser = "ourspe5_ratings";
  $dbpass = "ratings";
  $dbname = "ourspe5_Ratings";
  
  $conn = mysql_connect($dbhost, $dbuser, $dbpass) OR die(mysql_error());
  mysql_select_db($dbname, $conn) OR die(mysql_error());
} else {
  require "../../../tools/conn.php";
}

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
