<?php
$currentPath = dirname(__FILE__).'/';
if (!is_dir($currentPath."songs"))  mkdir($currentPath."songs");

$key = "948vyqn94vnvyv94vnal4a938v9a9alv498v";
$uploaddir = $currentPath.'songs/';

if($_SERVER['REMOTE_ADDR'] !== "145.239.85.31") die("access denied");

if(isset($_FILES['file'])){
    if(!isset($_POST['key']) || $_POST['key'] !== md5($key.basename($_FILES['file']['name'])))
        die("Wrong key");

    $uploadfile = $uploaddir . basename($_FILES['file']['name']);

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
        echo "done";
    } else {
        echo "error";
    }
} else {
    die("no file");
}



