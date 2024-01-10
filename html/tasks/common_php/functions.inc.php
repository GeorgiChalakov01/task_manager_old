<?php

function get_uncategorized_id($con, $user_id) {
    $query = "
        select 
            min(id) as id
        from 
            categories
        where 
            owner_id = ?;
    ";
    
    $stmt = mysqli_stmt_init($con);


    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: home.php?error=$error_message");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $user_id);

    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        $categories = array();
        while($row = mysqli_fetch_assoc($result)){
            $categories[] = $row;
        }
        return $categories[0]['id'];
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: home.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}

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

function append_category($con, $user_id, $category_id, $object_id, $object_type) {
    $query = "call p_append_category(?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: category_edit.php?error=$error_message");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "iiis", $user_id, $category_id, $object_id, $object_type);
    
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);

        return true;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: home.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}

function unappend_category($con, $user_id, $category_id, $object_id, $object_type) {
    $query = "call p_unappend_category(?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: category_edit.php?error=$error_message");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "iiis", $user_id, $category_id, $object_id, $object_type);
    
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);

        return true;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: home.php?error=$error_message" . mysqli_stmt_error($stmt));
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

function get_appended_categories($con, $user_id, $object_id, $object_type) {
    $table_name = mysqli_real_escape_string($con, $object_type) . "s_have_categories";
    $query = "
        select 
            c.id,
            c.name,
            c.background_color,
            c.text_color
        from 
            categories c 
            inner join $table_name o on c.id = o.category_id
        where
            c.owner_id = ?;
    ";
    $stmt = mysqli_stmt_init($con);


    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: home.php?error=$error_message");
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
        header("location: home.php?error=$error_message" . mysqli_stmt_error($stmt));
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

function get_files_with_categories($con, $user_id) {
    $query = "
        select 
            f.id, 
            f.full_path,
            f.name,
            f.extension,
            f.title, 
            f.description, 
            f.uploaded_on,

            fc.category_id
        from 
            files f inner join files_have_categories fc on fc.file_id = f.id
        where 
            f.id in (
                select
                    file_id
                from
                    file_privileges
                where
                    user_id = ? and
                    privilege = 'v'
            )
        order by
            fc.category_id, f.id desc;
    ";
    $stmt = mysqli_stmt_init($con);


    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: home.php?error=$error_message");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $user_id);

    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        // load the $rows in an array and 
        $files = array();
        while($row = mysqli_fetch_assoc($result)){
            $files[] = $row;
        }
        return $files;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: category_edit.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}

function get_files($con, $user_id) {
    $query = "
        select 
            f.id, 
            f.full_path,
            f.name,
            f.extension,
            f.title, 
            f.description, 
            f.uploaded_on
        from 
            files f
        where 
            f.id in (
                select
                    file_id
                from
                    file_privileges
                where
                    user_id = ? and
                    privilege = 'v'
            )
        order by
            f.id desc;
    ";
    $stmt = mysqli_stmt_init($con);


    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: home.php?error=$error_message");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $user_id);

    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        // load the $rows in an array and 
        $files = array();
        while($row = mysqli_fetch_assoc($result)){
            $files[] = $row;
        }
        return $files;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: home.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}

function upload_file($con, $user_id, $file_name, $file_extension, $file_destination, $title, $description) {
    $query = "CALL p_upload_file(?, ?, ?, ?, ?, ?, @out_file_id)";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: file_edit.php?file_id=-1&error=$error_message");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "isssss", $user_id, $file_name, $file_extension, $file_destination, $title, $description);

    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_query($con, "SELECT @out_file_id");
        $file_id = mysqli_fetch_assoc($result)['@out_file_id'];
        mysqli_stmt_close($stmt);

        return $file_id;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: file_edit.php?file_id=-1&error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}


function edit_file($con, $file_id, $user_id, $file_name, $file_extension, $file_destination, $title, $description) {
    $query = "call p_edit_file(?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: file_edit.php?id=-1&error=$error_message");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "iisssss", $file_id, $user_id, $file_destination, $file_name, $file_extension, $title, $description);
    
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);

        return $file_id;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: file_edit.php?id=-1&error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}


function create_note($con, $user_id, $title, $description) {
    $query = "CALL p_create_note(?, ?, ?, @out_note_id)";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: note_edit.php?id=-1&error=$error_message");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "iss", $user_id, $title, $description);

    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_query($con, "SELECT @out_note_id");
        $note_id = mysqli_fetch_assoc($result)['@out_note_id'];
        mysqli_stmt_close($stmt);

        return $note_id;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: note_edit.php?id=-1&error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}

function attach_file_to_note($con, $user_id, $note_id, $file_id) {
    $query = "call p_attach_file_to_note(?, ?, ?);";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: note_edit.php?id=$note_id&error=$error_message");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "iii", $user_id, $note_id, $file_id);
    
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);

        return true;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: note_edit.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}

function unattach_file_to_note($con, $user_id, $note_id, $file_id) {
    $query = "call p_unattach_file_to_note(?, ?, ?);";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: note_edit.php?id=$note_id&error=$error_message");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "iii", $user_id, $note_id, $file_id);
    
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);

        return true;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: note_edit.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}

