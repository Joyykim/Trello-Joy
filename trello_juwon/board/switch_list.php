<?php
include '../include/dbConnect.php';
include '../include/session.php';
include '../include/post_session_check.php';

$sorting_lid = (int)$_POST['sorting_lid'];
$next_sort_id = (int)$_POST['next_sort_id'];

if ($next_sort_id == 0){
    $sql_select_last = "select sort_id from list order by sort_id desc limit 1"; //마지막 sort_id 구하기
    $next_sort_id = mysqli_fetch_array($dbConnect->query($sql_select_last))['sort_id'];
}

$sql_update_rest = "UPDATE list SET sort_id = sort_id+1 WHERE sort_id >= {$next_sort_id}"; //마지막 sort_id+1로 설정
$sql_update_drag = "UPDATE list SET sort_id = {$next_sort_id} WHERE list_id = {$sorting_lid}";

if ($dbConnect->query($sql_update_rest) && $dbConnect->query($sql_update_drag)) {
    echo 'success';
} else {
    echo mysqli_errno($dbConnect);
}
