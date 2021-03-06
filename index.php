<?php header("Cache-Control: no-cache"); ?>
<?php session_start(); ?>
<?php $version = "2.2"; ?>
<?php require "authinfo.php"; ?>
<?php
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) OR die(mysqli_connect_error());

function mysqlEscape($str){
  global $conn;
  return mysqli_real_escape_string($conn, $str);
}

if (isset($_REQUEST['type'])){
  if ($_REQUEST['type'] == "getRatings"){
    $id = mysqlEscape($_GET['id']);
    if ($id == "")die("Need an id");
    $sql = "SELECT rating FROM ratings WHERE item_id='$id'";
    $result = mysqli_query($conn, $sql);
    
    if (!$result){
      die(mysqli_error());
    } else {
      $n = 0;
      $sum = 0;
      while ($rows = mysqli_fetch_assoc($result)){
        $n++;
        $sum += $rows['rating'];
      }
      $avg = ($n != 0)?($sum / $n):0;
      die("success|$n|$avg");
    }
  }
  
  if ($_REQUEST['type'] == "sendRating"){
    if (is_null(getUsername())){
      die("Sign in to rate");
    }
    $username = mysqlEscape(getUsername());
    $id = mysqlEscape($_POST['id']);
    $rating = mysqlEscape($_POST['rating']);
    
    $sql = "SELECT rating FROM ratings WHERE username='$username' AND item_id='$id'";
    $result = mysqli_query($conn, $sql);
    
    if (!$result){
      die(mysqli_error());
    } else {
      if (mysqli_num_rows($result) == 0){
        $sql = "INSERT INTO ratings (username, item_id, rating) VALUES ('$username', '$id', '$rating')";
      } else {
        $sql = "UPDATE ratings SET rating='$rating' WHERE username='$username' AND item_id='$id'";
      }
      $result = mysqli_query($conn, $sql);
      
      if (!$result){
        die(mysqli_error());
      } else {
        die("1");
      }
    }
  }
  
  if ($_REQUEST['type'] == "deleteRating"){
    if (is_null(getUsername())){
      die("Sign in to rate");
    }
    $username = mysqlEscape(getUsername());
    $id = mysqlEscape($_POST['id']);
    $sql = "DELETE FROM ratings WHERE username='$username' AND item_id='$id'";
    $result = mysqli_query($conn, $sql);
    
    if (!$result){
      die(mysqli_error());
    } else {
      die("1");
    }
  }
}

$username = null;
if (!is_null(getUsername())){
  $username = getUsername();
}

$id = "";
if (isset($_GET['id'])){
  $id = $_GET['id'];
}

$rating = "-1";
if (!is_null($username) && $id != ""){
  $usernamee = mysqlEscape($username);
  $ide = mysqlEscape($id);
  $sql = "SELECT rating FROM ratings WHERE username='$usernamee' AND item_id='$ide'";
  $result = mysqli_query($conn, $sql);
  
  if ($result && $row = mysqli_fetch_assoc($result)){
    $rating = $row['rating'];
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Ratings <?php echo $version ?></title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="ratings.css?v=<?php echo $version ?>">
  <script src="lib/tools/tools.js?v=<?php echo $version ?>"></script>
  <script src="lib/ajax/ajax.js?v=<?php echo $version ?>"></script>
  <script src="lib/prec-math/prec-math.js?v=<?php echo $version ?>"></script>
  <script src="ratings.js?v=<?php echo $version ?>" defer></script>
  <script>
  loggedin = <?php echo (!is_null($username))?"true":"false" ?>;
  rating = "<?php echo $rating ?>";
  id = decodeURIComponent("<?php echo rawurlencode($id) ?>");
  </script>
</head>

<body>
  <div id="rating">
    <div id="rating-bar">
      <div id="rating-bottom">
        <img src="images/star-empty.png">
        <img src="images/star-empty.png">
        <img src="images/star-empty.png">
        <img src="images/star-empty.png">
        <img src="images/star-empty.png">
      </div>
      <div class="clear"></div>
      <div id="rating-top">
        <div id="rating-top-stars">
          <img src="images/star-filled.png">
          <img src="images/star-filled.png">
          <img src="images/star-filled.png">
          <img src="images/star-filled.png">
          <img src="images/star-filled.png">
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
