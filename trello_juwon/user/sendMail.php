<?php
include '../include/dbConnect.php';
//post로 왔는지 검사
//if (!isset($_POST['isPost'])) header('Location: ../main/index.php');

include './test_mail.php';

$email = $_POST['to'];
$id = $_POST['id'];

$sql_id = "SELECT * FROM user WHERE uid = '{$id}'";
if ($res = $dbConnect->query($sql_id)) {
    $row = $res->fetch_array(MYSQLI_ASSOC);
    if ($row == null) {
        echo 'ID_error';
        exit();
    }
} else {
    echo mysqli_errno($dbConnect);
}

$temp_PW = passwordGenerator();
if (mailer("트렐로", $email, "트렐로 임시 비밀번호발급", $temp_PW) == '성공') {
    $temp_PW = hash('sha256',$temp_PW);
    $sql_pw = "UPDATE user SET password = '{$temp_PW}' WHERE uid = '{$id}'";
    if (!$dbConnect->query($sql_pw)) {
        echo mysqli_errno($dbConnect);
    }
    header('Location: ../user/login_form.php');
} else {
    echo 'mail_error';
}


function passwordGenerator($length = 12)
{
    $counter = ceil($length / 4);
    // 0보다 작으면 안된다.
    $counter = $counter > 0 ? $counter : 1;

    $charList = array(
        array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0"),
        array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"),
        array("!", "@", "#", "%", "^", "&", "*")
    );
    $password = "";
    for ($i = 0; $i < $counter; $i++) {
        $strArr = array();
        for ($j = 0; $j < count($charList); $j++) {
            $list = $charList[$j];

            $char = $list[array_rand($list)];
            $pattern = '/^[a-z]$/';
            // a-z 일 경우에는 새로운 문자를 하나 선택 후 배열에 넣는다.
            if (preg_match($pattern, $char)) array_push($strArr, strtoupper($list[array_rand($list)]));
            array_push($strArr, $char);
        }
        // 배열의 순서를 바꿔준다.
        shuffle($strArr);

        // password에 붙인다.
        for ($j = 0; $j < count($strArr); $j++) $password .= $strArr[$j];
    }
    // 길이 조정
    return substr($password, 0, $length);
}