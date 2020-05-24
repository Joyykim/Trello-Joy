<?php
include '../include/dbConnect.php';

$index = (int)$_POST['index'];

if ($index == 0) {
    $sql = "SELECT * FROM userAct WHERE toUid = '{$uid}' LIMIT 10";
} else {
    $sql = "SELECT * FROM userAct WHERE toUid = '{$uid}' LIMIT {$index},10";
}

$userActs = '';
if ($res_userAct = $dbConnect->query($sql)) {
    while ($row_act = mysqli_fetch_array($res_userAct)) {

        $aid = (int)($row_act['aid']);
        $ua_id = (int)($row_act['ua_id']);
        $fromUid = $row_act['fromUid'];
        $checked = $row_act['checked'];

        $sql_act = "SELECT * FROM activity WHERE act_id = {$aid}";
        $res_act = mysqli_fetch_array($dbConnect->query($sql_act));
        $act_cont = $res_act['activity_content'];
        $cid = (int)($res_act['cid']);

        //언제 생성된 액티비티인지 검사해 2년전 1달전 등등...
//       $created_at = $res_act['created_at'];
//        $arr = explode(' ',$created_at);
//        $ymd = explode('-',$arr[0]);
//        $hms = explode(':',$arr[1]);
//        var_dump($ymd);
//        var_dump($hms);

        $sql_card = "SELECT * FROM card WHERE card_id = {$cid}";
        $res_card = mysqli_fetch_array($dbConnect->query($sql_card));
        $card_title = $res_card['card_title'];
        $lid = (int)($res_card['lid']);

        $sql_list = "SELECT * FROM list WHERE list_id = {$lid}";
        $res_list = mysqli_fetch_array($dbConnect->query($sql_list));
        $list_title = $res_list['list_title'];
        $bid = (int)($res_list['bid']);

        $sql_board = "SELECT * FROM board WHERE board_id = {$bid}";
        $res_board = mysqli_fetch_array($dbConnect->query($sql_board));
        $board_title = $res_board['board_title'];

        if ($cid == 0 || $lid == 0 || $bid==0) {
            $sql_clear = "DELETE FROM userAct WHERE ua_id = {$ua_id}";
            $dbConnect->query($sql_clear);
        }

    }
} else {
    echo mysqli_error($dbConnect);
}
//echo mysqli_errno($dbConnect);
//echo mysqli_error($dbConnect);


//echo $userActs;