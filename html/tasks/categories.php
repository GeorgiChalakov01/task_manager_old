<!DOCTYPE html>
<html>
    <head>
        <title>Начало</title>
        <?php include '../PWA/headers.php'?>
        <?php include 'common_php/head.php'?>
    </head>
    <body>
        <?php include 'common_php/body.php'?>

        <a href="category_edit.php" class="link_button">Добави</a>

        <?php include 'common_php/statuserror.php'?>
        <br><br><br><br><br>

        <?php
        require_once '../db/dbh.inc.php';
        require_once 'common_php/functions.inc.php';

        $rows = get_categories($con, $_SESSION['id']);

        foreach($rows as $row) {
            if($row['id'] == get_uncategorized_id($con, $_SESSION['id']))
            echo '
        <div 
            class="category" 
            style="background-color: ' . $row['background_color'] . ';"
        >
            <p style="color: ' . $row['text_color'] .';">' 
            . $row['name'] . 
            '</p>
        </div>
        <br>
        ';
            else
                echo '
        <a href="
            category_edit.php?id=' . $row['id'] . '
            &name=' . urlencode($row['name']) . '
            &text_color=' . urlencode($row['text_color']) . '
            &background_color=' . urlencode($row['background_color']) . '"
        >
            <div 
                class="category" 
                style="background-color: ' . $row['background_color'] . ';"
            >
                <p style="color: ' . $row['text_color'] .';">' 
                . $row['name'] . 
                '</p>

                <a
                    class="delete"
                    href="category_delete.inc.php?id=' . $row['id'] . '"
                >
                    X
                </a>
            </div>
        </a>
        <br>
        ';
        }
        ?>
    </body>
</html>
