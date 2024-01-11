<!DOCTYPE html>
<html>
<head>
    <title><?php if(isset($_GET['id'])) echo 'Промяна на Проект'; else echo 'Създаване на Проект';?></title>
    <?php include '../PWA/headers.php'?>
    <?php include 'common_php/head.php'?>
    <link rel="stylesheet" href="styles/form.css">
</head>
<body>
    <?php include 'common_php/body.php'?>

    <div class="content">
        <div class="container">
            <div class="form">
                <header><?php if(isset($_GET['id'])) echo 'Промяна на Проект'; else echo 'Създаване на Проект';?></header>
                <p class="status"><?php if(isset($_GET['status']))echo $_GET["status"];?></p>
                <p class="error"><?php if(isset($_GET['error']))echo $_GET["error"];?></p>
        
                <form action="project_edit.inc.php" method="post">
                    <input 
                        type="hidden" 
                        name="id"
                        value=
                            "<?php 
                            if(isset($_GET['id']))
                                echo $_GET['id'];
                            else
                                echo '-1';
                            ?>"
                    >
                    
                    <label for="title">Име</label>
                    <input 
                        type="text" 
                        name="title" 
                        value=
                            "<?php 
                            if(isset($_GET['title']))
                                echo $_GET['title'];
                            ?>"
                        >
                    
                    <label for="description">Описание</label>
                    <br><textarea
                        name="description"
                    ><?php 
                        if(isset($_GET['description']))
                            echo $_GET['description'];
                    ?></textarea><br><br>

                    <label for="deadline">Краен Срок</label>
                    <input 
                        type="datetime-local"
                        name="deadline" 
                        value=
                            "<?php 
                            if(isset($_GET['deadline']))
                                echo urldecode($_GET['deadline']);
                            ?>"
                    >

                    <label for="categories"> Категории</label>
                    <div class="category_container">
                        <?php
                            require_once '../db/dbh.inc.php';
                            require_once 'common_php/functions.inc.php';
    
                            $categories = get_categories($con, $_SESSION['id']);
                            foreach ($categories as $category) {
                                if(isset($_GET['id'])) {
                                    $appended_categories = get_appended_categories($con, $_SESSION['id'], $_GET['id'], 'project');
                                    $checked = '';
                                    foreach($appended_categories as $appended_category) {
                                        if($appended_category['id'] == $category['id'])
                                            $checked = 'checked';
                                    }
                                }
                                else if($category['id'] == get_uncategorized_id($con, $_SESSION['id'])) {
                                    $checked = 'checked';
                                }
                                else {
                                    $checked = '';
                                }
                                
                                echo '
                                <div class="category_option">
                                    <input 
                                        type="checkbox" 
                                        name="category_'. $category['id'] . '" ' . 
                                        $checked .'
                                    >&nbsp;' . 
                                    $category['name'] . '
                                </div>';
                            }
                        ?>
                    </div><br><br>

                    <label>Бележки за прикачване:</label>
                    <div class="attach_container">
                        <?php
                        $notes = get_notes($con, $_SESSION['id']);
                        $attached_notes = get_attached_notes_to_a_project ($con, $_SESSION['id'], $_GET['id']);
                        $attached_note_ids = array_column($attached_notes, 'id');

                        foreach ($notes as $note) {
                            if(in_array($note['id'], $attached_note_ids))
                                $checked = 'checked';
                            else
                                $checked = '';

                            echo '
                            <div class="attach_option">
                                <input 
                                    type="checkbox" 
                                    name="note_'. $note['id'] . '" ' . 
                                    $checked .'
                                >&nbsp;' . 
                                $note['title'] . '<br>
                            </div>';
                        }
                        ?>
                    </div>

                    <input type="submit" class="button" value="Въведи" name="submit">
                </form>
            </div>      
        </div>
    </div>
</body>
</html>
