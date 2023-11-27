<?php

if(isset($_POST["submit"]))
{
        $username=$_POST["username"];
        $password=$_POST["password"];

        require_once "../db/dbh.inc.php";


        # check the inputed data.
        if(empty("$username") !== false or empty("$password") !== false)
        {
                header("location: auth.php?error=empty_fields");
                exit();
        }

        # the data is inputed. log in.
        login_user($con, $username, $password);
}
else
{
    echo "no submit";
        header("location: auth.php");
        exit();
}