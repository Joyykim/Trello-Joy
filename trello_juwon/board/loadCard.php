<?php
include '../include/dbConnect.php';
include '../include/session.php';
include '../include/post_session_check.php';

$lid = $_POST['lid'];
$index = (int)$_POST['index'];

require_once '../include/functions.php';

$cards = makeCards($lid,$dbConnect,$index);

echo $cards;