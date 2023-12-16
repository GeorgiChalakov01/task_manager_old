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


        <?php
        require_once '../db/dbh.inc.php';
        require_once 'common_php/functions.inc.php';

        $categories = get_categories($con, $_SESSION['id']);
        $rows = get_notes($con, $_SESSION['id']);

        foreach($categories as $category){
            echo '
            <div class="collapsable_container">
                    <button type="button" class="collapsible">' . $category['name'] . '</button>
                    <div class="content">
            ';
            foreach($rows as $row) {                
                if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $source_image = $row['full_path'];
                }
                if($row['category_id'] == $category['id']) {
                    echo '
                        <div class="note">
                            <h2>' . $row['title'] . '</h2>
                            <p>' . nl2br($row['description']) . '</p>
                            <p>' . substr($row['created_on'], 0, 16) . '</p>
                        </div><br>';
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