function get_attached_files_to_a_note ($con, $user_id, $note_id) {
    $query = "
        select 
            f.id, 
            f.full_path,
            f.name,
            f.extension,
            f.title, 
            f.description, 
            f.uploaded_on
        from 
            files f
            inner join notes_attach_files naf on naf.file_id = f.id
        where 
            naf.note_id in (
                select
                    note_id
                from
                    note_privileges
                where
                    user_id = ? and
                    privilege = 'v'
            ) and
            naf.file_id in (
                select
                    file_id
                from
                    file_privileges
                where
                    user_id = ? and
                    privilege = 'v'
            ) and
            naf.note_id = ?
        order by
            f.id;
    ";
    $stmt = mysqli_stmt_init($con);


    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: home.php?error=$error_message");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "iii", $user_id, $user_id, $note_id);

    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        $files = array();
        while($row = mysqli_fetch_assoc($result)){
            $files[] = $row;
        }
        return $files;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: home.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}

function get_notes($con, $user_id) {
    $query = "
        select 
            n.id, 
            n.title, 
            n.description, 
            n.created_on
        from 
            notes n
        where 
            n.id in (
                select
                    note_id
                from
                    note_privileges
                where
                    user_id = ? and
                    privilege = 'v'
            )
        order by
            n.id desc;
    ";
    $stmt = mysqli_stmt_init($con);


    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: home.php?error=$error_message");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $user_id);

    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        // load the $rows in an array and 
        $files = array();
        while($row = mysqli_fetch_assoc($result)){
            $files[] = $row;
        }
        return $files;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: category_edit.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}

function get_notes_with_categories($con, $user_id) {
    $query = "
        select 
            n.id, 
            n.title, 
            n.description, 
            n.created_on,

            nc.category_id
        from 
            notes n inner join notes_have_categories nc on nc.note_id = n.id
        where 
            n.id in (
                select
                    note_id
                from
                    note_privileges
                where
                    user_id = ? and
                    privilege = 'v'
            )
        order by
            n.id desc;
    ";
    $stmt = mysqli_stmt_init($con);


    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: home.php?error=$error_message");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $user_id);

    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        // load the $rows in an array and 
        $files = array();
        while($row = mysqli_fetch_assoc($result)){
            $files[] = $row;
        }
        return $files;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: category_edit.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}

function get_attached_notes_to_a_project ($con, $user_id, $project_id) {
    $query = "
        SELECT 
        n.id, 
        n.title, 
        n.description, 
        n.created_on
    FROM 
        notes n
        INNER JOIN projects_attach_notes pan ON pan.note_id = n.id
    WHERE 
        pan.project_id IN (
            SELECT
                project_id
            FROM
                project_privileges
            WHERE
                user_id = ? AND
                privilege = 'v'
        ) AND
        pan.note_id IN (
            SELECT
                note_id
            FROM
                note_privileges
            WHERE
                user_id = ? AND
                privilege = 'v'
        ) AND
        pan.project_id = ?
    ORDER BY
        n.id;
    ";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: home.php?error=$error_message");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "iii", $user_id, $user_id, $project_id);

    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        $notes = array();
        while($row = mysqli_fetch_assoc($result)){
            $notes[] = $row;
        }
        return $notes;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: home.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}

function attach_note_to_project($con, $user_id, $project_id, $note_id) {
    $query = "call p_attach_note_to_project(?, ?, ?);";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: project_edit.php?id=$project_id&error=$error_message");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "iii", $user_id, $project_id, $note_id);
    
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);

        return true;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: project_edit.php?id=$project_id&error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}

function unattach_note_to_project($con, $user_id, $project_id, $note_id) {
    $query = "call p_unattach_note_to_project(?, ?, ?);";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: project_edit.php?id=$project_id&error=$error_message");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "iii", $user_id, $project_id, $note_id);
    
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);

        return true;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: project_edit.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}

function get_projects($con, $user_id) {
    $query = "
        select 
            p.id, 
            p.title, 
            p.description, 
            p.created_on,
            p.ended_on,
            p.deadline,

            pc.category_id
        from 
            projects p inner join projects_have_categories pc on pc.project_id = p.id
        where 
            p.id in (
                select
                    project_id
                from
                    project_privileges
                where
                    user_id = ? and
                    privilege = 'v'
            )
        order by
            p.id desc;
    ";
    $stmt = mysqli_stmt_init($con);


    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: home.php?error=$error_message");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $user_id);

    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        // load the $rows in an array and 
        $files = array();
        while($row = mysqli_fetch_assoc($result)){
            $files[] = $row;
        }
        return $files;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: category_edit.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}


function create_project($con, $user_id, $title, $description, $deadline, $category_id) {
    $query = "CALL p_create_project(?, ?, ?, ?, @project_id);";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: note_edit.php?error=$error_message");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "isss", $user_id, $title, $description, $deadline);
    
    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_query($con, "SELECT @project_id");
        $row = mysqli_fetch_assoc($result);
        $project_id = $row['@project_id'];
        return $project_id;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: project_edit.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}

