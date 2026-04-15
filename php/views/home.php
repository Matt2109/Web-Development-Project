<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Reservo - Homepage</title>
        <link rel="stylesheet" href="../css/fonts.css">
        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/home.css">
        <script src="../js/home.js" defer></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Matteo Fragassi">
    </head>
    <body id="homeBody" onload="init()">
        <header id="homeHeader">
            <div class="headSx">
                <div id="logo">
                    <img src="../images/logo.png" class="logo" alt="logo">
                    <a class="name">eservo</a>
                </div>
                <a class="homepage" href="index.php">Home</a>
                <a class="homepage" href="../html/guide.html">Guida</a>
            </div>
            <?php if (isset($_SESSION['UID'])): ?>
                <div class="logBar">
                    <p class="session"><?= "Bentornato " . htmlspecialchars($_SESSION['Name']) ?></p>
                    <a href="#reservations" class="navLink">Le mie prenotazioni</a>
                    <a class="logout" href="logout.php">Esci</a>
                </div>
            <?php else: ?>
                <div class="headDx">
                    <a class="login" href="login.php">Accedi</a>
                    <a class="register" href="register.php">Registrati</a>
                </div>
            <?php endif; ?>
        </header>
        <p id="title">Trova il locale giusto per te</p>
        <div id="bodyContainer">
            <div id="restContainer">
                <div id="searchContainer">
                    <img src="../images/search.svg" class="search" alt="ricerca">
                    <input type="text" id="search" placeholder="Inserisci il nome di un locale">
                </div>
                <div id="restSection">
                    <?php if (empty($restaurants)): ?>
                        <p>Nessun ristorante presente.</p>
                    <?php else: ?>
                    <?php foreach ($restaurants as $restaurant): ?>
                        <div class="restPanel" id=<?= htmlspecialchars($restaurant['Id']) ?>>
                                <?php
                                    switch($restaurant['Type']) {
                                        case 0:
                                            echo '<img src="../images/pizza.png" class="kitchen" alt="type">';
                                            break;
                                        case 1:
                                            echo '<img src="../images/spaghetti.png" class="kitchen" alt="type">';
                                            break;
                                        case 2:
                                            echo '<img src="../images/sushi.png" class="kitchen" alt="type">';
                                            break;
                                        case 3:
                                            echo '<img src="../images/fast-food.png" class="kitchen" alt="type">';
                                            break;
                                        case 4:
                                            echo '<img src="../images/steak.png" class="kitchen" alt="type">';
                                            break;
                                        case 5:
                                            echo '<img src="../images/fish.png" class="kitchen" alt="type">';
                                    }
                                ?>
                                <p class="resName"><?= $restaurant['Name'] ?></p>
                                <img src="../images/marker.svg" class="marker" alt="address">
                                <p class="address"><?= $restaurant['Address'] . ", " . $restaurant['City'] ?></p>
                                <?php if (isset($_SESSION['UID'])): ?>
                                    <button class="panelButton" onclick="setResId(<?= htmlspecialchars($restaurant['Id']) ?>)">Prenota</button>
                                <?php else: ?>
                                    <a class="panelLink" href="login.php">Prenota</a>
                                <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if (isset($_SESSION['UID'])): ?>
            <p class="reservations">Le tue prenotazioni</p>
            <div id="reservations">
                <?php if (empty($reservations)): ?>
                    <p>Non hai ancora effettuato prenotazioni.</p>
                <?php else: ?>
                    <?php foreach ($reservations as $reservation): ?>
                        <div class="reservPanel" id=<?= htmlspecialchars($reservation['Id']) ?>>
                            <h3><?= htmlspecialchars($reservation['Name']) ?></h3>
                            <small><?= $reservation['Address'].", ".$reservation['City'] ?></small>
                            <p><?= "Data: ".$reservation['Date'] ?></p>
                            <p><?= "Ora: ".$reservation['Hour'] ?></p>
                            <p><?= "Coperti: ".$reservation['Covers'] ?></p>
                            <button class="removeReserv" onclick="removeReserv(event)">Elimina prenotazione</button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </body>
</html>