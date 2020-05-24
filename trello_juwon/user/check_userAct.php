<?php
include '../include/dbConnect.php';
include '../include/session.php';
include '../include/post_session_check.php';

$ua_id = (int)$_POST['ua_id'];
var_dump($ua_id);

$sql_check_userAct = "UPDATE userAct SET checked = 1 WHERE ua_id = '{$ua_id}'";

$res = $dbConnect->query($sql_check_userAct);

if ($res){
    echo 'success';
}else{
    echo mysqli_errno($dbConnect);
    echo mysqli_error($dbConnect);
}
