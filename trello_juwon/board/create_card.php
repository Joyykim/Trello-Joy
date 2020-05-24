<?php
include '../include/dbConnect.php';
include '../include/session.php';
include '../include/post_session_check.php';

//data: {lid: lid,card_create_title: card_create_title,: true},

$card_create_title = $_POST['card_create_title'];
$lid = $_POST['lid'];

$content = preg_replace("(\<(/?[^\>]+)\>)", "", $card_create_title);
$content = preg_replace('/\s+/', "", $content);
$content = str_replace('&nbsp;','',$content);

if ($content == '') {
    echo "content_error";
    exit();
}

if ($card_create_title == ' ' || $card_create_title == ''){
    echo "card_title_error";
    exit();
}

if (!isset($lid)){
    echo "lid_error";
    exit();
}

//마지막 정렬 인덱스 구하기
$sql_select_last = "select sort_id from card order by sort_id desc limit 1";
$res = $dbConnect->query($sql_select_last);
$last_sort_id = mysqli_fetch_array($res)['sort_id'];

//카드 생성 sql
$sql_create_card = "INSERT INTO card VALUES(0,{$lid},'{$card_create_title}','',now(),{$last_sort_id}+1,'{$uid}')";

if ($dbConnect->query($sql_create_card)) {
    echo 'success';
}else{
    echo mysqli_errno($dbConnect);
    echo mysqli_error($dbConnect);
}
