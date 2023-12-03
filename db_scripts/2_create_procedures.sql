use dwh;

delimiter $$




------------------------------------------------------------------------------------------------------
/*
Check the privileges of a user to an object.    
*/
CREATE OR REPLACE PROCEDURE p_check_privileges (
    IN pi_user_id INT,
    IN pi_object_id INT,
    IN pi_privilege VARCHAR(1),
    IN pi_object_type VARCHAR(20),
    OUT po_has_privileges BOOLEAN
)
BEGIN
    DECLARE query VARCHAR(500);

    SET @table_name = CONCAT(pi_object_type, '_privileges');
    SET @query = CONCAT('
        SELECT 
            COUNT(*) > 0 
        FROM 
            ', @table_name, ' 
        WHERE 
            user_id = ? AND 
            object_id = ? AND 
            privilege = ?;
    ');

    PREPARE stmt FROM @query;
    SET @user_id = pi_user_id, @object_id = pi_object_id, @privilege = pi_privilege;
    EXECUTE stmt USING @user_id, @object_id, @privilege;
    DEALLOCATE PREPARE stmt;

    SELECT @has_privileges INTO po_has_privileges;
END;
$$
------------------------------------------------------------------------------------------------------
/*
Create a user.
Do some basic validation.
*/
create or replace procedure p_create_user (
    in pi_email varchar(255),
    in pi_username varchar(20),
    in pi_password_hash char(60),
    in pi_first_name varchar(30),
    in pi_last_name varchar(30),
    in pi_profile_picture blob
)
begin
    -- Handle empty strings.
    SET pi_email = NULLIF(pi_email, '');
    SET pi_username = NULLIF(pi_username, '');
    SET pi_password_hash = NULLIF(pi_password_hash, '');
    SET pi_first_name = NULLIF(pi_first_name, '');
    SET pi_last_name = NULLIF(pi_last_name, '');


    -- create the user.
    insert into users(
        email,
        username,
        password_hash,
        first_name,
        last_name,
        profile_picture
    )
    values(
        pi_email,
        pi_username,
        pi_password_hash,
        pi_first_name,
        pi_last_name,
        pi_profile_picture
    );
end;
$$
------------------------------------------------------------------------------------------------------
/*
Create a category.
Validate the input.
*/
create or replace procedure p_create_category (
    in pi_owner_id int,
    in pi_name varchar(50),
    in pi_background_color varchar(7),
    in pi_text_color varchar(7)
)
begin
    -- Handle empty strings.
    SET pi_name = nullif(pi_name, '');


    -- Handle Nulls for the colors.
    if pi_background_color = '' or pi_background_color is null then
        SET pi_background_color = '#FFE599';
    end if;

    if pi_text_color = '' or pi_text_color is null then
        SET pi_text_color = '#FFFFFF';
    end if;


    -- Create the category.
    insert into categories(
        owner_id,
        name,
        background_color,
        text_color
    )
    values(
        pi_owner_id,
        pi_name,
        pi_background_color,
        pi_text_color
    );
end;
$$
------------------------------------------------------------------------------------------------------
/*
Append a category to an object, regardless of the object type.
*/
CREATE or replace PROCEDURE p_append_category (
    IN pi_category_id INT,
    IN pi_object_id INT,
    IN pi_object_type VARCHAR(50)
)
BEGIN
    DECLARE query VARCHAR(500);
    DECLARE table_name VARCHAR(500);

    SET table_name = CONCAT(pi_object_type, 's_have_categories');
    SET query = CONCAT('INSERT INTO ', table_name, ' VALUES (?, ?)');

    PREPARE stmt FROM query;
    EXECUTE stmt USING pi_object_id, pi_category_id;
    DEALLOCATE PREPARE stmt;
END;
$$
------------------------------------------------------------------------------------------------------
/*
Edit a category.
*/
create or replace procedure p_edit_category (
    in pi_category_id INT,
    in pi_user_id int,
    in pi_name varchar(50),
    in pi_background_color varchar(7),
    in pi_text_color varchar(7)  
)
begin
    -- Handle empty strings and null values.
    if pi_name is null or pi_name = '' then
        set pi_name = (select name from categories where id = pi_category_id);
    end if;

    if pi_background_color is null or pi_background_color = '' then
        set pi_background_color = (select background_color from categories where id = pi_category_id);
    end if;

    if pi_text_color is null or pi_text_color = '' then
        set pi_text_color = (select text_color from categories where id = pi_category_id);
    end if;


    -- Check if the owner tries to edit the category.
    if pi_user_id = (select owner_id from categories where id = pi_category_id) then
        update 
            categories 
        set 
            name = pi_name, 
            background_color = pi_background_color, 
            text_color = pi_text_color
        where
            id = pi_category_id;
    end if;
end;
$$
------------------------------------------------------------------------------------------------------
/*
Delete a category.
*/
create or replace procedure p_delete_category (
    in pi_category_id INT,
    in pi_user_id INT
)
begin
    -- Check if the owner tries to edit the category.
    if pi_user_id = (select owner_id from categories where id = pi_category_id) then
        delete from 
            categories 
        where
            id = pi_category_id;
    else
        signal sqlstate '45000' set message_text = 'user does not own the category!';
    end if;
end;
$$
------------------------------------------------------------------------------------------------------
/*
Private procedure used to grant privileges on creation to the creator.
To grant privileges to a different user use p_grant_access
*/
create or replace procedure _p_grant_access (
    in pi_user_id int,
    in pi_object_id int,
    in pi_privilege char(1),
    in pi_object_type varchar(50)
)
begin
    DECLARE query VARCHAR(500);
    DECLARE table_name VARCHAR(500);

    SET table_name = CONCAT(pi_object_type, '_privileges');
    SET query = CONCAT('
        INSERT INTO ', table_name, ' (
            user_id, 
            ', pi_object_type, '_id, 
            privilege
        ) 
        VALUES (
            ?, 
            ?, 
            ?
        )');

    PREPARE stmt FROM query;
    EXECUTE stmt USING pi_user_id, pi_object_id, pi_privilege;
    DEALLOCATE PREPARE stmt;
end;
$$
------------------------------------------------------------------------------------------------------
/*
Create a note.
Handle empty strings.
Add View, Edit and Own privileges to the creator.
*/
create or replace procedure p_create_note (
    in pi_user_id int,
    in pi_title varchar(50),
    in pi_description varchar(15000)
)
begin
    declare note_id int;


    -- Handle empty strings.
    if pi_title = '' or pi_title is null then
        set pi_title = 'Untitled Note';
    end if;


    -- create the note.
    begin
    insert into notes(
        title,
        description,
        created_on
    )
    values(
        pi_title,
        pi_description,
        now()
    );

    set note_id = last_insert_id();
    end;

    call _p_grant_access(pi_user_id, note_id, 'v', 'note');
    call _p_grant_access(pi_user_id, note_id, 'e', 'note');
    call _p_grant_access(pi_user_id, note_id, 'o', 'note');
end;
$$
------------------------------------------------------------------------------------------------------
/*
Upload a file and give privileges to the creator.
*/
create or replace procedure p_upload_file (
    in pi_file blob,
    in pi_title varchar(50),
    in pi_description varchar(15000),
    in pi_user_id int
)
begin
    declare file_id int;

    -- Upload the file.
    insert into files (
        file,
        title,
        description,
        uploaded_on
    )
    values (
        pi_file,
        pi_title,
        pi_description,
        now()
    );

    set file_id = last_insert_id();

    -- Add the accesses.
    call _p_grant_access(pi_user_id, file_id, 'v', 'file');
    call _p_grant_access(pi_user_id, file_id, 'e', 'file');
    call _p_grant_access(pi_user_id, file_id, 'o', 'file');
end;
$$
------------------------------------------------------------------------------------------------------
/*
Attach a file to a note.
*/
create or replace procedure p_attach_file_to_note(
    in pi_note_id int,
    in pi_file_id int
)
begin
    insert into notes_attach_files values(pi_note_id, pi_file_id);
end;
$$
------------------------------------------------------------------------------------------------------
/*
Create a project and give privileges to the creator.
*/
create or replace procedure p_create_project (
    in pi_title varchar(50),
    in pi_description varchar(15000),
    in pi_deadline datetime,
    in pi_user_id int
)
begin
    declare project_id int;


    -- Handle empty strings.
    set pi_title = nullif(pi_title, '');


    -- Create the project.
    insert into projects (
        title, 
        description, 
        created_on, 
        deadline
    )
    values(
        pi_title,
        pi_description,
        now(),
        pi_deadline
    );

    set project_id = last_insert_id();

    call _p_grant_access(pi_user_id, project_id, 'v', 'project');
    call _p_grant_access(pi_user_id, project_id, 'e', 'project');
    call _p_grant_access(pi_user_id, project_id, 'o', 'project');
end;
$$
------------------------------------------------------------------------------------------------------
/*
Mark a project as completed.
*/
create or replace procedure p_end_project (
    in pi_project_id int,
    in pi_user_id int
)
begin
    declare is_editor boolean;

    call p_check_privileges(pi_user_id, pi_project_id, 'e', 'project', is_editor);

    if is_editor then
        update 
            projects 
        set
            ended_on = now()
        where
            project_id = pi_project_id;
    else
        signal sqlstate '45000' set message_text = 'user has no editor privileges!';
    end if;
end;
$$
------------------------------------------------------------------------------------------------------
/*
Attach a note to a project.
*/
create or replace procedure p_attach_note_to_project (
    in pi_project_id int,
    in pi_note_id int
)
begin
    insert into projects_attach_notes values(pi_project_id, pi_note_id);
end;
$$
------------------------------------------------------------------------------------------------------
/*
Assign a task to a user.
*/
create or replace procedure p_assign_task_to_user (
    in pi_task_id int,
    in pi_user_id int
)
begin
    insert into users_have_tasks_assigned (
        user_id, 
        task_id
    )
    values (
        pi_user_id,
        pi_task_id
    );
end;
$$
------------------------------------------------------------------------------------------------------
/*
Create a task
*/
create or replace procedure p_create_task (
    in pi_user_id int,
    in pi_project_id int,
    in pi_blocker boolean,
    in pi_title varchar(50),
    in pi_description varchar(15000),
    in pi_duration_minutes int,
    in pi_deadline datetime,
    in pi_create_reminder boolean
)
begin
    -- Place
    declare next_place int;
    declare task_id int;
    
    set next_place = ifnull(
        (select max(place) + 1 from tasks where project_id = pi_project_id),
        1
    );


    -- Handling empty strings.
    set pi_title = nullif(pi_title, '');


    -- Create the task.
    insert into tasks (
        project_id,
        place,
        blocker,
        title,
        description,
        created_on,
        completed_on,
        duration_minutes,
        deadline
    )
    values (
        pi_project_id,
        next_place,
        pi_blocker,
        pi_title,
        pi_description,
        now(),
        NULL,
        pi_duration_minutes,
        pi_deadline
    );

    set task_id = last_insert_id();

    -- Give privileges to the creator.
    call _p_grant_access(pi_user_id, task_id, 'v', 'task');
    call _p_grant_access(pi_user_id, task_id, 'e', 'task');
    call _p_grant_access(pi_user_id, task_id, 'o', 'task');

    call p_assign_task_to_user(task_id, pi_user_id);

    -- Create a reminder if the user wants to.
    /*
    if pi_create_reminder and pi_duration > 0 then
        call p_create_reminder(...);
    end if;
    */
end;
$$
------------------------------------------------------------------------------------------------------
/*
Create an event on the schedule.
Give privileges to the creator.
*/
create or replace procedure p_create_event (
    in pi_task_id int,
    in pi_user_id int,
    in pi_start_time datetime,
    in pi_end_time datetime
)
begin
    if pi_start_time < pi_end_time then
    begin
        declare event_id int;
        
        -- Create the event
        insert into events (
            task_id,
            start_time,
            end_time
        )
        values (
            pi_task_id,
            pi_start_time,
            end_time
        );
    
        set event_id = last_insert_id();
    
        -- Give privileges to the owner.
        call _p_grant_access(pi_user_id, event_id, 'v', 'event');
        call _p_grant_access(pi_user_id, event_id, 'e', 'event');
        call _p_grant_access(pi_user_id, event_id, 'o', 'event');
    end;
    else
        signal sqlstate '45000' set message_text = 'end_time can not be less than the start_time!';
    end if;
end;
$$
------------------------------------------------------------------------------------------------------
/*
Create a reminder for a project and grant privileges to the creator.
*/
create or replace procedure p_create_project_reminder (
    in pi_user_id int,
    in pi_project_id int,
    in pi_datetime datetime,
    in pi_comment varchar(500)
)
begin
    declare project_reminder_id int;


    -- Handle empty strings.
    set pi_comment = nullif(pi_comment, '');

    -- Check if the reminder datetime is after now.
    if  pi_datetime < now() then
        signal sqlstate '45000' set message_text = 'can not create the reminder in the past!';
    end if;

    -- Create the reminder.
    insert into project_reminders (
        project_id,
        `datetime`,
        comment
    )
    values (
        pi_project_id,
        pi_datetime,
        pi_comment
    );

    set project_reminder_id = last_insert_id();

    -- Grant the privileges to the creator.
    call _p_grant_access(pi_user_id, project_reminder_id, 'v', 'project_reminder');
    call _p_grant_access(pi_user_id, project_reminder_id, 'e', 'project_reminder');
    call _p_grant_access(pi_user_id, project_reminder_id, 'o', 'project_reminder');
end;
$$
------------------------------------------------------------------------------------------------------
/*
Create a reminder for a task and grant privileges to the creator.
*/
create or replace procedure p_create_task_reminder (
    in pi_user_id int,
    in pi_task_id int,
    in pi_datetime datetime,
    in pi_comment varchar(500)
)
begin
    declare task_reminder_id int;


    -- Handle empty strings.
    set pi_comment = nullif(pi_comment, '');

    -- Check if the reminder datetime is after now.
    if  pi_datetime < now() then
        signal sqlstate '45000' set message_text = 'can not create the reminder in the past!';
    end if;

    -- Create the reminder.
    insert into task_reminders (
        task_id,
        `datetime`,
        comment
    )
    values (
        pi_task_id,
        pi_datetime,
        pi_comment
    );

    set task_reminder_id = last_insert_id();

    -- Grant the privileges to the creator.
    call _p_grant_access(pi_user_id, task_reminder_id, 'v', 'task_reminder');
    call _p_grant_access(pi_user_id, task_reminder_id, 'e', 'task_reminder');
    call _p_grant_access(pi_user_id, task_reminder_id, 'o', 'task_reminder');
end;
$$
------------------------------------------------------------------------------------------------------
/*
Mark a project as archived
*/
create or replace procedure p_archive_project (
    in pi_project_id int,
    in pi_user_id int
)
begin
    insert into archived_projects (
        project_id,
        user_id
    )
    values (
        pi_project_id,
        pi_user_id
    );
end;
$$
------------------------------------------------------------------------------------------------------
/*
Mark a note as archived
*/
create or replace procedure p_archive_note (
    in pi_note_id int,
    in pi_user_id int
)
begin
    insert into archived_notes (
        note_id,
        user_id
    )
    values (
        pi_note_id,
        pi_user_id
    );
end;
$$






--====================================================================================================
/*
Delete a user with his data.
*/
/*
create or replace procedure p_delete_user_with_data(
    in pi_user_id int
);
begin
    -- Delete the user's data.
    call p_delete_file();
    call p_delete_category();
    call p_delete_note();
    call p_delete_project_with_tasks();
    
    -- Finally delete the user himself.
    delete from users where id = pi_user_id;
end;
$$
*/
------------------------------------------------------------------------------------------------------
/*
create or replace procedure p_delete_category (
    in pi_category_id int
)
begin
    Change the categories of all the objects with this category.

    delete from categories where id = pi_category_id;
end;
$$
*/
------------------------------------------------------------------------------------------------------
/*
create or replace procedure p_grant_access (
/*
------------------------------------------------------------------------------------------------------
/*
Delete a Project
*/
/*
create or replace procedure p_delete_project (
    in pi_project_id int,
    in pi_user_id int
)
begin
    declare is_owner boolean;

    call p_check_privileges(pi_user_id, pi_project_id, 'o', 'project', is_owner);

    if is_owner then
        delete from projects where project_id = pi_project_id;
    else
        signal sqlstate '45000' set message_text = 'user has no owner privileges!';
    end if;
end;
$$
*/
------------------------------------------------------------------------------------------------------










delimiter ;