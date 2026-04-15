<?php
    session_start();
    
    $restaurant = $_SESSION['Restaurant'] ?? null;
    $address = $_SESSION['Address'] ?? null;
    $city = $_SESSION['City'] ?? null;
    $name = $_SESSION['Name'] ?? null;
    $surname = $_SESSION['Surname'] ?? null;
    $clients = $_SESSION['Clients'] ?? null;
    $date = $_SESSION['Date'] ?? null;
    $shift = $_SESSION['Shift'] ?? null;
    $hour = $_SESSION['Hour'] ?? null;

    if ($shift == "lunch")
        $shift = "Pranzo";
    else 
        $shift = "Cena";
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Reservo - Riepilogo</title>
        <link rel="stylesheet" href="../../css/fonts.css">
        <link rel="stylesheet" href="../../css/summary.css">
        <script src="../../js/addreservation.js" defer></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h2>Riepilogo della prenotazione</h2>
        <div id="container">
            <div id="summary">
                <div class="row">
                    <label>Ristorante:</label>
                    <p><?= htmlspecialchars($restaurant) ?></p>
                </div>
                <div class="row">
                    <label>Indirizzo:</label>
                    <p><?= htmlspecialchars($address) . ", " . htmlspecialchars($city) ?></p>
                </div>
                <div class="row">
                    <label>A nome:</label>
                    <p><?= htmlspecialchars($name) . " " . htmlspecialchars($surname) ?></p>
                </div>
                <div class="row">
                    <label>Numero di persone:</label>
                    <p><?= htmlspecialchars($clients) ?></p>
                </div>
                <div class="row">
                    <label>Turno:</label>
                    <p><?= htmlspecialchars($shift) ?></p>
                </div>
                <div class="row">
                    <label>Data:</label>
                    <p><?= htmlspecialchars($date) ?></p>
                </div>
                <div class="row">
                    <label>Ora:</label>
                    <p><?= htmlspecialchars($hour) ?></p>
                </div>
            </div>
            <div id="buttons">
                <button id="confirm" onclick="confirmReservation()">Conferma</button>
                <button id="cancel" onclick="goBack()">Annulla</button>
            </div>
        </div>
    </body>
</html>