function edit_project($con, $user_id, $title, $description, $deadline, $project_id) {
    $query = "CALL p_edit_project(?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: note_edit.php?error=$error_message");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "isssi", $user_id, $title, $description, $deadline, $project_id);
    
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);
        return $project_id;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: project_edit.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}


function get_project_info($con, $user_id, $project_id) {
    $query = "
        select
            title, 
            description, 
            created_on,
            ended_on,
            deadline
        from 
            projects
        where 
            id = ? and
            id in (
                select
                    project_id
                from
                    project_privileges
                where
                    user_id = ? and
                    privilege = 'v'
            );
    ";
    $stmt = mysqli_stmt_init($con);


    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: home.php?error=$error_message");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $project_id, $user_id);

    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        // load the $rows in an array and 
        $files = array();
        while($row = mysqli_fetch_assoc($result)){
            $files[] = $row;
        }
        return $files;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: projects.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}

function get_tasks($con, $user_id, $project_id) {
    $query = "
        select 
            id,
            project_id,
            place,
            blocker,
            title,
            description,
            created_on,
            completed_on,
            duration_minutes,
            deadline
        from 
            tasks
        where 
            project_id = ? and
            project_id in (
                select
                    project_id
                from
                    project_privileges
                where
                    user_id = ? and
                    privilege = 'v'
            );
    ";
    $stmt = mysqli_stmt_init($con);


    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: home.php?error=$error_message");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $project_id, $user_id);

    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        // load the $rows in an array and 
        $files = array();
        while($row = mysqli_fetch_assoc($result)){
            $files[] = $row;
        }
        return $files;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: category_edit.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}

function create_task($con, $user_id, $project_id, $title, $description, $blocker, $duration, $reminder, $deadline) {
    $query = "call p_create_task(?, ?, ?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: task_edit.php?error=$error_message");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "iiissisi", $user_id, $project_id, $blocker, $title, $description, $duration, $deadline, $reminder);
    
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);

        $status_message = urlencode("Успешно създаден проект!");
        header("location: project_view.php?id=$project_id&status=$status_message");
        exit();
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: task_edit.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}


function get_task_info($con, $user_id, $task_id) {
    $query = "
        select 
            id,
            project_id,
            place,
            blocker,
            title,
            description,
            created_on,
            completed_on,
            duration_minutes,
            deadline
        from 
            tasks
        where 
            id = ? and
            project_id in (
                select
                    project_id
                from
                    project_privileges
                where
                    user_id = ? and
                    privilege = 'v'
            );
    ";
    $stmt = mysqli_stmt_init($con);


    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: home.php?error=$error_message");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $task_id, $user_id);

    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        // load the $rows in an array and 
        $files = array();
        while($row = mysqli_fetch_assoc($result)){
            $files[] = $row;
        }
        return $files;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: category_edit.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}

function edit_note($con, $note_id, $user_id, $title, $description) {
    $query = "call p_edit_note(?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: note_edit.php?error=$error_message");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "iiss", $note_id, $user_id, $title, $description);
    
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);

        return $note_id;
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: note_edit.php?error=$error_message" . mysqli_stmt_error($stmt));
        exit();
    }
}


function delete_file($con, $file_id, $user_id, $file_path) {
    $query = "call p_delete_file(?, ?);";
    $stmt = mysqli_stmt_init($con);


    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: files.php?error=$error_message");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $file_id, $user_id);

    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);

        if (file_exists($file_path)) {
            if (unlink($file_path)) {
                echo "File deleted successfully";
            } else {
                echo "File could not be deleted";
            }
        } else {
            echo "File does not exist";
        }

        $status_message = urlencode("Успешно изтриване на файла!");
        header("location: files.php?status=$status_message");
        exit();
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: files.php?error=$error_message" . urlencode(mysqli_stmt_error($stmt)));
        exit();
    }
}

function delete_note($con, $note_id, $user_id) {
    $query = "call p_delete_note(?, ?);";
    $stmt = mysqli_stmt_init($con);


    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: notes.php?error=$error_message");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $note_id, $user_id);

    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);

        $status_message = urlencode("Успешно изтриване на бележка!");
        header("location: notes.php?status=$status_message");
        exit();
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: notes.php?error=$error_message" . urlencode(mysqli_stmt_error($stmt)));
        exit();
    }
}

function delete_task($con, $task_id, $user_id) {
    $query = "call p_delete_task(?, ?);";
    $stmt = mysqli_stmt_init($con);


    if(!mysqli_stmt_prepare($stmt, $query)) {
        $error_message = urlencode("Няма връзка с базата данни!");
        header("location: home.php?error=$error_message");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $task_id, $user_id);

    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);

        $status_message = urlencode("Успешно изтриване на задача!");
        header("location: projects.php?status=$status_message");
        exit();
    }
    else {
        $error_message = urlencode("Грешка: ");
        header("location: projects.php?error=$error_message" . urlencode(mysqli_stmt_error($stmt)));
        exit();
    }
}