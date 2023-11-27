<!DOCTYPE html>
<html>
<head>
    <title>Регистрация</title>
    
    <?php include __DIR__ . '/PWA/headers.php';?>
    <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
      
    <input type="checkbox" id="check">
      
    <div class="login form">
        
      <header>Вход</header>
      <form action="signin.inc.php" method="post">
        <input type="text" placeholder="Потребителско име" name="username">
        <input type="password" placeholder="Парола" name="password">
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
        
      <form action="#">
        <input type="text" placeholder="Въведете имейл">
        <input type="password" placeholder="Въведете парола">
        <input type="password" placeholder="Потвърдете паролата">
        <input type="button" class="button" value="Регистрирайте се">
      </form>
        
      <div class="signup">
        <span class="signup">Вече сте регистрирани?
         <label for="check">Вход</label>
        </span>
      </div>
        
    </div>
      
  </div>
</body>
</html>
