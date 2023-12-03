<?php

session_start();


if(!isset($_POST['submit'])){
    header('location: category.php');
    exit();
}



require_once '../db/dbh.inc.php';
require_once 'common_php/functions.inc.php';

$user_id = $_SESSION['id'];
$id = $_POST['id'];
$name = $_POST['name'];
$text_color = $_POST['text_color'];
$background_color = $_POST['background_color'];



if($id == '-1'){
    create_category($con, $user_id, $name, $background_color, $text_color);
} else {
    $query = "call p_edit_category(?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: category_edit.php?error=$error_message");
        exit();
    }
    else {
        mysqli_stmt_bind_param($stmt, "isss", $user_id, $name, $background_color, $text_color);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        header("location: category.php");
        exit();
    }
}