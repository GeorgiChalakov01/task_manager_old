<?php

session_start();


if(!isset($_SESSION['id']) and !isset($_POST["submit"])) {
    header('location: ../signin_system/auth.php');
    exit();
}



require_once '../db/dbh.inc.php';
require_once 'common_php/functions.inc.php';


$note_id = $_GET['id'];
$user_id = $_SESSION['id'];

delete_note($con, $note_id, $user_id);
exit();