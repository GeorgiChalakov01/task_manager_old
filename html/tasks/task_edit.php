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

                    <label for="blocker">Блокираща</label>
                    <br>
                    <input
                        type="checkbox"
                        name="blocker"
                        <?php 
                            if(isset($_GET['blocker']))
                                echo 'checked';
                        ?>
                    ><br>
                                
                    <label for="duration">Очаквана продължителност в минути</label>
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

                    <label for="reminder">Създай напомняне</label>
                    <br>
                    <input
                        type="checkbox"
                        name="reminder"
                        <?php 
                            if(isset($_GET['reminder']))
                                echo 'checked';
                        ?>
                    ><br>

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
        
                    <p>Права...</p>
                    <input type="submit" class="button" value="Въведи" name="submit">
                </form>
            </div>      
        </div>
    </div>
</body>
</html>
