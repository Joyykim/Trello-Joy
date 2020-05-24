<?php
include  '../include/dbConnect.php';
include  '../include/session.php';
include  '../include/post_session_check.php';


$card_id = $_POST['card_id'];

$sql_delete = "DELETE FROM card WHERE card_id = {$card_id}";

if ($dbConnect->query($sql_delete)) {
    echo 'success';
}else{
    echo mysqli_errno($dbConnect);
    echo mysqli_error($dbConnect);
}