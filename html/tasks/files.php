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

        <a href="file_upload.php" class="controls link_button">Качване</a>
        <a href="file_archive.php" class="controls link_button">Архив</a>

        <br>
        <br>
        <br>
        <br>

        <div class="category">
            <button type="button" class="collapsible">CategoryName</button>
            <div class="content">
                <div class="file">
                    <img src="image.png">
                    <table>
                        <tr>
                            <td>Име</td>
                            <td>Tobias</td>
                        </tr>
                        <tr>
                            <td>Заглавие</td>
                            <td>Tobias</td>
                        </tr>
                        <tr>
                            <td>Описание</td>
                            <td>Tobias</td>
                        </tr>
                        <tr>
                            <td>Дата</td>
                            <td>Tobias</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <br>
  
        <script src="scripts/files.js"></script>
    </body>
</html>
