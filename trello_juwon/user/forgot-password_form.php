<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Forgot Password</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

<div class="container">

    <a class="mt-5 d-flex align-items-center justify-content-center" href="../main/index.php">
        <h1 style="color: #ffffff">Trello</h1>
    </a>

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-2">Forgot Your Password?</h1>
                                    <p class="mb-4">We get it, stuff happens. Just enter your email address below and
                                        we'll send you a link to reset your password!</p>
                                </div>
                                <form class="user" action="./sendMail.php" method="post">
                                    <div class="form-group">
                                        <input type="text" name="id" class="form-control form-control-user"
                                               id="tempID" aria-describedby="emailHelp"
                                               placeholder="Enter ID...">
                                    </div>
                                    <div class="form-group">
                                        <input type="email" name="to" class="form-control form-control-user"
                                               id="tempEmail" aria-describedby="emailHelp"
                                               placeholder="Enter Email Address...">
                                    </div>
                                    <button class="btn btn-primary btn-user btn-block" id="reset_pw">Reset Password</button>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="register_form.php">Create an Account!</a>
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
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../js/sb-admin-2.min.js"></script>

<!--<script>-->
<!--    $(-->
<!--        $('#reset_pw').click(function () {-->
<!--            alert(1);-->
<!---->
<!--            let id = $('#tempID').val();-->
<!--            let email = $('#tempEmail').val();-->
<!---->
<!--            if (id == '' || email == '') {-->
<!--                alert('이메일과 ID를 정확히 입력해주세요');-->
<!--                return;-->
<!--            }-->
<!---->
<!--            $.ajax({-->
<!--                url: './sendMail.php',-->
<!--                method: "POST",-->
<!--                data: {to: email, id: id, isPost: true},-->
<!--                success: function (data) {-->
<!--                    switch (data) {-->
<!--                        case 'success':-->
<!--                            alert('임시 비밀번호가 발급되었습니다. 이메일을 확인하세요.');-->
<!--                            // location.href = './login_form.php';-->
<!--                            break;-->
<!--                        case 'ID_error':-->
<!--                            alert('ID가 존재하지 않습니다.');-->
<!--                            break;-->
<!--                        case 'mail_error':-->
<!--                            alert('mailer 에러');-->
<!--                            break;-->
<!--                        default:-->
<!--                            alert(data);-->
<!--                    }-->
<!--                },-->
<!--                error: function (request, status, error) {-->
<!--                    alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);-->
<!--                }-->
<!--            });-->
<!--        });-->
<!---->
<!--    )-->
<!--</script>-->

</body>

</html>
