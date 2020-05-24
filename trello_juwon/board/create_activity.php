<?php
include '../include/dbConnect.php';
include '../include/session.php';
include '../include/post_session_check.php';

$activity_submit = $_POST['activity_submit'];
$cid = $_POST['cid'];
$maker_uid = $_POST['card_maker'];
$uid = $_SESSION['uid'];

preg_match("(\<img(/?[^\>]+)\>)", $activity_submit, $matches);

$content = preg_replace("(\<(/?[^\>]+)\>)", "", $activity_submit);
$content = preg_replace('/\s+/', "", $content);
$content = str_replace('&nbsp;', '', $content);

foreach ($matches as $imgTag) {
    $content .= $imgTag;
}

if ($content == '') {
    echo "content_error";
    exit();
}

if (!isset($cid)) {
    echo "cid_error";
    exit();
}

$sql_last = "select act_id from activity order by act_id desc limit 1";
$res_last = $dbConnect->query($sql_last);
$last_aid = (int)(mysqli_fetch_array($dbConnect->query($sql_last))['act_id']);
$last_aid++;
$sql = "INSERT INTO activity VALUES({$last_aid},{$cid},'{$uid}','{$activity_submit}',now())";

if ($dbConnect->query($sql)) {
    if ($maker_uid != $uid) {
        $sql_userAct = "INSERT INTO userAct VALUES(0,'{$maker_uid}','{$uid}',{$last_aid},0)";

        $res = $dbConnect->query($sql_userAct);
//        for ($i = 0; $i < 500; $i++) {
//            $res = $dbConnect->query($sql_userAct);
//        }

        if ($res) {
            echo 'success';
        } else {
            echo mysqli_errno($dbConnect);
            echo mysqli_error($dbConnect);
        }
    } else {
        echo 'success';
    }
} else {
    echo mysqli_errno($dbConnect);
    echo mysqli_error($dbConnect);
}
