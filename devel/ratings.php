<?php header("Cache-Control: no-cache"); ?>
<?php
require "{$_SERVER['DOCUMENT_ROOT']}/codes/libphp/LibInclude/2.0/LibInclude.php";

LibInclude::loadPHP("GVars", "devel");
LibInclude::loadPHP("Session", "1.x");
Session::start();

LibInclude::loadPHP("Tools", "1.x");

LibInclude::loadPHP("SQLConn", "1.x");

function makeRatingsConn(){
  $host = "localhost";
  $user = "ourspe5_ratings";
  $pass = "ratings";
  $name = "ourspe5_Ratings";
  
  return makeConn($host, $user, $pass, $name);
}

$conn = makeRatingsConn();

if (isset($_POST['username'])){
  $_SESSION['rating-username'] = $_POST['username'];
}

if (isset($_POST['logout'])){
  Session::destroy();
}

LibInclude::loadPHP("Ratings", "devel");
$id = GVars::request('id');
$username = Session::get('rating-username');
$type = GVars::request('type');
$ratings = new Ratings($id, $username, $conn);
if (!is_null($type)){
  $rating = GVars::post('rating');
  $ratings->processBackend($type, $rating);
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
  <?php
  $backend = "/codes/apps/ratings/devel/ratings.php";
  $ratings->display($backend);
  ?>
</body>

</html>
