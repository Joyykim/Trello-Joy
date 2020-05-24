<?php
include '../include/dbConnect.php';
include '../include/session.php';
include '../include/post_session_check.php';


$act_id = $_POST['act_id'];

$sql_delete = "DELETE FROM activity WHERE act_id ={$act_id}";

if ($dbConnect->query($sql_delete)) {
    echo 'success';
}else{
    echo mysqli_errno($dbConnect);
    echo mysqli_error($dbConnect);
}