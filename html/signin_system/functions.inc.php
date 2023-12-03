<?php


function create_user($con, $email, $username, $password, $first_name, $last_name, $profile_picture){
    $query = "call p_create_user(?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: auth.php?error_signup=$error_message");
        exit();
    }
    else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        mysqli_stmt_bind_param($stmt, "ssssss", $email, $username, $hashed_password, $first_name, $last_name, $profile_picture);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        $status_message = urlencode("Регистрацията е успешна!");
        header("location: auth.php?status_signin=$status_message");
        exit();
    }
}

function get_user_signin_data($con, $email) {
    $query = "select id, password_hash from users where email = ?;";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: auth.php?error_signin=$error_message");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    if($row = mysqli_fetch_assoc($result))
        return $row;
    else {
        mysqli_stmt_close($stmt);
        return false;
    }
}

function signin_user($con, $email, $password) {
    $user_signin_data = get_user_signin_data($con, $email);

    if($user_signin_data === false) {
        $error_message = urlencode("Няма потребителски профил с този имейл!");
        header("location: auth.php?error_signin=$error_message");
        exit();
    }

    $hashed_password = $user_signin_data["password_hash"];

    if(password_verify($password, $hashed_password) === false) {
        $error_message = urlencode("Грешна парола!");
        header("location: auth.php?error_signin=$error_message");
        exit();
    }
    else {
        session_start();

        $_SESSION['id'] = $user_signin_data['id'];

        header("location: ../tasks/home.php");
        exit();
    }
}