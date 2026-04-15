<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Reservo - Aggiungi turno</title>
        <link rel="stylesheet" href="../css/fonts.css">
        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/addshift.css">
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
                <p class="info">Aggiungi un turno</p>
                <a class="logout" href="logout.php">Esci</a>
            </div>
        </header>
        <div id="container">
            <div id="text">
                <p class="mess">Completa i campi per aggiungere un turno</p>
                <p>Alcune indicazioni:</p>
                <p><small>Un turno valido per il pranzo non può iniziare prima delle 11:00 e non può terminare dopo le 17:00.</small></p>
                <p><small>Un turno valido per la cena non può iniziare prima delle 17:00 e non può terminare dopo le 23:00.</small></p>
                <p><small>Le checkbox con i giorni permettono di indicare la validità del turno durante la settimana.</small></p>
                <p><small>In caso si vogliano aggiungere specifici giorni di chiusura si rimanda alla sezione eccezioni della dashboard.</small></p>
            </div>
            <form id="addShift" action=<?= "addshift.php?resid=".$resid ?> method="post">
                <label>Tipologia turno</label>
                <select id="type" name="type">
                    <option value="0">Pranzo</option>
                    <option value="1">Cena</option>
                </select>
                <label>Orario di apertura</label>
                <select id="opening" name="opening">
                    <option value="11:00">11:00</option>
                    <option value="12:00" selected>12:00</option>
                    <option value="13:00">13:00</option>
                    <option value="14:00">14:00</option>
                    <option value="15:00">15:00</option>
                    <option value="16:00">16:00</option>
                    <option value="17:00">17:00</option>
                    <option value="18:00">18:00</option>
                    <option value="19:00">19:00</option>
                    <option value="20:00">20:00</option>
                    <option value="21:00">21:00</option>
                    <option value="22:00">22:00</option>
                    <option value="23:00">23:00</option>
                </select>
                <label>Orario di chiusura</label>
                <select id="closure" name="closure">
                    <option value="11:00">11:00</option>
                    <option value="12:00">12:00</option>
                    <option value="13:00">13:00</option>
                    <option value="14:00">14:00</option>
                    <option value="15:00" selected>15:00</option>
                    <option value="16:00">16:00</option>
                    <option value="17:00">17:00</option>
                    <option value="18:00">18:00</option>
                    <option value="19:00">19:00</option>
                    <option value="20:00">20:00</option>
                    <option value="21:00">21:00</option>
                    <option value="22:00">22:00</option>
                    <option value="23:00">23:00</option>
                </select>
                <fieldset>
                    <legend>Valido per:</legend>
                        <div class="row">
                            <input type="checkbox" id="lun" name="lun" checked>
                            <label for="lun">Lunedì</label>
                        </div>
                        <div class="row">
                            <input type="checkbox" id="mar" name="mar" checked>
                            <label for="mar">Martedì</label>
                        </div>
                        <div class="row">
                            <input type="checkbox" id="mer" name="mer" checked>
                            <label for="mer">Mercoledì</label>
                        </div>
                        <div class="row">
                            <input type="checkbox" id="gio" name="gio" checked>
                            <label for="gio">Giovedì</label>
                        </div>
                        <div class="row">
                            <input type="checkbox" id="ven" name="ven" checked>
                            <label for="ven">Venerdì</label>
                        </div>
                        <div class="row">
                            <input type="checkbox" id="sab" name="sab" checked>
                            <label for="sab">Sabato</label>
                        </div>
                        <div class="row">
                            <input type="checkbox" id="dom" name="dom" checked>
                            <label for="dom">Domenica</label>
                        </div>
                </fieldset>
                <input class="submit" type="submit" value="Aggiungi turno">
                <?php if ($error): ?>
                    <p class="error"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
            </form>
        </div>
    </body>
</html>

