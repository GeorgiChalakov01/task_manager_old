<?php

session_start();


if(!isset($_SESSION['id']) and !isset($_POST["submit"])) {
    header('location: ../signin_system/auth.php');
    exit();
}



require_once '../db/dbh.inc.php';
require_once 'common_php/functions.inc.php';


$task_id = $_GET['id'];
$user_id = $_SESSION['id'];

delete_task($con, $task_id, $user_id);
exit();