<?php

function create_category($con, $user_id, $name, $background_color, $text_color) {
    $query = "call p_create_category(?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: category_edit.php?error=$error_message");
        exit();
    }
    else {
        mysqli_stmt_bind_param($stmt, "isss", $user_id, $name, $background_color, $text_color);
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_close($stmt);

            $status_message = urlencode("Успешно добавена категория!");
            header("location: category.php?status=$status_message");
            exit();
        }
        else {
            $error_message = urlencode("Грешка: ");
            header("location: category_edit.php?status=$error_message" . mysqli_stmt_error($stmt));
            exit();
        }
    }
}