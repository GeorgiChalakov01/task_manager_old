<!DOCTYPE html>
<html>
<head>
    <title>Начало</title>
    <?php include '../PWA/headers.php'?>
    <?php include 'common_php/head.php'?>
    <link rel="stylesheet" href="styles/categories.css">
</head>
<body>
    <?php include 'common_php/body.php'?>

    <a href="category_edit.php" class="link_button">Добави</a>

    <br>
    <br>
    <br>
    <p class="status"><?php if(isset($_GET['status']))echo $_GET["status"];?></p>
    <p class="error"><?php if(isset($_GET['error']))echo $_GET["error"];?></p>
    <br>
    <br>
    <br>
    <?php
    require_once '../db/dbh.inc.php';
    require_once 'common_php/functions.inc.php';
    
    $rows = get_categories($con, $_SESSION['id']);

    foreach($rows as $row) {
        echo '
        <div 
            class="category" 
            style="
                background-color: ' . $row['background_color'] . ';
            "
        >
            <a 
                class="name"
                style="color: ' . $row['text_color'] .'"; 
                href=
                    "category_edit.php?id=' . $row['id'] . 
                    '&name=' . urlencode($row['name']) . 
                    '&text_color=' . urlencode($row['text_color']) . 
                    '&background_color=' . urlencode($row['background_color']) . 
                '"
            >' 
                . $row['name'] . 
            '</a>
            
            <a 
                class="delete" 
                href="category_delete.inc.php?id=' . $row['id'] . '"
            >
                &nbspX&nbsp
            </a>
        </div>
        <br>
        ';
    }
    ?>
</body>
</html>
