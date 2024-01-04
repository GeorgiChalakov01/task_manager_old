<?php
session_start();

if(!isset($_SESSION['id']) and !isset($_POST["submit"])) {
    header('location: ../signin_system/auth.php');
    exit();
}

require_once '../db/dbh.inc.php';
require_once 'common_php/functions.inc.php';



$file_id = $_POST['id'];
$title = $_POST['title'];
$description = $_POST['description'];

$file = $_FILES['file'];
$file_info = pathinfo($_FILES['file']['name']);
$file_name = $file_info['filename'];
$file_extension = $file_info['extension'];


// Load the chosen categories in the $categories array
$categories = array();
foreach ($_POST as $key => $value) {
    if (preg_match('/^category_\d+/', $key)) {
        $categories[] = substr($key, 9);
    }
}

if(empty($categories)) {
    $error = urlencode("Изберете поне енда категория!");
    header("location: file_edit.php?id=$file_id&error=$error");
    exit();
}


if($file_name) {
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
}


if($file_id == '-1'){
    $file_id = upload_file($con, $_SESSION['id'], $file_name, $file_extension, $file_destination, $title, $description, $category_id);
} else {
    edit_file($con, $file_id, $_SESSION['id'], $file_name, $file_extension, $file_destination, $title, $description);
}

$user_categories = get_categories($con, $_SESSION['id']);
foreach($user_categories as $user_category) {
    if(in_array($user_category['id'], $categories)) {
        append_category($con, $_SESSION['id'], $user_category['id'], $file_id, 'file');
    }
    else {
        unappend_category($con, $_SESSION['id'], $user_category['id'], $file_id, 'file');
    }
}


$status_message = urlencode("Успешно качен файл!");
header("location: files.php?status=$status_message");
exit();