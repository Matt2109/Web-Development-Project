<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Reservo - Accedi</title>
        <link rel="stylesheet" href="../css/fonts.css">
        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/login.css">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <header>
            <div class="headSx">
                <div id="logo">
                    <img src="../images/logo.png" class="logo" alt="logo">
                    <a class="name">eservo</a>
                </div>
                <a class="homepage" href="index.php">Home</a>
            </div>
            <div class="headDx"></div>
        </header>
        <div id="loginBody">
            <div id="text">
                <p class="welcome">Benvenuto</p>
                <p class="mess">Inserisci le tue credenziali per accedere</p>
            </div>
            <div id="loginPanel">
                <form id="loginForm" action="login.php" method="post">
                    <input type="text" class="loginInput" id="username" name="username" placeholder="Username" pattern="^[A-Za-z0-9]{4,20}$" required>
                    <input type="password" class="loginInput" id="pswd" name="pswd" placeholder="Password" pattern="^[\S]{8,32}$" required>
                    <input type="submit" value="Accedi" id="login" name="login">
                    <?php if ($mess): ?>
                        <p class="errorMess"><?= htmlspecialchars($mess) ?></p>
                    <?php endif; ?>
                </form>
                <p>Non sei ancora registrato?</p>
                <a class="regButton" href="register.php">Registrati</a>
            </div>
        </div>
    </body>
</html>