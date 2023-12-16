<!DOCTYPE html>
<html>
    <head>
        <title>Файлове</title>
        <?php include '../PWA/headers.php'?>
        <?php include 'common_php/head.php'?>
    </head>
    <body>
        <?php include 'common_php/body.php'?>

        <a href="file_edit.php" class="controls link_button">Качване</a>
        <a href="file_archive.php" class="controls link_button">Архив</a>

        <?php include 'common_php/statuserror.php'?>


        <?php
        require_once '../db/dbh.inc.php';
        require_once 'common_php/functions.inc.php';

        $categories = get_categories($con, $_SESSION['id']);
        $rows = get_files($con, $_SESSION['id']);

        foreach($categories as $category){
            echo '
            <div class="collapsable_container">
                    <button type="button" class="collapsible">' . $category['name'] . '</button>
                    <div class="content">
            ';
            foreach($rows as $row) {
                $filename;
                $source_image = 'images/file.png';

                if(isset($row['extension'])) $filename = $row['name'] . '.' . $row['extension'];
                else $filename = $row['name'];
                
                if(in_array($row['extension'], ['jpg', 'jpeg', 'png', 'gif', 'ico'])) {
                    $source_image = $row['full_path'];
                }
                if($row['category_id'] == $category['id']) {
                    echo '
                        <div class="file">
                            <img src="' . $source_image . '">
                            <table>
                                <tr>
                                    <td>Файл</td>
                                    <td class="splitword">
                                        <a 
                                            href="' . $row['full_path'] . '" 
                                            download="' . $filename . '"
                                        >' . 
                                            $filename .'
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Заглавие</td>
                                    <td class="splitword">' . $row['title'] . '</td>
                                </tr>
                                <tr>
                                    <td>Описание</td>
                                    <td class="splitword">' . nl2br($row['description']) . '</td>
                                </tr>
                                <tr>
                                    <td>Дата</td>
                                    <td class="splitword">' . substr($row['uploaded_on'], 0, 16) . '</td>
                                </tr>
                            </table>
                        </div>
                        <br>
                        ';
                    }
                }
            echo '
                    </div>
                </div>
                
                <br>';
        }
        ?>
        <script src="scripts/script.js"></script>
    </body>
</html>
