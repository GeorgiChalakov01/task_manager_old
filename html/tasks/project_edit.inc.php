<?php
session_start();

if(!isset($_SESSION['id']) and !isset($_POST["submit"])) {
    header('location: ../signin_system/auth.php');
    exit();
}

require_once '../db/dbh.inc.php';
require_once 'common_php/functions.inc.php';


$note_id = $_POST['id'];
$category_id = $_POST['category'];
$title = $_POST['title'];
$description = $_POST['description'];
$deadline = $_POST['deadline'];


if($note_id == '-1'){
    create_project($con, $_SESSION['id'], $title, $description, $deadline, $category_id);
    exit();
} else {
    // edit_project($con, $_SESSION['id'], $title, $description, $category_id);
    exit();
}