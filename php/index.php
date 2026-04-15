<?php
    require_once "dbconfig.php";

    session_start();

    /* Se un gestore ha effettuato l'accesso viene rimandato alla dashboard */
    if (isset($_SESSION['UID']) && isset($_SESSION['Type'])) {
        if ($_SESSION['Type'] == 1) {
            header("Location: dashboard.php");
            exit;
        }
    }

    $restaurants = null;
    $reservations = null;
    try {
        $connString = "mysql:host=".DBHOST.";dbname=".DBNAME;

        $pdo = new PDO($connString, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /* Recupero le informazioni sui ristoranti presenti nel database */
        $sql1 = "SELECT * FROM restaurants";
        $result1 = $pdo->query($sql1);
        $restaurants = $result1->fetchAll(PDO::FETCH_ASSOC);

        /* Nel caso in cui sia stato effettuato l'accesso, recupero le prenotazioni */
        if (isset($_SESSION['UID'])) {
            $userid = $_SESSION['UID'];
            $sql2 = "SELECT reservations.Id, Name, Address, City, Date, Hour, Covers FROM reservations INNER JOIN restaurants ON reservations.Restaurant = restaurants.Id WHERE Client = '$userid' AND Date >= CURRENT_DATE";
            $result2 = $pdo->query($sql2);
            $reservations = $result2->fetchAll(PDO::FETCH_ASSOC);
        }

        $pdo = null;
    }
    catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: ../html/error.html");
        exit;
    }

    include "views/home.php";
?>