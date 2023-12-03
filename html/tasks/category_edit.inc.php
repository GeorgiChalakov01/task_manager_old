<?php

session_start();


if(!isset($_POST['submit'])){
    header('location: category.php');
    exit();
}



require_once '../db/dbh.inc.php';
require_once 'common_php/functions.inc.php';

$user_id = $_SESSION['id'];
$category_id = $_POST['id'];
$name = $_POST['name'];
$text_color = $_POST['text_color'];
$background_color = $_POST['background_color'];



if($category_id == '-1'){
    create_category($con, $user_id, $name, $background_color, $text_color);
    exit();
} else {
    edit_category($con, $category_id, $user_id, $name, $background_color, $text_color);
}