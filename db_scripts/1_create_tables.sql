create or replace database dwh;
------------------------------------------------------------------------------------------------------


use dwh;


------------------------------------------------------------------------------------------------------
create or replace table users (
    id int auto_increment primary key,
    email varchar(255) not null unique,
    username varchar(30) not null unique,
    password_hash char(60) not null,
    first_name varchar(30) not null,
    last_name varchar(30) not null,
    profile_picture blob
);
------------------------------------------------------------------------------------------------------
create or replace table notes (
    id int auto_increment primary key,
    title varchar(50) not null,
    description varchar(15000) not null,
    created_on datetime not null
);
------------------------------------------------------------------------------------------------------
create or replace table note_privileges (
    id int auto_increment primary key,
    user_id int not null,
    note_id int not null,
    privilege varchar(1) not null,

    foreign key (user_id) references users(id),
    foreign key (note_id) references notes(id)
);
------------------------------------------------------------------------------------------------------
create or replace table categories (
    id int auto_increment primary key,
    owner_id int not null,
    name varchar(50) not null,
    background_color varchar(7) not null,
    text_color varchar(7) not null,

    foreign key (owner_id) references users(id)
);
------------------------------------------------------------------------------------------------------
create or replace table notes_have_categories (
    id int auto_increment primary key,
    note_id int not null,
    category_id int not null,

    foreign key (note_id) references notes(id),
    foreign key (category_id) references categories(id)
);
------------------------------------------------------------------------------------------------------
create or replace table files (
    id int auto_increment primary key,
    file blob not null,
    title varchar(50) not null,
    description varchar(15000),
    uploaded_on datetime not null
);
------------------------------------------------------------------------------------------------------
create or replace table file_privileges (
    id int auto_increment primary key,
    user_id int not null,
    file_id int not null,
    privilege varchar(1) not null,

    foreign key (user_id) references users(id),
    foreign key (file_id) references files(id)
);
------------------------------------------------------------------------------------------------------
create or replace table files_have_categories (
    id int auto_increment primary key,
    file_id int not null,
    category_id int not null,

    foreign key (file_id) references files(id),
    foreign key (category_id) references categories(id)
);
------------------------------------------------------------------------------------------------------
create or replace table notes_attach_files (
    id int auto_increment primary key,
    note_id int not null,
    file_id int not null,

    foreign key (note_id) references notes(id),
    foreign key (file_id) references files(id)
);
------------------------------------------------------------------------------------------------------
create or replace table projects (
    id int auto_increment primary key,
    title varchar(50) not null,
    description varchar(15000),
    created_on datetime not null,
    ended_on datetime,
    deadline datetime
);
------------------------------------------------------------------------------------------------------
create or replace table project_privileges (
    id int auto_increment primary key,
    user_id int not null,
    project_id int not null,
    privilege varchar(1) not null,

    foreign key (user_id) references users(id),
    foreign key (project_id) references projects(id)
);
------------------------------------------------------------------------------------------------------
create or replace table projects_have_categories (
    id int auto_increment primary key,
    project_id int not null,
    category_id int not null,

    foreign key (project_id) references projects(id),
    foreign key (category_id) references categories(id)
);
------------------------------------------------------------------------------------------------------
create or replace table projects_attach_notes (
    id int auto_increment primary key,
    project_id int not null,
    note_id int not null,

    foreign key (project_id) references projects(id),
    foreign key (note_id) references notes(id)
);
------------------------------------------------------------------------------------------------------
create or replace table tasks (
    id int auto_increment primary key,
    project_id int not null,
    place int not null,
    blocker boolean not null,
    title varchar(50) not null,
    description varchar(15000),
    created_on datetime not null,
    completed_on datetime,
    duration_minutes int,
    deadline datetime,

    foreign key (project_id) references projects(id)
);
------------------------------------------------------------------------------------------------------
create or replace table task_privileges (
    id int auto_increment primary key,
    user_id int not null,
    task_id int not null,
    privilege varchar(1) not null,

    foreign key (user_id) references users(id),
    foreign key (task_id) references tasks(id)
);
------------------------------------------------------------------------------------------------------
create or replace table tasks_attach_notes (
    id int auto_increment primary key,
    task_id int not null,
    note_id int not null,

    foreign key (task_id) references tasks(id),
    foreign key (note_id) references notes(id)
);
------------------------------------------------------------------------------------------------------
create or replace table users_have_tasks_assigned (
    id int auto_increment primary key,
    user_id int not null,
    task_id int not null,

    foreign key (user_id) references users(id),
    foreign key (task_id) references tasks(id)
);
------------------------------------------------------------------------------------------------------
create or replace table events (
    id int auto_increment primary key,
    task_id int not null,
    start_time datetime not null,
    end_time datetime not null,

    foreign key (task_id) references tasks(id)
);
------------------------------------------------------------------------------------------------------
create or replace table event_privileges (
    id int auto_increment primary key,
    user_id int not null,
    event_id int not null,
    privilege varchar(1) not null,

    foreign key (user_id) references users(id),
    foreign key (event_id) references events(id)
);
------------------------------------------------------------------------------------------------------
create or replace table project_reminders (
    id int auto_increment primary key,
    project_id int not null,
    `datetime` datetime not null,
    comment varchar(500),

    foreign key (project_id) references projects(id)
);
------------------------------------------------------------------------------------------------------
create or replace table project_reminder_privileges (
    id int auto_increment primary key,
    user_id int not null,
    project_reminder_id int not null,
    privilege varchar(1) not null,

    foreign key (user_id) references users(id),
    foreign key (project_reminder_id) references project_reminders(id)
);
------------------------------------------------------------------------------------------------------
create or replace table task_reminders (
    id int auto_increment primary key,
    task_id int not null,
    `datetime` datetime not null,
    comment varchar(500),

    foreign key (task_id) references tasks(id)
);
------------------------------------------------------------------------------------------------------
create or replace table task_reminder_privileges (
    id int auto_increment primary key,
    user_id int not null,
    task_reminder_id int not null,
    privilege varchar(1) not null,

    foreign key (user_id) references users(id),
    foreign key (task_reminder_id) references task_reminders(id)
);
------------------------------------------------------------------------------------------------------
create or replace table archived_notes (
    id int auto_increment primary key,
    note_id int not null,
    user_id int not null,

    foreign key (note_id) references notes(id),
    foreign key (user_id) references users(id)
);
------------------------------------------------------------------------------------------------------
create or replace table archived_projects (
    id int auto_increment primary key,
    project_id int not null,
    user_id int not null,

    foreign key (project_id) references projects(id),
    foreign key (user_id) references users(id)
);
------------------------------------------------------------------------------------------------------