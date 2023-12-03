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

        <form action="file_upload.inc.php" method="post" enctype="multipart/form-data">
            Select image to upload:<br>
            <input type="file" name="fileToUpload" id="fileToUpload"><br>
            <input type="submit" value="Upload Image" name="submit">
        </form>
    </body>
</html>
