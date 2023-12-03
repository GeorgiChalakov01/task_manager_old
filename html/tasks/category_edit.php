<!DOCTYPE html>
<html>
<head>
    <title>Категории</title>
    <?php include '../PWA/headers.php'?>
    <?php include 'common_php/head.php'?>
    <link rel="stylesheet" href="styles/form.css">
</head>
<body>
    <?php include 'common_php/body.php'?>

    <div class="content">
        <div class="container">
            <div class="form">
                <header>Категория</header>
                <p class="status"><?php if(isset($_GET['status']))echo $_GET["status"];?></p>
                <p class="error"><?php if(isset($_GET['error']))echo $_GET["error"];?></p>
                <form action="category_edit.inc.php" method="post">
                    <input type="hidden" name="id" value="-1" id = "selected-task">
                    
                    <lable for="color">Име</lable>
                    <input type="text" autocomplete="off" name="name">
                    
                    <lable for="text_color">Цвят на текста</lable>
                    <input type="color" name="text_color">
                    
                    <lable for="background_color">Цвят</lable>
                    <input type="color" name="background_color">
                    
                    <input type="submit" class="button" value="Въведи" name="submit">
                </form>
            </div>      
        </div>
    </div>
</body>
</html>
