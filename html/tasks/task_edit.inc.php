<?php
session_start();

if(!isset($_SESSION['id']) and !isset($_POST["submit"])) {
    header('location: ../signin_system/auth.php');
    exit();
}

require_once '../db/dbh.inc.php';
require_once 'common_php/functions.inc.php';


$project_id = $_POST['project_id'];
$task_id = $_POST['id'];
$title = $_POST['title'];
$description = $_POST['description'];
$blocker = $_POST['blocker'];
$duration = $_POST['duration'];
$reminder = $_POST['reminder'];
$deadline = $_POST['deadline'];

if($blocker=='on')$blocker='1';
else $blocker='0';

if($deadline == '')$deadline=null;


if($task_id == '-1'){
    create_task($con, $_SESSION['id'], $project_id, $title, $description, $blocker, $duration, $reminder, $deadline);
    exit();
} else {
    // edit_project($con, $_SESSION['id'], $title, $description, $category_id);
    exit();
}