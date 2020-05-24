<?php
include '../include/dbConnect.php';
include '../include/session.php';
include '../include/post_session_check.php';

$cid = (int)$_POST['cid'];

$sql = "SELECT * FROM card WHERE card_id = {$cid}";


if ($res_invite = $dbConnect->query($sql)) {
    if ($res_invite->num_rows > 0){
        echo 'success';
    }else{
        echo 'fail';
    }

} else {
    echo mysqli_errno($dbConnect);
    echo mysqli_error($dbConnect);
}