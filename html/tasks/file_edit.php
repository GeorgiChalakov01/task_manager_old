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
        
                <form action="file_edit.inc.php?file_id=<?php echo $_GET['file_id'];?>" method="post" enctype="multipart/form-data">
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
                    <input 
                        type="text" 
                        name="description"
                        value=
                            "<?php 
                            if(isset($_GET['description']))
                                echo $_GET['description'];
                            ?>"
                    >
                    
                    <lable for="file"></lable>
                    <input 
                        type="file" 
                        name="file"
                        value=
                            "<?php 
                            if(isset($_GET['file']))
                                echo $_GET['file'];
                            ?>"
                    >

                    <lable for="category">Категория</lable>
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
