<?php
include '../include/dbConnect.php';
include '../include/session.php';
include '../include/post_session_check.php';

$currentPW = $_POST['currentPW'];
$new_PW1 = $_POST['newPW1'];
$new_PW2 = $_POST['newPW2'];

if ($new_PW1 != $new_PW2) {
    echo 'repeat_error';
    exit();
}

$currentPW = hash('sha256',$currentPW);

$sql = "SELECT * FROM user WHERE uid = '{$uid}' AND password = '{$currentPW}'";
$res = $dbConnect->query($sql);
$row = $res->fetch_array();
if ($row != null) {
    $new_PW = hash('sha256',$new_PW1);
    $sql_changePW = "UPDATE user SET password = '{$new_PW}' WHERE uid = '{$uid}'";
    $dbConnect->query($sql_changePW);
    $_SESSION['uid'] = $row['uid'];
    $_SESSION['nickname'] = $row['nickname'];
    echo 'success';
}else{
    echo 'pw_incorrect';
}


