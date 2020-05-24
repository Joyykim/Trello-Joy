<?php
include_once('../PHPMailer/PHPMailerAutoload.php');

function mailer($fname, $to, $subject, $content, $type=0, $file="", $cc="", $bcc="") {
    if ($type != 1) $content = nl2br($content);
    // type : text=0, html=1, text+html=2
    $mail = new PHPMailer(); // defaults to using php "mail()"
    $mail->IsSMTP();
    //   $mail->SMTPDebug = 2;
    $mail->SMTPSecure = "ssl";
    $mail->SMTPAuth = true;
    $mail->Host = "smtp.naver.com";
    $mail->Port = 465;
    $mail->Username = "kjw11077@naver.com";
    $mail->Password = "rhffna1";
    $mail->CharSet = 'UTF-8';
    $mail->From = "kjw11077@naver.com";
    $mail->FromName = $fname;
    $mail->Subject = $subject;
    $mail->AltBody = ""; // optional, comment out and test
    $mail->msgHTML($content);
    $mail->addAddress($to);
    if ($cc)
        $mail->addCC($cc);
    if ($bcc)
        $mail->addBCC($bcc);
    if ($file != "") {
        foreach ($file as $f) {
            $mail->addAttachment($f['path'], $f['name']);
        }
    }
    if ( $mail->send() ) return "성공";
    else return "실패";
}