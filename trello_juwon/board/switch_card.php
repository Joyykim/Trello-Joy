<?php
include '../include/dbConnect.php';
include '../include/session.php';
include '../include/post_session_check.php';

$sorting_cid = (int)$_POST['sorting_cid'];
$next_sort_id = (int)$_POST['next_sort_id'];
$sort_lid = (int)$_POST['sort_lid'];

if ($next_sort_id == 0){
    $sql_select_last = "select sort_id from card order by sort_id desc limit 1";
    $res = $dbConnect->query($sql_select_last);
    $next_sort_id = mysqli_fetch_array($res)['sort_id']+1;
}

$sql_update_rest = "UPDATE card SET sort_id = sort_id+1 WHERE sort_id >= {$next_sort_id}";
$sql_update_drag = "UPDATE card SET sort_id = {$next_sort_id} WHERE card_id = {$sorting_cid}";
$sql_update_list = "UPDATE card SET lid = {$sort_lid} WHERE card_id = {$sorting_cid}";

if ($dbConnect->query($sql_update_rest) && $dbConnect->query($sql_update_drag) && $dbConnect->query($sql_update_list)) {
    echo 'success';
} else {
    echo mysqli_errno($dbConnect);
}
