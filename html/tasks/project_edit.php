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
                    
                    <label for="title">Заглавие</label>
                    <input 
                        type="text" 
                        name="title" 
                        value=
                            "<?php 
                            if(isset($_GET['title']))
                                echo $_GET['title'];
                            ?>"
                        >
                    
                    <label for="description">Съдържание</label>
                    <br><textarea
                        name="description"
                        value=
                            "<?php 
                            if(isset($_GET['description']))
                                echo $_GET['description'];
                            ?>"
                    ></textarea><br><br>

                    <label for="deadline">Краен Срок</label>
                    <input 
                        type="datetime-local"
                        name="deadline" 
                        value=
                            "<?php 
                            if(isset($_GET['deadline']))
                                echo $_GET['deadline'];
                            ?>"
                    >

                    <label for="category">Категория</label>
                    <select name="category">
                        <option value="-1"> Неподредени </option>
                        <?php
                        require_once '../db/dbh.inc.php';
                        require_once 'common_php/functions.inc.php';

                        $categories = get_categories($con, $_SESSION['id']);
                        
                        foreach($categories as $category) {
                            $selected = ($category['id'] == $_GET['category_id']) ? ' selected' : '';
                            echo '
                            <option value="' . $category['id'] .'"' . 
                                $selected . 
                            '>' .
                                $category['name'] . 
                            '</option>';
                        }
                        ?>
                    </select>
                    <p>Права...</p>
                    <input type="submit" class="button" value="Въведи" name="submit">
                </form>
            </div>      
        </div>
    </div>
</body>
</html>
