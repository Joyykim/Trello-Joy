$(document).ready(function () {

    $('.userAct').on('click', function () {
        let ua_id = $(this).closest('.card').find('.ua_id').val();
        $.ajax({
            ajax: false,
            async: true,
            url: "../user/check_userAct.php",
            method: 'POST',
            data: {
                ua_id: ua_id,
                isPost: true
            },
            success: function (data) {
                if (data == "session_error") {
                    alert('로그인 세션이 만료되었습니다');
                    window.location.href = '../main/index.php';
                }

            },
            error: function (request, status, error) {
                alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            }
        })
    });

    //보드 생성 create
    $('.make_board').on('click', function () {
        $('#dialog-makeBoard').modal("show");
        //보드 생성
        $('#create_board_btn').on('click', function () {
            let board_title = $('#board_create_text').val();
            $.ajax({
                ajax: false,
                async: true,
                url: "../board/create_board.php",
                method: 'POST',
                data: {
                    board_title: board_title,
                    isPost: true
                },
                success: function (data) {
                    location.href = '../board/board.php?bid=' + data;
                    // $('#dialog-makeBoard').modal("hide");
                },
                error: function (request, status, error) {
                    alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                }
            })
        });
    });

    //초대 글 클릭시 모달 생성
    $('.invite_alarm').click(function () {
        let from_uid = $(this).find('.from_uid').val();
        let invite_bTitle = $(this).find('.invite_bTitle').val();
        let invite_bid = $(this).find('.invite_bid').val();


        $("#inviteModal").dialog({
            title: "보드 초대 수락",
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "수락": function () {
                    $(this).dialog("close");
                    console.log("from_uid:" + from_uid);
                    console.log("invite_bTitle:" + invite_bTitle);
                    console.log("invite_bid:" + invite_bid);
                    // accept_invite(invite_bid,from_uid)
                },
                "거절": function () {
                    $(this).dialog("close");
                    refuse_invite(invite_bid, from_uid);
                }
            }
        });
        $('#invite_modal_content').text('"' + from_uid + '님이 ' + '"' + invite_bTitle + '" 보드로 초대하였습니다');

    });

    $('.yes_invite').on('click', function () {
        let from_uid = $(this).siblings('.from_uid').val();
        let invite_bid = $(this).siblings('.invite_bid').val();
        accept_invite(invite_bid, from_uid);
    });

    $('.no_invite').on('click', function () {
        let from_uid = $(this).siblings('.from_uid').val();
        let invite_bid = $(this).siblings('.invite_bid').val();
        refuse_invite(invite_bid, from_uid);
    })
});

function accept_invite(invite_bid, from_uid) {
    $.ajax({
        url: "../user/accept_invite.php",
        method: "POST",
        //위에서 클릭한 cid 데이터 전송
        data: {invite_bid: invite_bid, from_uid: from_uid, isPost: true},
        success: function (data) {
            switch (data) {
                case "success":
                    window.location.href = '../board/board.php?bid=' + invite_bid;
                    break;
                case "session_error":
                    alert('로그인 세션이 만료되었습니다');
                    window.location.href = '../main/index.php';
                    break;
                default:
                    alert(data);
            }
        },
        error: function (request, status, error) {
            alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });
}


function refuse_invite(invite_bid, from_uid) {
    $.ajax({
        url: "../user/refuse_invite.php",
        method: "POST",
        //위에서 클릭한 cid 데이터 전송
        data: {invite_bid: invite_bid, from_uid: from_uid, isPost: true},
        success: function (data) {
            switch (data) {
                case "success":
                    // window.location.href = '../board/board.php?bid=' + invite_bid;
                    break;
                case "session_error":
                    alert('로그인 세션이 만료되었습니다');
                    window.location.href = '../main/index.php';
                    break;
                default:
                    alert(data);
            }
        },
        error: function (request, status, error) {
            alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });
}