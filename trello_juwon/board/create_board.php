<?php
include '../include/dbConnect.php';
include '../include/session.php';
include '../include/post_session_check.php';

$board_title = $_POST['board_title'];

$sql_create_board = "INSERT INTO board VALUES(0,'{$board_title}',now())";

if ($dbConnect->query($sql_create_board)) {
    echo 'success';
} else {
    echo mysqli_errno($dbConnect);
    echo mysqli_error($dbConnect);
}


//$sql_create_board_member = "INSERT INTO board_member VALUES({$bid},'{$uid}')";
//
//if ($dbConnect->query($sql_create_board) && $dbConnect->query($sql_create_board_member)) {
//    echo 'success';
//} else {
//    echo mysqli_error($dbConnect);
//}