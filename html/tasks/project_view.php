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


        $project = get_project_info($con, $_SESSION['id'], $_GET['id'])[0];
        echo '<h1>' . $project['title'] . '</h1>';
        echo '<p>' . nl2br($project['description']) . '</p>';
        ?>

        <br><br>
        <a href="task_edit.php?project_id=<?php echo $_GET['id']?>" class="link_button">+</a>
        <button class="link_button">↓</button>
        <button class="link_button">↑</button>
        <br><br><br>

        <div class="task_container" id="task_container">
            <?php
            $rows = get_tasks($con, $_SESSION['id'], $_GET['id']);
            foreach($rows as $row) {
                echo '
                <div class="task">
                    <input type="checkbox">
                    &nbsp;&nbsp;
                    <a href="task_view.php?id='. $row['id'] .'">' . $row['title'] . '</a>
                </div>';
            }
            ?>
        </div>

        <script>
            function sizeDiv() {
                var div = document.getElementById('task_container');
                var rect = div.getBoundingClientRect();
                var offsetTop = window.pageYOffset + rect.top;
                div.style.height = (window.innerHeight - offsetTop - 20) + "px";
            };
            
            window.onload = sizeDiv();
            window.onresize = sizeDiv();
        </script>
        <script src="scripts/script.js"></script>
    </body>
</html>