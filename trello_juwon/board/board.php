<?php
include '../include/session.php';
include '../include/dbConnect.php';
include '../include/functions.php';

//로그인이 되어있지 않거나 bid가 없다면 메인으로 보냄 testok
if (!isset($_SESSION['uid']) || empty($_GET['bid'])) header("Location: ../main/index.php");

require '../include/boardMember_check.php'; //보드에 가입되어있는지 검사
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="../css/sb-admin-2.css" rel="stylesheet">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>보드</title>
<!--    <link rel="preload" href="../css/sb-admin-2.css" as="style" crossorigin="crossorigin">-->

</head>
<body>
<!-- Topbar -->
<?php
require_once "../include/topbar.php";

$bid = $_GET['bid'];
$uid = $_SESSION['uid'];

echo "<input id='bid' type='hidden' value='{$bid}'>";
echo "<input id='uid' type='hidden' value='{$uid}'>";

//보드멤버 배열
$sql_member = "SELECT member_id FROM board_member WHERE board_id = {$bid}";
if ($res_member = $dbConnect->query($sql_member)) {
    echo '멤버: ';
    while ($row_member = mysqli_fetch_array($res_member)) {
        $member_id = $row_member['member_id'];
        echo $member_id . ' ';
    }
} else {
    echo 'board_board';
    echo mysqli_error($dbConnect);
}

//리스트 fetch
$sql_list = "SELECT * FROM list WHERE bid = {$bid} order by sort_id";
$res_list = $dbConnect->query($sql_list);

if (!$res_list) {
    echo 'board_list';
    mysqli_error($dbConnect);
}


while ($row_list = mysqli_fetch_array($res_list)) {
    $lid = (int)$row_list['list_id'];
    $list_sort = $row_list['sort_id'];
    $list_title = $row_list['list_title'];

    // 리스트 내용물 갯수
    $sql_listCnt = "SELECT COUNT(*) as cnt FROM card WHERE lid = {$lid}";
    $listCnt = mysqli_fetch_array($dbConnect->query($sql_listCnt))['cnt'];
    $listCnt = $listCnt > 9999 ? '9999+' : $listCnt;

    $cards = makeCards($lid, $dbConnect);
    $lists .= makeList($lid,$list_sort,$list_title,$cards,$listCnt);
}
echo "<div id='row' >{$lists}</div>";
?>

<!-- The Modal 모달 -->
<div id="myModal" class="modal">
    <div class="modal-dialog" role="document">
        <!-- Modal content -->
        <div class="modal-content">
            <h4 class="m-0 font-weight-bold text-primary" id="modal_card_title"></h4>
            <!--            <span class="modal-close">&times;</span>-->
            <span id="card_maker"></span>
            <span id="modal_created"></span>
            <p id="card_info"></p>
            <textarea id="summernote_act_create" name="content"></textarea>
            <button type="submit" class="btn btn-primary m-2" id="create_act_btn" value="글쓰기">액티비티 등록</button>
            <ul class="list-group" id="modal_list"><!--액티비티 들어갈곳--></ul>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../js/sb-admin-2.min.js"></script>
<!--<link href="../css/sb-admin-2.css" rel="stylesheet">-->

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<!-- Custom fonts for this template-->
<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
      rel="stylesheet">

<link href="../css/horizontalScroll.css" rel="stylesheet">

<!-- Custom scripts for all pages-->

<!-- include summernote css/js-->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.js"></script>

<!--커스텀 자바스크립트-->
<script src="../js/board.js"></script>
<script src="../js/topbar.js"></script>


</body>


</html>