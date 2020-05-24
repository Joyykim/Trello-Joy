<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <form class="btn">
        <a class="btn btn-primary" type="button" href="../main/index.php">
            <i class="fas fa-fw fa-home"></i>
            <span>Home</span>
        </a>
    </form>

    <form class="btn">
        <?php
        // 보드 목록 보기
        if ($logged_in) { ?>
            <li class="nav-item dropdown no-arrow" style="list-style-type: none">
                <a class="btn btn-primary" type="button" href="#" id="userDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Board</span>
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                     aria-labelledby="userDropdown">
                    <span class="dropdown-item make_board">
                        <i class="fas fa-plus fa-sm fa-fw mr-2 text-gray-400"></i>보드 생성
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
                            <a class="dropdown-item" href="../board/board.php?bid=' . $bid . '">
                                <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                ' . $board_title . '
                            </a>';
                        }
                    } else {
                        echo mysqli_error($dbConnect);
                    }
                    ?>

                </div>
            </li>
            <?php
        }
        ?>
    </form>
    <div class="d-none d-sm-inline-block form-inline ml-md-3 my-2 my-md-0 mw-100 navbar-search"
         style="margin-right: 250px">
        <div class="input-group">


            <?php
            // 보드 멤버 초대
            if ($logged_in && isset($_GET['bid'])) {
                echo '
            <input type="text" class="form-control bg-light border-0 small" placeholder="invite" id="invite_content">
            <div class="input-group-append">
                <button class="btn btn-primary" id="invite_btn" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>';
            }

            ?>
        </div>

    </div>

    <?php
    //보드 이름, 타이틀?
    if (isset($_GET['bid'])) {
        $bid = (int)$_GET['bid'];
        $sql_bTitle = "SELECT * FROM board WHERE board_id = {$bid}";
        $board_title = mysqli_fetch_array($dbConnect->query($sql_bTitle))['board_title'];
        echo '<span class="align-content-lg-center mr-5">' . $board_title . '</span>';
        echo '
            <button class="btn btn-primary" id="add_list" type="button">
                <i class="fas fa-plus fa-sm"></i>리스트 추가
            </button>
            <input style="display: none" type="text" id="list_title_input">
            <button style="display: none" class="btn btn-primary m-1" id="create_list">생성</button>
            <button style="display: none" class="btn btn-primary" id="create_list_cancel">취소</button>
';
    }

    ?>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                 aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small"
                               placeholder="Search for..." aria-label="Search"
                               aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <?php
        // 초대, 새소식
        if ($logged_in) {

            // 초대 쿼리
            $sql_read_invite = "SELECT * FROM invite WHERE to_uid = '{$uid}' and checked = 0 LIMIT 5";
            $res_invite = $dbConnect->query($sql_read_invite);
            $invite_alarm = '';

            // 초대 행 갯수
            $sql_invite_cnt = "SELECT COUNT(*) as cnt FROM invite WHERE checked = 0 AND to_uid = '{$uid}';";
            $invite_cnt = mysqli_fetch_array($dbConnect->query($sql_invite_cnt))['cnt'];
            $invite_cnt = $invite_cnt > 99 ? '99+' : $invite_cnt;

            // 초대 목록
            if ($invite_cnt == 0) {
                $invite_alarm = "
                    <span class='dropdown-item d-flex align-items-center'>
                        <p class=''>초대가 없습니다</p>
                    </span>";
            } else {
                while ($row_invite = mysqli_fetch_array($res_invite)) {
                    $invite_bid = (int)$row_invite['bid'];
                    $sql_board = "SELECT * FROM board WHERE board_id = {$invite_bid}";
                    $res_board = mysqli_fetch_array($dbConnect->query($sql_board));
                    $board_title = $res_board['board_title'];

                    $invite_alarm .= "
                    <span class='dropdown-item d-flex align-items-center invite_alarm'>
                        <div class='mr-3'>
                            <div class='icon-circle bg-success'><i class='fas fa-donate text-white' ></i></div>
                        </div>
                        <div>
                            <input type='hidden' class='from_uid' value='" . $row_invite['from_uid'] . "'>
                            <input type='hidden' class='invite_bTitle' value='" . $board_title . "'>
                            <input type='hidden' class='invite_bid' value='" . $invite_bid . "'>
                            
                            <div class='small text-gray-500' >" . $row_invite['invited_at'] . " </div>
                            \"" . $row_invite['from_uid'] . "\"님이 \"" . $board_title . "\" 보드로 초대하였습니다.
                        </div>
                    </span>";
                }
                $invite_alarm .= "<a class=\"dropdown-item text-center small text-gray-500\" href='../main/index.php?page=invite'>Show All Alerts</a>";
            }


            echo "
                <li class=\"nav-item dropdown no-arrow mx-1\">
                    <a class=\"nav-link dropdown-toggle\" href=\"\" id=\"alertsDropdown\" role=\"button\"
                       data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                        <i class=\"fas fa-bell fa-fw\"></i>
                        <span class=\"badge badge-danger badge-counter\">$invite_cnt</span>
                    </a>
                    <div class=\"dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in\"
                         aria-labelledby=\"alertsDropdown\">
                        <h6 class=\"dropdown-header\">초대</h6>
                        $invite_alarm
                    </div>
                </li>";


            // 새소식
            $sql_read_userAct = "SELECT * FROM userAct WHERE toUid = '{$uid}' LIMIT 5";
            $userActs = '';
            $res_userAct = $dbConnect->query($sql_read_userAct);

            // 새소식 행 갯수
            $sql_userAct_cnt = "SELECT COUNT(*) as cnt FROM userAct WHERE checked = 0 AND toUid = '{$uid}';";
            $userAct_cnt = mysqli_fetch_array($dbConnect->query($sql_userAct_cnt))['cnt'];
            $userAct_cnt = $userAct_cnt > 99 ? '99+' : $userAct_cnt;

            if ($userAct_cnt == 0) {
                $userAct_alarm = "
                    <span class='dropdown-item d-flex align-items-center'>
                        <p class=''>새소식 없습니다</p>
                    </span>";
            } else {
                // 새소식 목록
                while ($row_userAct = mysqli_fetch_array($res_userAct)) {

                    $aid = (int)($row_userAct['aid']);
                    $fromUid = $row_userAct['fromUid'];

                    $sql_act = "SELECT * FROM activity WHERE act_id = {$aid}";
                    $res_act = mysqli_fetch_array($dbConnect->query($sql_act));
                    $act_cont = $res_act['activity_content'];
                    $cid = (int)($res_act['cid']);

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

                    if ($lid == 0 || $bid == 0) {
                        continue;
                    }

                    $userAct_alarm .= "
                    <a class='dropdown-item d-flex align-items-center' href='../board/board.php?bid=$bid'>
                        <div class='mr-3'>
                            <div class='icon-circle bg-success'><i class='fas fa-donate text-white' ></i></div>
                        </div>
                        <div>
                            <div class='small text-gray-500' ><b>$board_title:</b>$list_title</div>
                            <p class='text-black-50 m-0'>$card_title</p>
                            <p class='text-black-50 m-0'>$act_cont</p>
                        </div>
                    </a>";
                }
                $userAct_alarm .= "<a class=\"dropdown-item text-center small text-gray-500\" href=\"../main/index.php\">Read More Messages</a>";
            }

            echo "
            <li class=\"nav-item dropdown no-arrow mx-1\">
                <a class=\"nav-link dropdown-toggle\" href=\"\" id=\"messagesDropdown\" role=\"button\"
                   data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                    <i class=\"fas fa-envelope fa-fw\"></i>
                    <span class=\"badge badge-danger badge-counter\">$userAct_cnt</span>
                </a>
                <div class=\"dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in\"
                     aria-labelledby=\"messagesDropdown\">
                    <h6 class=\"dropdown-header\">새소식</h6>
                    $userAct_alarm
                </div>
            </li>";
        }

        ?>
        <div class="topbar-divider d-none d-sm-block"></div>
        <!-- Nav Item - User Information -->
        <?php

        // 유저 프로필, 비번 바꾸기 등
        if ($logged_in) {
            $user_id = $_SESSION['uid'];
            $user_name = $_SESSION['nickname'];
            ?>
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $user_name ?></span>
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                     aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                    <a class="dropdown-item" href="../user/changePW_form.php">
                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                        비밀번호 교체
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                        Activity Log
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>
            <?php
        } else {
            ?>
            <div class="btn">
                <a class="btn btn-primary m-1" type="button" href="../user/login_form.php">
                    <span>Log-In</span>
                </a>
                <a class="btn btn-primary m-1" type="button" href="../user/register_form.php">
                    <span>Sign-Up</span>
                </a>
            </div>
            <?php
        }
        ?>
    </ul>
    <!-- Logout Modal-->
    <div class="modal" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="../user/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <div id="inviteModal" class="modal">
        <p id="invite_modal_content"></p>
    </div>

    <div id="dialog-makeBoard" class="modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="dialog-makeBoard_content" class="text-primary">보드 생성</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <input class="m-5" type="text">
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">취소</button>
                    <button class="btn btn-primary create_board" type="button" data-dismiss="modal">생성</button>
                </div>
            </div>
        </div>
    </div>
</nav>

