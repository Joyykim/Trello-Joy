<?php
//$target_dir = "../img/";
//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
//echo $target_file;
//exit();
//
//$uploadOk = 1;
//$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
//
//// Check if image file is a actual image or fake image
//if(isset($_POST["submit"])) {
//    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
//    if($check !== false) {
//        echo "File is an image - " . $check["mime"] . ".";
//        $uploadOk = 1;
//        exit();
//    } else {
//        echo "File is not an image.";
//        $uploadOk = 0;
//        exit();
//    }
//}
//
//
//// Check if file already exists
////if (file_exists($target_file)) {
////    echo "Sorry, file already exists.";
////    $uploadOk = 0;
////    exit();
////}
//
//
//// Check file size
//if ($_FILES["fileToUpload"]["size"] > 5000000) {
//    echo "Sorry, your file is too large.";
//    $uploadOk = 0;
//    exit();
//}
//
//// Allow certain file formats
//if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
//    && $imageFileType != "gif" ) {
//    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
//    $uploadOk = 0;
//    exit();
//}
//
//// Check if $uploadOk is set to 0 by an error
//if ($uploadOk == 0) {
//    echo "Sorry, your file was not uploaded.";
//    exit();
//// if everything is ok, try to upload file
//} else {
//    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
//        echo "<p>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</p>";
//        echo "<br><img src=/uploads/". basename( $_FILES["fileToUpload"]["name"]). ">";
//        echo "<br><button type='button' onclick='history.back()'>돌아가기</button>";
//    } else {
//        echo "<p>Sorry, there was an error uploading your file.</p>";
//        echo "<br><button type='button' onclick='history.back()'>돌아가기</button>";
//    }
//}

if ($_FILES['file']['name']) {
    if (!$_FILES['file']['error']) {
        $name = md5(rand(100, 200));
        $ext = explode('.', $_FILES['file']['name']);
        $filename = $name . '.' . $ext[1];
        $destination = '../img/' . $filename; //change this directory
        $location = $_FILES["file"]["tmp_name"];
        move_uploaded_file($location, $destination);
        echo 'http://localhost:63342/startbootstrap/img/' . $filename; //change this URL
    }
    else
    {
        echo  $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['file']['error'];
    }
}