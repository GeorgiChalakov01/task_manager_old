<?php

if(isset($_POST["submit"]))
{
    $email=$_POST["email"];
    $username=$_POST["username"];
    $password=$_POST["password"];
    $password_repeat=$_POST["password_repeat"];
    $first_name=$_POST["first_name"];
    $last_name=$_POST["last_name"];
    $profile_picture=$_POST["profile_picture"];


    # includes
    require_once '../db/dbh.inc.php';
    require_once 'data_checks.inc.php';
    require_once 'functions.inc.php';


    # Data validation.
    # Check if all fields are populated.
    if(empty($first_name)) {
        $error_message = urlencode("Моля въведете име!");
        header("location: auth.php?error_signup=$error_message");
        exit();
    }
    if(empty($last_name) !== false) {
        $error_message = urlencode("Моля въведете фамилия!");
        header("location: auth.php?error_signup=$error_message");
        exit();
    }
    
    if(empty($email)) {
        $error_message = urlencode("Моля въведете имейл!");
        header("location: auth.php?error_signup=$error_message");
        exit();
    }
    if(invalid_email($email) !== false) {
        $error_message = urlencode("Моля въведете валиден имейл!");
        header("location: auth.php?error_signup=$error_message");
        exit();
    }
    if(get_user_signin_data($con, $email) !== false) {
        $error_message = urlencode("Вече съществува потребителски профил с този имейл!");
        header("location: auth.php?error_signup=$error_message");
        exit();
    }
    
    if(empty($username) !== false) {
        $error_message = urlencode("Моля въведете потребителско име!");
        header("location: auth.php?error_signup=$error_message");
        exit();
    }
    if(invalid_username($username) !== false) {
        $error_message = urlencode("Непозволени символи в потребителското име!");
        header("location: auth.php?error_signup=$error_message");
        exit();
    }
    if(username_exists($con, $username) !== false) {
        $error_message = urlencode("Това потребителско име вече е заето!");
        header("location: auth.php?error_signup=$error_message");
        exit();
    }
    
    if(empty($password) !== false) {
        $error_message = urlencode("Моля въведете парола!");
        header("location: auth.php?error_signup=$error_message");
        exit();
    }
    if(empty($password_repeat) !== false) {
        $error_message = urlencode("Моля потвърдете паролата си!");
        header("location: auth.php?error_signup=$error_message");
        exit();
    }
    if(passwords_dont_match($password, $password_repeat) !== false) {
        $error_message = urlencode("Паролите не съвпадат!");
        header("location: auth.php?error_signup=$error_message");
        exit();
    }
    if(weak_password($password) !== false) {
        $error_message = urlencode("Слаба парола!");
        header("location: auth.php?error_signup=$error_message");
        exit();
    }

    # Data is correct. Create the user.
    create_user($con, $email, $username, $password, $first_name, $last_name, $profile_picture);
} else {
        header("location: auth.php");
        exit();
}