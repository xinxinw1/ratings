<!DOCTYPE html>
<html>

<head>
  <title>Ratings 0.2</title>
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
      <span id="rating-text1">No rating. Click to rate.</span>
      <span id="rating-text2"></span>
    </div>
  </div>
</body>

</html>
