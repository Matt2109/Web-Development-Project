<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Reservo - Dashboard</title>
        <link rel="stylesheet" href="../css/fonts.css">
        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/dashboard.css">
        <script src="../js/dashboard.js" defer></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body onload="init()">
        <header>
            <div class="headSx">
                <div id="logo">
                    <img src="../images/logo.png" class="logo" alt="logo">
                    <a class="name">eservo</a>
                </div>
                <a class="homepage" href="index.php">Home</a>
            </div>
            <div class="headDx">
                <p class="info">Gestisci i tuoi locali</p>
                <a class="logout" href="logout.php">Esci</a>
            </div>
        </header>
        <div id="container">
            <div id="restaurants">
                <div class="addRes">
                    <h2>I tuoi locali</h2>
                    <img src="../images/plus.svg" class="plus" data-url="addrestaurant.php" alt="aggiungi">
                </div>
                <?php if (empty($restaurants)): ?>
                    <p>Non hai ancora aggiunto ristoranti</p>
                <?php else: ?>
                <?php $i = 0 ?>
                <?php foreach ($restaurants as $restaurant): ?>
                    <div class="resButtons">
                        <p class="resName"><?= htmlspecialchars($restaurant['Name']) ?></p>
                        <button class="showReserv" onclick="showInput(<?= htmlspecialchars($restaurant['Id']) ?>)">Mostra prenotazioni</button>
                        <button class="removeRest" onclick="removeRest(event)" data-url=<?= "removerestaurant.php?resid=".htmlspecialchars($restaurant['Id']) ?>>Rimuovi ristorante</button>
                    </div>
                    <div id="shifts">
                        <div class="addShift">
                            <h2>Turni</h2>
                            <img src="../images/plus.svg" class="plus" alt="aggiungi" onclick="setResId(<?= htmlspecialchars($restaurant['Id']) ?>)">
                        </div>
                        <?php if (!empty($lunchShifts[$i])): ?>
                            <p>Pranzo</p>
                            <div class="shiftBox">
                                <p><?= htmlspecialchars($lunchShifts[$i]['Opening'])."-".htmlspecialchars($lunchShifts[$i]['Closure']) ?></p>
                                <img src="../images/edit.svg" class="settings" alt="modifica" onclick="getExceptions(event)" data-resid=<?= htmlspecialchars($restaurant['Id']) ?> data-resname="<?= htmlspecialchars($restaurant['Name']) ?>" data-shift="0">
                                <img src="../images/trash.svg" class="remove" alt="rimuovi" data-url=<?= "removeshift.php?resid=".htmlspecialchars($restaurant['Id'])."&shift=0" ?>>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($dinnerShifts[$i])): ?>
                            <p>Cena</p>
                            <div class="shiftBox">
                                <p><?= htmlspecialchars($dinnerShifts[$i]['Opening'])."-".htmlspecialchars($dinnerShifts[$i]['Closure']) ?></p>
                                <img src="../images/edit.svg" class="settings" alt="modifica" onclick="getExceptions(event)" data-resid=<?= htmlspecialchars($restaurant['Id']) ?> data-resname="<?= htmlspecialchars($restaurant['Name']) ?>" data-shift="1">
                                <img src="../images/trash.svg" class="remove" alt="rimuovi" data-url=<?= "removeshift.php?resid=".htmlspecialchars($restaurant['Id'])."&shift=1" ?>>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php $i++ ?>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div id="reservations">
                <h2>Prenotazioni</h2>
                <div id="showInput"></div>
                <div id="showReserv"></div>
            </div>
            <div id="exceptions">
                <h2>Eccezioni</h2>
                <p id="info">Clicca sull'icona di edit del turno per aggiungere o modificare le eccezioni</p>
                <div id="exform">
                    <p>Seleziona una data</p>
                    <input id="exdata" type="date">
                    <button id="exbutton" data-resid="" data-shift="" onclick="addException(event)">Aggiungi eccezione</button>
                </div>
                <div id="exlist"></div>
            </div>
        </div>
    </body>
</html>