<?php

function create_category($con, $user_id, $name, $background_color, $text_color) {
    $query = "call p_create_category(?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: category_edit.php?error=$error_message");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "isss", $user_id, $name, $background_color, $text_color);
    
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);

        $status_message = urlencode("Успешно добавена категория!");
        header("location: categories.php?status=$status_message");
        exit();
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: category_edit.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}

function edit_category($con, $category_id, $user_id, $name, $background_color, $text_color) {
    $query = "call p_edit_category(?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: category_edit.php?error=$error_message");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "iisss", $category_id, $user_id, $name, $background_color, $text_color);
    
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);

        $status_message = urlencode("Успешно променена категория!");
        header("location: categories.php?status=$status_message");
        exit();
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: category_edit.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}

function get_categories($con, $user_id) {
    $query = "
        select 
            id,
            name,
            background_color,
            text_color
        from 
            categories
        where 
            owner_id = ?
        order by 
            id;
    ";
    $stmt = mysqli_stmt_init($con);


    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: categories.php?error=$error_message");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $user_id);

    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        // load the $rows in an array and 
        $categories = array();
        while($row = mysqli_fetch_assoc($result)){
            $categories[] = $row;
        }
        return $categories;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: category_edit.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}

function delete_category($con, $category_id, $user_id) {
    $query = "call p_delete_category(?, ?);";
    $stmt = mysqli_stmt_init($con);


    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: categories.php?error=$error_message");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $category_id, $user_id);

    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);

        $status_message = urlencode("Успешно изтриване на категорията!");
        header("location: categories.php?status=$status_message");
        exit();
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: categories.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}