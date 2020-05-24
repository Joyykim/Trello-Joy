<?php
include '../include/dbConnect.php';
include '../include/session.php';
include '../include/post_session_check.php';

$cid = $_POST['cid'];
$card_title = $_POST['card_title'];

$sql = "SELECT * FROM activity WHERE cid={$cid} ORDER BY act_id DESC";
if ($res_act = $dbConnect->query($sql)) {
    while ($row_act = mysqli_fetch_array($res_act)) {
        $act_id = $row_act['act_id'];
        $uid = $row_act['uid'];
        $created_at = $row_act['created_at'];
        $activity_content = $row_act['activity_content'];
        $activities .= "
            <li>
            <div class='m-2 act-card'>
                <div><strong>{$uid}</strong><br>{$created_at}</div>
                <div class='shadow modal-card act-id' id='{$act_id}'>
                    <textarea name='content' class='summernote_update_act' style='display: none'></textarea>
                    <div class='activity_content_place'>{$activity_content}</div>
                </div>
                <button class='edit_act btn'>수정</button>
                <button class='delete_act btn'>삭제</button>
                <button class='update_act btn' style='display: none'>저장</button>
            </div>
            </li>
            ";
    }
} else {
    echo mysqli_errno($dbConnect);
    echo mysqli_error($dbConnect);
}
echo $activities;
?>

<!--<h4 class="m-0 font-weight-bold text-primary"></h4>-->
