<?php
/* requires "conn.php" */
/* requires "functions.php" */

require_once "codes/aobject/devel/aobject.php";

call_user_func(function () use (&$ratings, &$conn){
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
  
  $ratings = new AObject($ratings, array(
    "username" => $username,
    "rating" => $rating,
    "id" => $id
  ));
});
?>
<style type="text/css">
#rating {margin-bottom: 10px;}
#rating-bar {position: relative; overflow: hidden; width: 170px; margin: 0 auto;}
#rating img {display: block; float: left;}
#rating-bottom {margin: 0 10px;}
#rating-top {position: absolute; top: 0; left: 0; width: 0; height: 100%; overflow: hidden; margin: 0 10px;}
#rating-top-stars {width: 150px;}
#rating-text {color: #B5B5B5; font-weight: bold; text-align: center; margin-top: 3px;}
</style>
<script src="/codes/tools/devel/tools.js"></script>
<script src="/codes/events/3.x/events.js"></script>         
<script src="/codes/ajax/2.x/ajax.js"></script>
<script src="/codes/prec-math/2.x/prec-math.js"></script>
<script src="/codes/ratings/devel-include/ratings.js" defer></script>
<script>
Ratings = {
  <?php if (isset($ratings->test)){ ?>test: true,
  <?php } ?>username: "<?php echo $ratings->username ?>",
  rating: "<?php echo $ratings->rating ?>",
  id: "<?php echo $ratings->id ?>"
};
</script>
<div id="rating">
  <div id="rating-bar">
    <div id="rating-bottom">
      <img src="/codes/ratings/images/star-empty.png">
      <img src="/codes/ratings/images/star-empty.png">
      <img src="/codes/ratings/images/star-empty.png">
      <img src="/codes/ratings/images/star-empty.png">
      <img src="/codes/ratings/images/star-empty.png">
    </div>
    <div class="clear"></div>
    <div id="rating-top">
      <div id="rating-top-stars">
        <img src="/codes/ratings/images/star-filled.png">
        <img src="/codes/ratings/images/star-filled.png">
        <img src="/codes/ratings/images/star-filled.png">
        <img src="/codes/ratings/images/star-filled.png">
        <img src="/codes/ratings/images/star-filled.png">
      </div>
      <div class="clear"></div>
    </div>
  </div>
  <div id="rating-text">
    <div id="rating-status"></div>
    <div id="rating-your"></div>
  </div>
</div>
