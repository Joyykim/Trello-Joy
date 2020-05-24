<?php
//post로 왔는지 검사
if (!isset($_POST['isPost'])){
    header('Location: ../main/index.php');
}

//세션 검사
$uid = $_SESSION['uid'];
if (!isset($uid)) {
    echo "session_error";
    exit();
}