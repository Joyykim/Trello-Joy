<?php
include '../include/dbConnect.php';
include '../include/session.php';


if (!isset($_SESSION['uid'])) {
    include '../main/main_page.php';
    exit();
}

$index = (int)$_POST['index'];

if ($index == 0) {
    echo "
        <div class=\"d-sm-flex align-items-center justify-content-between mb-4\">
            <h1 class=\"h3 mb-0 text-gray-800\"><i class=\"fas fa-fw fa-heart\"></i>새소식</h1>
        </div>";
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

        if ($lid == 0 || $bid==0) {
            continue;
        }

        if ($checked) {
//            echo "
//            <div class='card mb-4' style=''>
//                <input class='ua_id' type='hidden' value='$ua_id'>
//                <div class='card-header py-3'>
//                    <a href='../board/board.php?bid=" . $bid . "&cid=" . $cid . "'><h4 class='font-weight-bold text-black-50'>$card_title</h4></a>
//                    <span>from: $fromUid</span>
//                </div>
//                <div class='card-body'>
//                <p class=''>$board_title : $list_title</p>
//                <h5 class=''>$act_cont</h5>
//                </div>
//            </div>
//            ";
        } else {
            echo "
            <div class='card mb-4' style=''>
                <input class='ua_id' type='hidden' value='$ua_id'>
                <div class='card-header py-3'>
                    <a href='../board/board.php?bid=" . $bid . "&cid=" . $cid . "'><h4 class='m-0 font-weight-bold text-primary'>$card_title</h4></a>
                    <span>from: $fromUid</span>
                </div>
                <div class='card-body'>
                <p class='text-primary'>$board_title : $list_title</p>
                <h5 class='text-primary'>$act_cont</h5>
                </div>
            </div>
            ";
        }

    }
} else {
    echo mysqli_error($dbConnect);
}
//echo mysqli_errno($dbConnect);
//echo mysqli_error($dbConnect);


//echo $userActs;