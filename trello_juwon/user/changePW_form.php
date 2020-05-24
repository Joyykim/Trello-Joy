<?php
include '../include/dbConnect.php';
include '../include/session.php';
//세션 검사
if (!isset($uid)) {
    header('Location: ../main/index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Change PW</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.0.min.js"></script>
    <script type="text/javascript" src="../js/mySignUpForm.js"></script>

</head>

<body class="bg-gradient-primary">

<div class="container">
    <a class="mt-5 d-flex align-items-center justify-content-center" href="../main/index.php">
        <h1 style="color: #ffffff">Trello</h1>
    </a>
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                <div class="col-lg-7">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Change PW!</h1>
                        </div>
                        <div class="user" action="changePW.php" method="post">
                            <input type="hidden" value="true" name="isPost">
                            <div class="form-group">
                                <!--이메일-->
                                <input type="password" class="form-control form-control-user" id="currentPW"
                                       name="currentPw" placeholder="현재 비밀번호">
                            </div>
                            <div class="form-group row">
                                <!--비밀번호-->
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user" id="newPW1"
                                           name="newPw" placeholder="신규 비밀번호">
                                </div>
                                <!--비밀번호2-->
                                <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user" id="newPW2"
                                           name="newPw2" placeholder="신규 비밀번호 재입력">
                                    <div class="memberPw2Comment comment"></div>
                                </div>
                                <div id="memberPw2Comment"></div>
                            </div>

                            <button type="submit" id="changePW" class="btn btn-primary btn-user btn-block">비밀번호 교체
                            </button>
                        </div>

                        <hr>

                        <div class="text-center">
                            <a class="small" href="forgot-password_form.php">Forgot Password?</a>
                        </div>
                        <div class="text-center">
                            <a class="small" href="login_form.php">Already have an account? Login!</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Bootstrap core JavaScript-->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../js/sb-admin-2.min.js"></script>
<script>
    $(
        $('#changePW').click(function () {
            let currentPW = $('#currentPW').val();
            let newPW1 = $('#newPW1').val();
            let newPW2 = $('#newPW2').val();
            // alert(currentPW);
            // return;

            if (currentPW == '' || newPW1 == '' || newPW2 == '') {
                alert('빈칸을 채워주세요');
                return;
            }

            $.ajax({
                url: './changePW.php',
                method: 'post',
                data: {
                    currentPW: currentPW,
                    newPW1: newPW1,
                    newPW2: newPW2,
                    isPost:true
                },
                success: function (data) {
                    switch (data) {
                        case 'success':
                            alert('비밀번호가 변경되었습니다');
                            location.href = '../main/index.php';
                            break;
                        case "session_error":
                            alert('로그인 세션이 만료되었습니다');
                            window.location.href = '../main/index.php';
                            break;
                        case 'repeat_error':
                            alert('신규 비밀번호가 일치하지 않습니다');
                            break;
                        case 'pw_incorrect':
                            alert('비밀번호가 틀렸습니다');
                            break;
                        default:
                            alert(data);
                    }
                },
                error: function (request, status, error) {
                    alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                }
            });
        })
    );


    let memberPw1 = $('#newPW1');
    let memberPw2 = $('#newPW2');
    let memberPw2Comment = $('#memberPw2Comment');

    //비밀번호 동일 한지 체크
    $('#newPW1,#newPW2').blur(function () {
        console.log('xxx');
        if (memberPw1.val() === memberPw2.val()) {
            memberPw2Comment.text('비밀번호가 일치합니다.');
            // pwCheck2.val('1');
        } else {
            memberPw2Comment.text('비밀번호가 일치하지 않습니다.');
        }
    });

    function check_same() {

    }


</script>

</body>

</html>
