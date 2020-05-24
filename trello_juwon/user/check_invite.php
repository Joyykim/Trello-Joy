<?php
include '../include/dbConnect.php';
include '../include/session.php';
include '../include/post_session_check.php';

$board_id = (int)$_POST['invite_bid'];
$from_uid = $_POST['from_uid'];

$sql_check_invite = "UPDATE invite SET checked = 1 WHERE to_uid = '{$uid}' AND bid = {$board_id} AND from_uid = '{$from_uid}'";

$res = $dbConnect->query($sql_check_invite);

if ($res){
    echo 'success';
}else{
    echo mysqli_errno($dbConnect);
    echo mysqli_error($dbConnect);
}
