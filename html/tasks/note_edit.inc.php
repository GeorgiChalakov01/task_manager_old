<?php
session_start();

if(!isset($_SESSION['id']) and !isset($_POST["submit"])) {
    header('location: ../signin_system/auth.php');
    exit();
}

require_once '../db/dbh.inc.php';
require_once 'common_php/functions.inc.php';


$note_id = $_POST['id'];
$title = $_POST['title'];
$description = $_POST['description'];


// Load the chosen categories in the $categories array
$categories = array();
foreach ($_POST as $key => $value) {
    if (preg_match('/^category_\d+/', $key)) {
        $categories[] = substr($key, 9);
    }
}

// Load the files in the $files array
$files = array();
foreach ($_POST as $key => $value) {
    if (preg_match('/^file_\d+/', $key)) {
        $files[] = substr($key, 5);
    }
}



if(empty($categories)) {
    $error = urlencode("Изберете поне енда категория!");
    header("location: note_edit.php?id=$note_id&error=$error");
    exit();
}


if($note_id == '-1'){
    $note_id = create_note($con, $_SESSION['id'], $title, $description);
    $status_message = urlencode("Успешно създадена бележка!");
} else {
    edit_note($con, $note_id, $_SESSION['id'], $title, $description);
    $status_message = urlencode("Успешно променена бележка!");
}

$user_categories = get_categories($con, $_SESSION['id']);
foreach($user_categories as $user_category) {
    if(in_array($user_category['id'], $categories)) {
        append_category($con, $_SESSION['id'], $user_category['id'], $note_id, 'note');
    }
    else {
        unappend_category($con, $_SESSION['id'], $user_category['id'], $note_id, 'note');
    }
}


$user_files = get_files($con, $_SESSION['id']);
foreach($user_files as $user_file) {
    unattach_file_to_note($con, $_SESSION['id'], $note_id, $user_file['id']);
    if(in_array($user_file['id'], $files)) {
        attach_file_to_note($con, $_SESSION['id'], $note_id, $user_file['id']);
    }
}



header("location: notes.php?status=$status_message");
exit();