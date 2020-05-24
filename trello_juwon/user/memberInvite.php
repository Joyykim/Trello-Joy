<?php
include '../include/dbConnect.php';
include '../include/session.php';
include '../include/post_session_check.php';

$invite_id = $_POST['invite_id'];
$board_id = $_POST['bid'];

if ($invite_id == $uid){
    echo '자신은 초대할 수 없습니다';
    exit;
}

//유저 검사
$sql_check = "SELECT * FROM user WHERE uid = '{$invite_id}';";
$res = $dbConnect->query($sql_check);

if ($res->num_rows < 1){
    echo '존재하지 않는 유저입니다';
    exit;
}

//보드멤버 중복검사.
$sql_isAlreadyMember = "SELECT * FROM board_member WHERE board_id = {$board_id} and member_id = '{$invite_id}';";
$res = $dbConnect->query($sql_isAlreadyMember);
if ($res){
    if($res->num_rows >= 1){
        echo '이미 보드에 가입된 멤버입니다';
    }else{
        $sql_invite = "INSERT INTO invite VALUES('{$invite_id}','{$uid}',{$board_id},now(),0);";
        $res = $dbConnect->query($sql_invite);

//        for ($i=0;$i<500;$i++){
//            $res = $dbConnect->query($sql_invite);
//        }

        if ($res){
            echo 'success';
        }else{
            echo mysqli_errno($dbConnect);
            echo mysqli_error($dbConnect);
        }
    }
}else{
    echo mysqli_errno($dbConnect);
    echo mysqli_error($dbConnect);
}
