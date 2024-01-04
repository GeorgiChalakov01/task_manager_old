<!DOCTYPE html>
<html>
    <head>
        <title>Проект</title>
        <?php include '../PWA/headers.php'?>
        <?php include 'common_php/head.php'?>
    </head>
    <body>
        <?php include 'common_php/body.php'?>
        <?php include 'common_php/statuserror.php'?>


        <?php
        require_once '../db/dbh.inc.php';
        require_once 'common_php/functions.inc.php';


        $project = get_task_info($con, $_SESSION['id'], $_GET['id'])[0];
        ?>
        <a href="task_finish.php" class="controls link_button">Приключване</a>
        <a href="task_edit.php" class="controls link_button">📝</a>
        <br><br><br>
        <?php
        $blocker;
        if($project['blocker'])
            $blocker = 'Да'; 
        else 
            $blocker = 'Не';

        
        echo '<h1>' . $project['title'] . '</h1>';
        echo '<p>' . nl2br($project['description']) . '</p> <br>';
        echo '<p> Краен Срок: ' . substr($project['deadline'], 0, 16) . '</p>';
        echo '<p> Блокираща: ' . $blocker . '</p>';


        echo '<br><p> Прикачени Файлове:</p>';
        ?>

        

        
        <script src="scripts/script.js"></script>
    </body>
</html>