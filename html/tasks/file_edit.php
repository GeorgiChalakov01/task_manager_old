<!DOCTYPE html>
<html>
<head>
    <title><?php if(isset($_GET['id'])) echo 'Промяна на Файл'; else echo 'Добавяне на Файл';?></title>
    <?php include '../PWA/headers.php'?>
    <?php include 'common_php/head.php'?>
    <link rel="stylesheet" href="styles/form.css">
</head>
<body>
    <?php include 'common_php/body.php'?>

    <div class="content">
        <div class="container">
            <div class="form">
                <header><?php if(isset($_GET['id'])) echo 'Промяна на Файл'; else echo 'Добавяне на Файл';?></header>
                <p class="status"><?php if(isset($_GET['status']))echo $_GET["status"];?></p>
                <p class="error"><?php if(isset($_GET['error']))echo $_GET["error"];?></p>
        
                <form action="file_edit.inc.php" method="post" enctype="multipart/form-data">
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
                    
                    <lable for="title">Име</lable>
                    <input 
                        type="text" 
                        name="title" 
                        value=
                            "<?php 
                            if(isset($_GET['title']))
                                echo $_GET['title'];
                            ?>"
                        >
                    
                    <lable for="description">Описание</lable>
                    <br>
                    <textarea name="description"><?php 
                        if(isset($_GET['description']))
                            echo trim(urldecode($_GET['description']));
                    ?></textarea><br><br>

                    
                    <lable for="file">Файл</lable>
                    <input 
                        type="file" 
                        name="file"
                    >

                    <lable for="categories"> Категории</lable>
                    <div class="category_container">
                        <?php
                            require_once '../db/dbh.inc.php';
                            require_once 'common_php/functions.inc.php';
    
                            $categories = get_categories($con, $_SESSION['id']);
                            foreach ($categories as $category) {
                                if(isset($_GET['id'])) {
                                    $appended_categories = get_appended_categories($con, $_SESSION['id'], $_GET['id'], 'file');
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
                                </div>'
                                ;
                                $category_number++;
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
