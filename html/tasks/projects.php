<!DOCTYPE html>
<html>
    <head>
        <title>Проекти</title>
        <?php include '../PWA/headers.php'?>
        <?php include 'common_php/head.php'?>
    </head>
    <body>
        <?php include 'common_php/body.php'?>

        <a href="project_edit.php" class="controls link_button">Създаване</a>
        <a href="project_archive.php" class="controls link_button">Архив</a>
        <br><br><br><br><br>
        
        <?php include 'common_php/statuserror.php'?>


        <?php
        require_once '../db/dbh.inc.php';
        require_once 'common_php/functions.inc.php';

        $categories = get_categories($con, $_SESSION['id']);
        $rows = get_projects($con, $_SESSION['id']);

        foreach($categories as $category){
            echo '
            <div class="collapsable_container">
            <button type="button" class="collapsible">' . $category['name'] . '</button>
            <div class="content">';
            
            foreach($rows as $row) {
                if($row['category_id'] == $category['id']) {
                    echo '
                        <a href="project_view.php?id=' . $row['id'] . '" class="link">
                            <div class="deadline">
                                <h2>' . $row['title'] . '</h2>
                                <p>' . nl2br($row['description']) . '</p>
                            </div><br>
                        </a>';
                    }
                }
            echo '
                    </div>
                </div><br>';
        }
        ?>
        <script src="scripts/script.js"></script>
    </body>
</html>
