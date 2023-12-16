<?php
session_start();

if(!isset($_SESSION['id']) and !isset($_POST["submit"])) {
    header('location: ../signin_system/auth.php');
    exit();
}

require_once '../db/dbh.inc.php';
require_once 'common_php/functions.inc.php';

$file = $_FILES['file'];
$file_info = pathinfo($_FILES['file']['name']);

$file_id = $_POST['id'];
$category_id = $_POST['category'];
$title = $_POST['title'];
$description = $_POST['description'];
$file_name = $file_info['filename'];
$file_extension = $file_info['extension'];

// Edited: Get the next free file number in the 'files' directory
$files_in_directory = glob('files/*.*');
$file_numbers = array_map(
    function($file) {
        return intval(pathinfo($file, PATHINFO_FILENAME));
    },
    $files_in_directory
);
$next_file_number = max($file_numbers) + 1;

$file_destination = 'files/' . $next_file_number . '.' . $file_extension;
$file_tmp_name = $_FILES['file']['tmp_name'];
move_uploaded_file($file_tmp_name, $file_destination);


if($file_id == '-1'){
    upload_file($con, $_SESSION['id'], $file_destination, $file_name, $file_extension, $title, $description, $category_id);
    exit();
} else {
    // edit_file($con, $file_id, $user_id, $name, $background_color, $text_color);
    exit();
}