<!DOCTYPE html>
<html>
    <head>
        <title>Бележки</title>
        <?php include '../PWA/headers.php'?>
        <?php include 'common_php/head.php'?>
    </head>
    <body>
        <?php include 'common_php/body.php'?>

        <a href="note_edit.php" class="controls link_button">Създаване</a>
        <a href="note_archive.php" class="controls link_button">Архив</a>

        <?php include 'common_php/statuserror.php'?>
        <br><br><br><br><br>


        <?php
        require_once '../db/dbh.inc.php';
        require_once 'common_php/functions.inc.php';

        $categories = get_categories($con, $_SESSION['id']);
        $rows = get_notes($con, $_SESSION['id']);

        foreach($categories as $category){
            echo '
            <div class="collapsable_container">
            <button type="button" class="collapsible">' . $category['name'] . '</button>
            <div class="content">';
            
            foreach($rows as $row) {                
                if($row['category_id'] == $category['id']) {
                    echo '
                    <a href="note_edit.php?id=' . $row['id'] . '&title=' . $row['title'] . '&description=' . $row['description'] . '&category_id=' . $row['category_id'] . '" class="link">
                        <div class="element">
                            <h2>' . $row['title'] . '</h2>
                            <p>' . nl2br($row['description']) . '</p>
                            <p>' . substr($row['created_on'], 0, 16) . '</p>
                        </div>
                    </a><br>';
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
