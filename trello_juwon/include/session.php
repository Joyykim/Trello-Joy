<?php
session_start();
$logged_in = isset($_SESSION['uid']);
$uid = '';
if ($logged_in) $uid = $_SESSION['uid'];
//echo "<br>session=";
//var_dump($_SESSION);
//echo "<br>cookie=";
//var_dump($_COOKIE);
?>