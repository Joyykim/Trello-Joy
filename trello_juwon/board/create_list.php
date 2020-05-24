<?php
include '../include/dbConnect.php';
include '../include/session.php';
include '../include/post_session_check.php';

$bid = (int)$_POST['bid'];
$list_title = $_POST['list_title'];

$content = preg_replace("(\<(/?[^\>]+)\>)", "", $list_title);
$content = preg_replace('/\s+/', "", $content);
$content = str_replace('&nbsp;','',$content);

if ($content == '') {
    echo "content_error";
    exit();
}

if ($list_title == ' ' || $list_title == ''){
    echo "list_title_error";
    exit();
}

$sql_select_last = "select sort_id from list order by sort_id desc limit 1"; //마지막 sort_id 구하기
$last_sort_id = (mysqli_fetch_array($dbConnect->query($sql_select_last)))['sort_id'];
$last_sort_id = ((int)$last_sort_id) + 1;

$sql_create_list = "INSERT INTO list VALUES(0,{$bid},'{$content}',now(),{$last_sort_id})";
$res = $dbConnect->query($sql_create_list);
if ($res) {
    echo 'success';
} else {
    echo mysqli_errno($dbConnect);
    echo mysqli_error($dbConnect);
}