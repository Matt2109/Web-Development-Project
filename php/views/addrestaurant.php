<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Reservo - Aggiungi ristorante</title>
        <link rel="stylesheet" href="../css/fonts.css">
        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/addrestaurant.css">
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
            <div class="headDx">
                <p class="info">Aggiungi un ristorante</p>
                <a class="logout" href="logout.php">Esci</a>
            </div>
        </header>
        <div id="container">
            <div id="text">
                <p class="mess">Completa i campi per aggiungere un ristorante</p>
                <p>Alcune indicazioni:</p>
                <p><small>Il nome del locale e quello della città possono essere composti da più parole e ognuna di queste deve cominciare con una lettera maiuscola.</small></p>
                <p><small>L'indirizzo va inserito nel formato "Via/Piazza NomeVia NumeroCivico".</small></p>
            </div>
            <form id="addForm" action="addrestaurant.php" method="post">
                <input type="text" id="name" name="name" placeholder="Nome" pattern="^[A-Z][a-z]+(?:\s(?:[A-Z][a-z]+|[A-Z]'[A-Z][a-z]+))*$" required>
                <input type="text" id="city" name="city" placeholder="Città" pattern="^[A-Z][a-z]+(?:\s(?:[A-Z][a-z]+|[A-Z]'[A-Z][a-z]+))*$" required>
                <input type="text" id="address" name="address" placeholder="Indirizzo" pattern="^[A-Z][a-z]+(?:\s(?:[A-Z][a-z]+|[A-Z]'[A-Z][a-z]+))*\s[1-9][0-9]{0,3}$" required>
                <select id="type" name="type">
                    <option value="" disabled selected>Tipologia</option>
                    <option value="0">Pizzeria</option>
                    <option value="1">Cucina italiana</option>
                    <option value="2">Cucina asiatica</option>
                    <option value="3">Fast food</option>
                    <option value="4">Braceria</option>
                    <option value="5">Ristorante di pesce</option>
                </select>
                <input type="number" id="covers" name="covers" min="1" max ="999" placeholder="Numero di coperti" required>
                <input type="submit" class="submit" value="Aggiungi ristorante">
                <?php if ($error): ?>
                    <p class="error"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
            </form>
        </div>
    </body>
</html>

