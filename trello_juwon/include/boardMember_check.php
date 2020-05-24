<?php
//bid uid 비교해서 같은게 있는지 검사 testok
$sql_member = "SELECT * FROM board_member WHERE member_id = '{$uid}' AND board_id = {$_GET['bid']}";
if ($res_member = $dbConnect->query($sql_member)) {
    $num_check = $res_member->num_rows;
    //같은게 없다면 리다이렉트 testok
    if ($num_check == 0) {
        ?>
        <script>
            alert('보드에 가입되지 않았습니다');
            window.location.href = '../main/index.php';
        </script>
        <?php
    }
} else {
    echo 'boardMember_check::'.mysqli_error($dbConnect);
}
