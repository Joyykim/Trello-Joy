<?php
include '../include/dbConnect.php';
include '../include/session.php';
include '../include/post_session_check.php';


$card_id = (int)$_POST['card_id'];
$edited_title = $_POST['edited_title'];

$content = preg_replace("(\<(/?[^\>]+)\>)", "", $edited_title);
$content = preg_replace('/\s+/', "", $content);
$content = str_replace('&nbsp;', '', $content);

if ($content == '') {
    echo "content_error";
    exit();
}

$sql_update_card = "UPDATE card SET card_title = '{$edited_title}' WHERE card_id = {$card_id}";

if ($dbConnect->query($sql_update_card)) {
    echo 'success';
} else {
    echo mysqli_errno($dbConnect);
}
