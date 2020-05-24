<?php
include '../include/dbConnect.php';
include '../include/session.php';
include '../include/post_session_check.php';


$list_id = (int)$_POST['list_id'];
$edited_title = $_POST['edited_title'];

$content = preg_replace("(\<(/?[^\>]+)\>)", "", $edited_title);
$content = preg_replace('/\s+/', "", $content);
$content = str_replace('&nbsp;', '', $content);

if ($content == '') {
    echo "content_error";
    exit();
}

$sql_update_list = "UPDATE list SET list_title = '{$edited_title}' WHERE list_id = {$list_id}";

if ($dbConnect->query($sql_update_list)) {
    echo 'success';
} else {
    echo mysqli_errno($dbConnect);
}
