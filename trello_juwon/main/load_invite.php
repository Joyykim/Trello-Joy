<?php
include '../include/dbConnect.php';
include '../include/session.php';

$index = (int)$_POST['index'];

if ($index == 0) {
    echo "
        <div class=\"d-sm-flex align-items-center justify-content-between mb-4\">
            <h1 class=\"h3 mb-0 text-gray-800\"><i class=\"fas fa-fw fa-star\"></i>나에게 온 초대</h1>
        </div>";
    $sql = "SELECT * FROM invite WHERE to_uid = '{$uid}' LIMIT 10";
} else {
    $sql = "SELECT * FROM invite WHERE to_uid = '{$uid}' LIMIT {$index},10";
}

if ($res_invite = $dbConnect->query($sql)) {
    while ($row_invite = mysqli_fetch_array($res_invite)) {

        $bid = (int)$row_invite['bid'];
        $from_uid = $row_invite['from_uid'];
        $checked = (int)$row_invite['checked'];

        $sql_board = "SELECT * FROM board WHERE board_id = {$bid}";
        $board_title = mysqli_fetch_array($dbConnect->query($sql_board))['board_title'];

        if ($checked == 0) {
            echo "
            <div class='card mb-4'>
                <div class='card-body'>
                    <input type='hidden' class='invite_bid' value='" . $bid . "'>
                    <input type='hidden' class='from_uid' value='" . $row_invite['from_uid'] . "'>
                    <input type='hidden' class='invite_bTitle' value='" . $board_title . "'>
                    <div class='small text-gray-500' >" . $row_invite['invited_at'] . " </div>
                    \"" . $row_invite['from_uid'] . "\"님이 \"" . $board_title . "\" 보드로 초대하였습니다.<br>
                    <button class='yes_invite'>수락</button>
                    <button class='no_invite'>거절</button>
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