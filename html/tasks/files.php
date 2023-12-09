<!DOCTYPE html>
<html>
    <head>
        <title>Файлове</title>
        <?php include '../PWA/headers.php'?>
        <?php include 'common_php/head.php'?>
        <link rel="stylesheet" href="styles/files.css">
    </head>
    <body>
        <?php include 'common_php/body.php'?>

        <a href="file_edit.php?file_id=-1" class="controls link_button">Качване</a>
        <a href="file_archive.php" class="controls link_button">Архив</a>

        <?php include 'common_php/statuserror.php'?>


        <?php
        require_once '../db/dbh.inc.php';
        require_once 'common_php/functions.inc.php';

        $categories = get_categories($con, $_SESSION['id']);
        $rows = get_files($con, $_SESSION['id']);

        foreach($categories as $category){
            echo '
            <div class="category">
                    <button type="button" class="collapsible">' . $category['name'] . '</button>
                    <div class="content">
            ';
            foreach($rows as $row) {
                $extension = $row['extension'];
                $source_image = 'images/file.png';
                $data = base64_encode($row['file']);
                
                if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $source_image = $row['full_path'];
                }
                if($row['category_id'] == $category['id']) {
                    echo '
                        <div class="file">
                            <img src="' . $source_image . '">
                            <table>
                                <tr>
                                    <td>Файл</td>
                                    <td>
                                        <a 
                                            href="' . $row['full_path'] . '" 
                                            download="' .$row['name'] . '.' . $row['extension'] . '"
                                        >' . 
                                            $row['name'] . '.' . $row['extension'] .'
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Заглавие</td>
                                    <td>' . $row['title'] . '</td>
                                </tr>
                                <tr>
                                    <td>Описание</td>
                                    <td>' . $row['description'] . '</td>
                                </tr>
                                <tr>
                                    <td>Дата</td>
                                    <td>' . $row['uploaded_on'] . '</td>
                                </tr>
                            </table>
                        </div>
                        ';
                    }
                }
            echo '
                    </div>
                </div>
                
                <br>';
        }
        ?>
        <script src="scripts/files.js"></script>
    </body>
</html>
