<?php
session_start();

if(!isset($_SESSION['id']) and !isset($_POST["submit"])) {
    header('location: ../signin_system/auth.php');
    exit();
}

require_once '../db/dbh.inc.php';
require_once 'common_php/functions.inc.php';


$project_id = $_POST['id'];
$title = $_POST['title'];
$description = $_POST['description'];
$deadline = str_replace("T", " ", $_POST['deadline']) . ":00";



// Load the chosen categories in the $categories array
$categories = array();
foreach ($_POST as $key => $value) {
    if (preg_match('/^category_\d+/', $key)) {
        $categories[] = substr($key, 9);
    }
}

// Load the notes in the $notes array
$notes = array();
foreach ($_POST as $key => $value) {
    if (preg_match('/^note_\d+/', $key)) {
        $notes[] = substr($key, 5);
    }
}

if(empty($categories)) {
    $error = urlencode("Изберете поне енда категория!");
    header("location: note_edit.php?id=$note_id&error=$error");
    exit();
}


if($project_id == '-1'){
    $project_id = create_project($con, $_SESSION['id'], $title, $description, $deadline, $category_id);
    $status_message = urlencode("Успешно създаден проект!");
} else {
    edit_project($con, $_SESSION['id'], $title, $description, $category_id, $project_id);
    $status_message = urlencode("Успешно променен проект!");
}

$user_categories = get_categories($con, $_SESSION['id']);
foreach($user_categories as $user_category) {
    if(in_array($user_category['id'], $categories)) {
        append_category($con, $_SESSION['id'], $user_category['id'], $project_id, 'project');
    }
    else {
        unappend_category($con, $_SESSION['id'], $user_category['id'], $project_id, 'project');
    }
}

$user_notes = get_notes($con, $_SESSION['id']);
foreach($user_notes as $user_note) {
    unattach_note_to_project($con, $_SESSION['id'], $project_id, $user_note['id']);
    if(in_array($user_note['id'], $notes)) {
        attach_note_to_project($con, $_SESSION['id'], $project_id, $user_note['id']);
    }
}


header("location: projects.php?status=$status_message");
exit();