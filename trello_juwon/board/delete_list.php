<?php
include  '../include/dbConnect.php';
include  '../include/session.php';
include  '../include/post_session_check.php';


$list_id = (int)$_POST['list_id'];

$sql_delete = "DELETE FROM list WHERE list_id = {$list_id}";

if ($dbConnect->query($sql_delete)) {
    echo 'success';
}else{
    echo mysqli_errno($dbConnect);
    echo mysqli_error($dbConnect);
}