<?php
$currentPath = dirname(__FILE__) . '/';

$key = "948vyqn94vnvyv94vnal4a938v9a9alv498v";
$uploaddir = $currentPath . 'songs/';

if ($_SERVER['REMOTE_ADDR'] !== "145.239.85.31") die("access denied");
if (!isset($_POST['key']) || $_POST['key'] !== md5($key . $_POST['song_id']))
    die("Wrong key");

$filePath = $uploaddir . $_POST['song_id'] . ".mp3";

if (file_exists($filePath)) {
    if (unlink($filePath))
        echo "done";
    else
        echo "error";
} else {
    echo "done";
}



