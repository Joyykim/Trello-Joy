<?php
require '../include/session.php';
require '../include/dbConnect.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        #dialog-confirm {
            display: none
        }

        .shut-up {
            float: left;
            display: inline-block;
            margin-top: 1em;
        }
    </style>
</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-laugh-wink"></i>
            </div>
            <div class="sidebar-brand-text mx-3">Trello</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
            <a class="nav-link" href="index.php">
                <i class="fas fa-fw fa-heart"></i>
                <span>새소식</span>
            </a>
            <a class="nav-link" href="index.php?page=invite">
                <i class="fas fa-fw fa-star"></i>
                <span>초대</span>
            </a>
            <div class="nav-link">
                <i class="fas fa-fw fa-table"></i>
                <span>가입한 보드 목록</span>
            </div>
        </li>

        <?php
        // 보드 목록 보기
        if ($logged_in) { ?>
            <div class="">
                <span class="dropdown-item text-white-50 make_board">
                    <i class="fas fa-plus fa-sm fa-fw mr-2 text-gray-400"></i>
                    보드 생성
                </span>
                <?php
                $uid = $_SESSION['uid'];
                $sql_boardList = "select * from board_member where member_id = '{$uid}'";
                if ($res_boardList = $dbConnect->query($sql_boardList)) {
                    while ($row_boardList = mysqli_fetch_array($res_boardList)) {
                        $bid = (int)$row_boardList['board_id'];

                        $sql_board = "SELECT * FROM board WHERE board_id = {$bid}";
                        $board_title = mysqli_fetch_array($dbConnect->query($sql_board))['board_title'];

                        echo '
                            <a class="dropdown-item text-white-50" href="../board/board.php?bid=' . $bid . '">
                                <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                ' . $board_title . '
                            </a>';
                    }
                } else {
                    echo mysqli_error($dbConnect);
                }
                ?>
            </div>
            <?php
        }
        ?>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <?php include "../include/topbar.php" ?>
            <div class="container-fluid" id="userAct_place">
                <?php

                if ($_GET['page'] == 'invite') {
                    include 'load_invite.php';
                }

                if (!isset($_GET['page'])) {
                    include 'load_userAct.php';
                }
                ?>
            </div>
            <!-- /.container-fluid -->
        </div>

        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; trello-joy 2019</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!--모달-->
<div id="dialog-confirm">코로나 조심하세요<br><a href="http://ncov.mohw.go.kr/">자세히 알아보기</a></div>


<!-- Bootstrap core JavaScript-->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../js/sb-admin-2.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="../js/topbar.js"></script>
<script>


    $(document).ready(function () {

        // 새소식 스크롤 페이징
        let userAct_place = $('#userAct_place');
        let actIndex;
        $(document).scroll(function () {
            let maxHeight = document.body.scrollHeight;
            let scroll = $(document).scrollTop();
            let clientHeight = $(window).height();
            console.log('maxHeight ' + maxHeight);
            console.log('scroll ' + scroll);
            console.log('clientHeight ' + clientHeight);

            actIndex = userAct_place.children().length-1;

            if (maxHeight <= clientHeight + scroll) {

                if (window.location.href.includes('page')){
                    $.ajax({
                        url: '../main/load_invite.php',
                        method: "POST",
                        data: {
                            index: actIndex,
                            isPost: true
                        },
                        success: function (data) {
                            if (data === 'session_error') {
                                // alert('로그인 세션이 만료되었습니다');
                                // window.location.href = '../main/index.php';
                                return;
                            }
                            userAct_place.append(data);
                        },
                        error: function (request, status, error) {
                            alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                        }
                    });
                }else {
                    $.ajax({
                        url: '../main/load_userAct.php',
                        method: "POST",
                        data: {
                            index: actIndex,
                            isPost: true
                        },
                        success: function (data) {
                            if (data === 'session_error') {
                                // alert('로그인 세션이 만료되었습니다');
                                // window.location.href = '../main/index.php';
                                return;
                            }
                            userAct_place.append(data);
                            // alert(data);
                        },
                        error: function (request, status, error) {
                            alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                        }
                    });
                }

            }
        });




        //쿠키 검사해서 팝업창 띄우기
        let cookieVal = getCookie("pop");  // 쿠기 가져오기
        if (cookieVal !== 'p=N') {
            $("#dialog-confirm").dialog({
                resizable: false,
                height: 250,
                width: 400,
                title: '코로나 조심',
                create: function (e, ui) {
                    let pane = $(this).dialog("widget").find(".ui-dialog-buttonpane");
                    $("<label class='shut-up' ><input id='stopShow' type='checkbox'/>24시간 동안 보지 않기</label>").prependTo(pane)
                },
                buttons: {
                    "close": function () {
                        $(this).dialog('close');
                        closeWin();
                        if ($("#stopShow").is(":checked")) {
                            setCookie('popup', 'N');
                        }
                    }
                }
            });
        }
    });

    function setCookie(name, value) {
        let todayDate = new Date();
        todayDate.setHours(24);
        document.cookie = name + "=" + escape(value) + "; path=/; expires=" + todayDate.toGMTString() + ";";
    }

    function closeWin() {
        if ($("#option").is(":checked")) {
            setCookie("name", "value");
            window.close();
        }
    }

    function getCookie(name) {
        let Found = false;
        let start, end;
        let i = 0;

        while (i <= document.cookie.length) {
            start = i;
            end = start + name.length;

            if (document.cookie.substring(start, end) == name) {
                Found = true;
                break
            }
            i++
        }

        if (Found == true) {
            start = end + 1;
            end = document.cookie.indexOf(";", start);
            if (end < start)
                end = document.cookie.length;
            return document.cookie.substring(start, end)
        }
        return ""
    }

</script>
</body>

</html>
