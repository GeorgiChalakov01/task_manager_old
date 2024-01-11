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
        $rows = get_notes_with_categories($con, $_SESSION['id']);

        foreach($categories as $category){
            echo '
            <div class="collapsable_container">
            <button type="button" class="collapsible">' . $category['name'] . '</button>
            <div class="content">';
            
            foreach($rows as $row) {                
                if($row['category_id'] == $category['id']) {
                    echo '
                    <div class="element">
                        <a href="note_edit.php?id=' . $row['id'] . '&title=' . $row['title'] . '&description=' . urlencode($row['description']) . '&category_id=' . $row['category_id'] . '" class="link">
                            <h2>' . $row['title'] . '</h2>
                            <p>' . nl2br($row['description']) . '</p>
                            <p>' . substr($row['created_on'], 0, 16) . '</p>
                        </a>';
                    
                    $attached_files = get_attached_files_to_a_note ($con, $_SESSION['id'], $row['id']);
                    foreach($attached_files as $attached_file) {
                        $source_image = 'images/file.png';
        
                        if(isset($attached_file['extension'])) $filename = $attached_file['name'] . '.' . $attached_file['extension'];
                        else $filename = $attached_file['name'];
                        
                        if(in_array($attached_file['extension'], ['jpg', 'jpeg', 'png', 'gif', 'ico', 'webp'])) {
                            $source_image = $attached_file['full_path'];
                        }
                        
                        echo '
                        <a style="display: flex; flex-direction: column;"
                            href="' . $attached_file['full_path'] . '" 
                            download="' . $attached_file['name'] . '.' . $attached_file['extension'] . '"
                        >' . 
                            $attached_file['name'] . '.' . $attached_file['extension'] .'
                            <img src="' . $source_image . '">
                        </a>';
                    }
                    
                    echo '
                        <a
                            class="delete" 
                            href="note_delete.inc.php?id=' . $row['id'] . '"
                        >
                            X
                        </a>
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
