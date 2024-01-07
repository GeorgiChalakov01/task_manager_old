<!DOCTYPE html>
<html>
<head>
    <title><?php if(isset($_GET['id'])) echo 'Промяна на Категория'; else echo 'Добавяне на Категория';?></title>
    <?php include '../PWA/headers.php'?>
    <?php include 'common_php/head.php'?>
    <link rel="stylesheet" href="styles/form.css">
</head>
<body>
    <?php include 'common_php/body.php'?>

    <div class="content">
        <div class="container">
            <div class="form">
                <header><?php if(isset($_GET['id'])) echo 'Промяна на Категория'; else echo 'Добавяне на Категория';?></header>
                <p class="status"><?php if(isset($_GET['status']))echo $_GET["status"];?></p>
                <p class="error"><?php if(isset($_GET['error']))echo $_GET["error"];?></p>
                <form action="category_edit.inc.php" method="post">
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
                    
                    <label for="color">Име</label>
                    <input 
                        type="text" 
                        autocomplete="off" 
                        name="name" 
                        value=
                            "<?php 
                            if(isset($_GET['name']))
                                echo $_GET['name'];
                            ?>"
                        >
                    
                    <label for="text_color">Цвят на текста</label>
                    <input 
                        type="color" 
                        name="text_color"
                        value=
                            "<?php 
                            if(isset($_GET['text_color']))
                                echo $_GET['text_color'];
                            ?>"
                    >
                    
                    <label for="background_color">Цвят на категорията</label>
                    <input 
                        type="color" 
                        name="background_color"
                        value=
                            "<?php 
                            if(isset($_GET['background_color']))
                                echo $_GET['background_color'];
                            ?>"
                    >
                    
                    <input type="submit" class="button" value="Въведи" name="submit">
                </form>
            </div>      
        </div>
    </div>
</body>
</html>
