<?php
    session_start();
    
    if(isset($_SESSION["id"]))
    {
        header("location: ../tasks/home.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Регистрация</title>
    
    <?php include '../PWA/headers.php';?>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <input type="checkbox" id="check" <?php if(isset($_GET['error_signup']))echo 'checked'?>>
        <div class="login form">
            <header>Вход</header>
            <p class="status"><?php if(isset($_GET['status_signin']))echo $_GET["status_signin"];?></p>
            <p class="error"><?php if(isset($_GET['error_signin']))echo $_GET["error_signin"];?></p>
            <form action="signin.inc.php" method="post">
                <input type="text" placeholder="Имейл" autocomplete="on" name="email">
                <input type="password" autocomplete="on" placeholder="Парола" name="password">
                <a href="password_recovery.php">Възстановяване на парола</a>
                <input type="submit" class="button" value="Вход" name="submit">
            </form>
            <div class="signup">
                <span class="signup">Нямате акаунт?
                    <label for="check">Регистрация</label>
                </span>
            </div>
        </div>


        <div class="registration form">
            <header>Регистрация</header>
            <p class="error"><?php if(isset($_GET['error_signup']))echo $_GET["error_signup"];?></p>
            <form action="signup.inc.php" method="post">
                <input type="text" placeholder="Име" name="first_name">
                <input type="text" placeholder="Фамилия" name="last_name">
                <input type="text" placeholder="Имейл" name="email">
                <input type="text" placeholder="Потребителско Име" name="username">
                <input type="password" placeholder="Парола" name="password">
                <input type="password" placeholder="Потвърдете паролата" name="password_repeat">
                <label for="profile_picture">Снимка</label>
                <input type="file" accept="image/*" id="profile_picture" name="profile_picture">
                <input type="submit" class="button" value="Регистрирайте се" name="submit">
            </form>
            <div class="signup">
                <span class="signup">Имате регистрация?
                    <label for="check">Вход</label>
                </span>
            </div>

        </div>
      
    </div>
</body>
</html>
