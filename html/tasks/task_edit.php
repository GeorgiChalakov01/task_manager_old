<!DOCTYPE html>
<html>
<head>
    <title><?php if(isset($_GET['id'])) echo 'Промяна на Задача'; else echo 'Добавяне на Задача';?></title>
    <?php include '../PWA/headers.php'?>
    <?php include 'common_php/head.php'?>
    <link rel="stylesheet" href="styles/form.css">
</head>
<body>
    <?php include 'common_php/body.php'?>

    <div class="content">
        <div class="container">
            <div class="form">
                <header><?php if(isset($_GET['id'])) echo 'Промяна на Задача'; else echo 'Добавяне на Задача';?></header>
                <p class="status"><?php if(isset($_GET['status']))echo $_GET["status"];?></p>
                <p class="error"><?php if(isset($_GET['error']))echo $_GET["error"];?></p>
        
                <form action="task_edit.inc.php" method="post">
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
                    
                    <input 
                        type="hidden" 
                        name="project_id"
                        value=
                            "<?php 
                            echo $_GET['project_id'];
                            ?>"
                    >
                    
                    <lable for="title">Заглавие</lable>
                    <input 
                        type="text" 
                        name="title" 
                        value=
                            "<?php 
                            if(isset($_GET['title']))
                                echo $_GET['title'];
                            ?>"
                        >
                    
                    <lable for="description">Съдържание</lable>
                    <br><textarea
                        name="description"
                        value=
                            "<?php 
                            if(isset($_GET['description']))
                                echo $_GET['description'];
                            ?>"
                    ></textarea><br><br>

                    <lable for="blocker">Блокираща</lable>
                    <br>
                    <input
                        type="checkbox"
                        name="blocker"
                        <?php 
                            if(isset($_GET['blocker']))
                                echo 'checked';
                        ?>
                    ><br>
                                
                    <lable for="duration">Очаквана продължителност в минути</lable>
                    <input 
                        type="number"
                        name="duration"
                        min="0"
                        value=
                            "<?php 
                            if(isset($_GET['duration']))
                                echo $_GET['duration'];
                            ?>"
                    >

                    <lable for="reminder">Създай напомняне</lable>
                    <br>
                    <input
                        type="checkbox"
                        name="reminder"
                        <?php 
                            if(isset($_GET['reminder']))
                                echo 'checked';
                        ?>
                    ><br>

                    <lable for="deadline">Краен Срок</lable>
                    <input 
                        type="datetime-local"
                        name="deadline" 
                        value=
                            "<?php 
                            if(isset($_GET['deadline']))
                                echo $_GET['deadline'];
                            ?>"
                    >
        
                    <p>Права...</p>
                    <input type="submit" class="button" value="Въведи" name="submit">
                </form>
            </div>      
        </div>
    </div>
</body>
</html>
