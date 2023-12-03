<?php


function invalid_username($username){
    if(!preg_match("/^[a-zA-Z0-9]*$/", $username))
        return true;
    else
        return false;
}

function username_exists($con, $username) {
    $query = "select * from users where username = ?;";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: auth.php?error_signin=$error_message");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    if($row = mysqli_fetch_assoc($result))
        return $row;
    else {
        mysqli_stmt_close($stmt);
        return false;
    }
}

function invalid_email($email) {
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        return true;
    else
        return false;
}

function passwords_dont_match($password1, $password2) {
    if($password1 != $password2)
        return true;
    else
        return false;
}

function weak_password($password) {
    $lowercase = preg_match('@[a-z]@', $password);
    $uppercase = preg_match('@[A-Z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) 
        return true;
    else
        return false;
    return false;
}