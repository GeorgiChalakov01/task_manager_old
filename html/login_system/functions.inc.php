<?php

function get_user_login_data($con, $username, $email) {
        $query = "select * from users where username = ? or email = ?;";
        $stmt = mysqli_stmt_init($con);

        if(!mysqli_stmt_prepare($stmt, $query)) {
                header("location: auth.php?error=stmt_failed");
                exit();
        }
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        if($row = mysqli_fetch_assoc($result))
                return $row;
        else {
                mysqli_stmt_close($stmt);
                return false;
        }
}

function login_user($con, $uid, $password) {
        $user_login_data = get_user_login_data($con, $uid, $uid);

        if($user_login_data === false) {
                header("location: ../login.php?error=uid_doesnt_exist");
                exit();
        }

        $hashed_password = $user_login_data["password"];

        if(password_verify($password, $hashed_password) === false) {
                header("location: ../login.php?error=wrong_password");
                exit();
        }
        else {
                session_start();

                $_SESSION["id"] = $user_login_data['id'];
                $_SESSION["username"] = $user_login_data['username'];

                date_default_timezone_set($user_login_data['timezone']);
                $_SESSION["date"] = date("Y-m-d");

                header("location: ../../tasks/tasks.php");
                exit();
        }
}