<?php
include '../include/session.php';
include "../include/dbConnect.php";

$memberId = $_POST['memberId'];
$memberPw = $_POST['memberPw'];
$memberPw2 = $_POST['memberPw2'];
$memberNickName = $_POST['memberNickName'];
$memberEmailAddress = $_POST['memberEmailAddress'];

//PHP에서 유효성 재확인

//아이디 중복검사.
$sql = "SELECT * FROM user WHERE uid = '{$memberId}'";
$res = $dbConnect->query($sql);
if($res->num_rows >= 1){
    echo '이미 존재하는 아이디가 있습니다.';
    exit;
}

//비밀번호 일치하는지 확인
if($memberPw !== $memberPw2){
    echo '비밀번호가 일치하지 않습니다.';
    exit;
}else{
    //비밀번호를 암호화 처리.
    $memberPw = hash("sha256", $memberPw);
}

//닉네임 빈값이 아닌지
if($memberNickName == ''){
    echo '닉네임의 값이 없습니다.';
    exit;
}

//이메일 주소가 올바른지
$checkEmailAddress = filter_var($memberEmailAddress, FILTER_VALIDATE_EMAIL);

if($checkEmailAddress != true){
    echo "올바른 이메일 주소가 아닙니다.";
    exit;
}

//이제부터 넣기 시작
$sql = "INSERT INTO user VALUES('{$memberId}','{$memberPw}','{$memberNickName}','{$memberEmailAddress}');";

if($dbConnect->query($sql)){
    session_start();
    $_SESSION['uid']=$memberId;
    $_SESSION['nickname']=$memberNickName;
    header("Location: ../main/index.php");

}else{
    echo mysqli_errno($dbConnect);
    echo mysqli_error($dbConnect);
}

?>