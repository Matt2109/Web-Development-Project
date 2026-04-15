<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Reservo - Registrati</title>
        <link rel="stylesheet" href="../css/fonts.css">
        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/register.css">
        <script src="../js/register.js" defer></script>
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
        <div id="registerBody">
            <div id="text">
                <p class="mess">Completa tutti i campi per iscriverti</p>
                <p>Alcune indicazioni:</p>
                <p><small>Nome e cognome possono essere composti da più parole e ognuna di queste deve cominciare con una lettera maiuscola.</small></p>
                <p><small>L'username deve essere compreso tra i 4 e i 20 caratteri e non può contenere spazi o caratteri speciali.</small></p>
                <p><small>La password deve essere lunga almeno 8 caratteri e non superare i 32.</small></p>
            </div>
            <div id="registerPanel">
                <form id="regForm" action="register.php" method="post">
                    <div id="regFieldset">
                        <label class="regLabel">Registrati come</label>
                        <select id="regSelect" id="type" name="type">
                            <option value="0">Cliente</option>
                            <option value="1">Gestore</option>
                        </select>
                    </div>
                    <input class="regInput" type="text" id="fname" name="fname" placeholder="Nome" pattern="^[A-Z][a-z]+(?:\s(?:[A-Z][a-z]+|[A-Z]'[A-Z][a-z]+))*$" required>
                    <input class="regInput" type="text" id="lname" name="lname" placeholder="Cognome" pattern="^[A-Z][a-z]+(?:\s(?:[A-Z][a-z]+|[A-Z]'[A-Z][a-z]+))*$" required>
                    <input class="regInput" type="text" id="username" name="username" placeholder="Username" pattern="^[A-Za-z0-9]{4,20}$" required>
                    <input class="regInput" type="password" id="pswd" name="pswd" placeholder="Password" pattern="^[\S]{8,32}$" required>
                    <input class="regInput" type="password" id="pswdconfirm" name="pswdconfirm" placeholder="Conferma password" required>
                    <?php if ($mess): ?>
                        <p class="error"><?= htmlspecialchars($mess) ?></p>
                    <?php endif; ?>
                    <input id="register" type="submit" value="Registrati">
                </form>
            </div>
        </div>
    </body>
</html>