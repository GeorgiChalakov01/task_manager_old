<?php

session_start();


if(!isset($_SESSION['id'])){
    header('location: ../signin_system/auth.php');
    exit();
}



require_once '../db/dbh.inc.php';
require_once 'common_php/functions.inc.php';


$category_id = $_GET['id'];
$user_id = $_SESSION['id'];

delete_category($con, $category_id, $user_id);
exit();