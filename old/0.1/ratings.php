<!DOCTYPE html>
<html>

<head>
  <title>Ratings 0.1</title>
  <meta charset="UTF-8">
  <style type="text/css">
  * {margin: 0; padding: 0; border: 0;}
  img {display: block; float: left;}
  .clear {clear: both;}
  
  #rating {position: relative; width: 150px;}
  #rating-bottom {}
  #rating-top {position: absolute; top: 0; width: 0; overflow: hidden;}
  #rating-top-stars {width: 150px;}
  #rating-overlay {position: absolute; top: 0; width: 100%; height: 30px; background-color: #FFF; opacity: 0; filter: alpha(opacity=0);}
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
      <div class="clear"></div>
    </div>
    <div id="rating-top">
      <div id="rating-top-stars">
        <img src="../images/star-filled.png">
        <img src="../images/star-filled.png">
        <img src="../images/star-filled.png">
        <img src="../images/star-filled.png">
        <img src="../images/star-filled.png">
        <div class="clear"></div>
      </div>
    </div>
    <div id="rating-overlay"></div>
  </div>
</body>

</html>
