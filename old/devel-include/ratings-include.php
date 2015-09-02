<?php
/* requires "conn.php" */
/* requires "functions.php" */

$username = ""; $id = ""; $rating = "-1";
if (isset($_SESSION['username']) && isset($_GET['id'])){
  $username = $_SESSION['username'];
  $usernamee = mysqlEscape($username);
  $id = $_GET['id'];
  $ide = mysqlEscape($id);
  $sql = "SELECT rating FROM ratings WHERE username='$usernamee' AND item_id='$ide'";
  $result = mysql_query($sql, $conn);
  
  if ($result && $row = mysql_fetch_array($result)){
    $rating = $row['rating'];
  }
}
?>
<?php $updated = time(); ?>
<style type="text/css">
#rating {margin-bottom: 10px;}
#rating-bar {position: relative; overflow: hidden; width: 170px; margin: 0 auto;}
#rating img {display: block; float: left;}
#rating-bottom {margin: 0 10px;}
#rating-top {position: absolute; top: 0; left: 0; width: 0; height: 100%; overflow: hidden; margin: 0 10px;}
#rating-top-stars {width: 150px;}
#rating-text {color: #B5B5B5; font-weight: bold; text-align: center; margin-top: 3px;}
</style>
<script src="/codes/events/2.x/events.js?<?php echo $updated ?>"></script>
<script src="/codes/ajax/1.x/ajax.js?<?php echo $updated ?>"></script>
<script src="/codes/prec-math/1.7.1/prec-math.js?<?php echo $updated ?>"></script>
<script src="/codes/ratings/devel/ratings.js?<?php echo $updated ?>" defer></script>
<script>
username = "<?php echo $username ?>";
rating = "<?php echo $rating ?>";
id = "<?php echo $id ?>";
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
