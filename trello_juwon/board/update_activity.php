<?php
include '../include/dbConnect.php';
include '../include/session.php';
include '../include/post_session_check.php';

$cid = $_POST['cid'];
$act_id = (int)$_POST['act_id'];
$edited_content = $_POST['edited_content'];

$pattern = "(\<img(/?[^\>]+)\>)";
preg_match($pattern, $edited_content, $matches);

$content = preg_replace("(\<(/?[^\>]+)\>)", "", $edited_content);
$content = preg_replace('/\s+/', "", $content);
$content = str_replace('&nbsp;','',$content);

foreach ($matches as $imgTag) {
    $content.=$imgTag;
}

if ($content == '') {
    echo "content_error";
    exit();
}


$sql_update_act = "UPDATE activity SET activity_content = '{$edited_content}' WHERE act_id = {$act_id}";

if ($dbConnect->query($sql_update_act)) {
    echo 'success';
}else{
    echo mysqli_errno($dbConnect);
}
