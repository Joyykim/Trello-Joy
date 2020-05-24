<?php
include '../include/dbConnect.php';
include '../include/session.php';
include '../include/post_session_check.php';
include '../include/functions.php';

$bid = (int)$_POST['bid'];

echo make_board($bid,$dbConnect);