<?php
include "../include/session.php";
include "../include/dbConnect.php";

$memberId = $_POST['memberId'];
$memberPw = hash('sha256',$_POST['memberPw']);

$sql = "SELECT * FROM user WHERE uid = '{$memberId}' AND password = '{$memberPw}'";
$res = $dbConnect->query($sql);

$row = $res->fetch_array(MYSQLI_ASSOC);

if ($row != null) {
    session_start();
    $_SESSION['uid'] = $row['uid'];
    $_SESSION['nickname'] = $row['nickname'];
    header("Location: ../main/index.php");
}else{
    ?>
    <script>
        alert('로그인 실패 ID나 비밀번호를 확인해주세요');
        window.location.href = '../user/login_form.php';
    </script>
    <?php
}

?>