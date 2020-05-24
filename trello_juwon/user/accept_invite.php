<?php
include '../include/dbConnect.php';
include '../include/session.php';
include '../include/post_session_check.php';

$board_id = (int)$_POST['invite_bid'];
$from_uid = $_POST['from_uid'];

$sql_accept_invite = "INSERT INTO board_member VALUES({$board_id},'{$uid}');";
if ($dbConnect->query($sql_accept_invite)){
    $sql_refuse_invite = "DELETE FROM invite WHERE to_uid = '{$uid}' AND bid = {$board_id} AND from_uid = '{$from_uid}'";
    if ($dbConnect->query($sql_refuse_invite)){
        echo 'success';
    }else{
        echo mysqli_errno($dbConnect);
        echo mysqli_error($dbConnect);
    }
}else{
    echo mysqli_errno($dbConnect);
    echo mysqli_error($dbConnect);
}
