<?php
header("Cache-Control: no-cache");

require "{$_SERVER['DOCUMENT_ROOT']}/codes/libphp/LibInclude/2.0/LibInclude.php";

LibInclude::loadPHP("GVars", "devel");
LibInclude::loadPHP("Session", "1.x");
Session::start();

LibInclude::loadPHP("Tools", "1.x");

LibInclude::loadPHP("SQLConn", "1.x");
LibInclude::loadPHP("SpConn", "1.x", "local");
$conn = makeSpConn();

LibInclude::loadPHP("Ratings", "devel");

$id = GVars::request('id');
$user = Session::get('username');
$rating = GVars::request('rating');
$type = GVars::request('type');

$ratings = new Ratings($id, $user, $conn);

$ratings->processBackend($type, $rating);
