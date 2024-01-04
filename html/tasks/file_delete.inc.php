<?php

session_start();


if(!isset($_SESSION['id']) and !isset($_POST["submit"])) {
    header('location: ../signin_system/auth.php');
    exit();
}



require_once '../db/dbh.inc.php';
require_once 'common_php/functions.inc.php';


$file_id = $_GET['id'];
$file_path = $_GET['file_path'];
$user_id = $_SESSION['id'];

delete_file($con, $file_id, $user_id, $file_path);
exit();