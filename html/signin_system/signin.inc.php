<?php

if(isset($_POST["submit"]))
{
    $email=$_POST["email"];
    $password=$_POST["password"];

    require_once "../db/dbh.inc.php";
    require_once "functions.inc.php";


    # check the inputed data.
    if(empty("$email")){
        $error_message = urlencode("Моля въведете имейл!");
        header("location: auth.php?error_signin=$error_message");
        exit();
    }
    if(empty("$password")){
        $error_message = urlencode("Моля въведете парола!");
        header("location: auth.php?error_signin=$error_message");
        exit();
    }

    # the data is inputed. log in.
    signin_user($con, $email, $password);
}
else
{
    echo "no submit";
    header("location: auth.php");
    exit();
}