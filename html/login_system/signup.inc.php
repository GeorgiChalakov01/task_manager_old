<?php

if(isset($_POST["submit"]))
{
        $first_name=$_POST["first-name"];
        $last_name=$_POST["last-name"];
        $username=$_POST["username"];
        $email=$_POST["email"];
        $password1=$_POST["password1"];
        $password2=$_POST["password2"];
        $timezone =$_POST["timezone"];


        # includes
        require_once '../../includes/dbh.inc.php';
        require_once 'data_checks.inc.php';
        require_once 'actions.inc.php';


        # Data validation.
        # Check if all fields are populated.
        if(is_empty("$first_name") !== false)
        {
                header("location: ../signup.php?error=empty_first_name");
                exit();
        }
        if(is_empty("$last_name") !== false)
        {
                header("location: ../signup.php?error=empty_last_name");
                exit();
        }
        if(is_empty("$username") !== false)
        {
                header("location: ../signup.php?error=empty_username");
                exit();
        }
        if(is_empty("$email") !== false)
        {
                header("location: ../signup.php?error=empty_email");
                exit();
        }
        if(is_empty("$password1") !== false)
        {
                header("location: ../signup.php?error=empty_password");
                exit();
        }
        if(is_empty("$password2") !== false)
        {
                header("location: ../signup.php?error=empty_password_repeat");
                exit();
        }
        if(is_empty("$timezone") !== false)
        {
                header("location: ../signup.php?error=empty_timezone");
                exit();
        }

        # Check the validity of the data.
        if(invalid_username("$username") !== false)
        {
                header("location: ../signup.php?error=invalid_username");
                exit();
        }
        # is used for the email and username
        if(get_user_login_data($con, "$username", $email) !== false)
        {
                header("location: ../signup.php?error=uid_taken");
                exit();
        }
        if(invalid_email("$email") !== false)
        {
                header("location: ../signup.php?error=invalid_email");
                exit();
        }
        if(passwords_dont_match("$password1", "$password2") !== false)
        {
                header("location: ../signup.php?error=passwords_dont_match");
                exit();
        }
        if(weak_password("$password1") !== false)
        {
                header("location: ../signup.php?error=weak_password");
                exit();
        }

        # Data is correct. Create the user.
        create_user($con, $first_name, $last_name, $username, $email, $password1, $timezone);
} else
{
        header("location: ../signup.php");
        exit();
}