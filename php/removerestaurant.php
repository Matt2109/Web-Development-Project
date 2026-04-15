<?php
    session_start();
    require_once "dbconfig.php";

    if (!isset($_SESSION['UID'])) {
        header("Location: login.php");
        exit;
    }

    if (isset($_SESSION['Type'])) {
        if ($_SESSION['Type'] != 1) {
            header("Location: logout.php");
            exit;
        }
    }

    $uid = $_SESSION['UID'];
    $resid = intval($_GET['resid']);

    try {
        $connString = "mysql:host=".DBHOST.";dbname=".DBNAME;

        $pdo = new PDO($connString, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /* Validazione id */
        $sql = "SELECT * FROM restaurants WHERE Id = ? AND Owner = ?";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1, $resid);
        $statement->bindValue(2, $uid);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            header("Location: ../html/error.html");
            exit;
        }

        /* Rimozione eccezioni */
        $sql2 = "DELETE FROM exceptions WHERE Restaurant = ?";
        $statement2 = $pdo->prepare($sql2);
        $statement2->bindValue(1, $resid);
        $statement2->execute();

        /* Rimozioni turni */
        $sql3 = "DELETE FROM shifts WHERE Restaurant = ?";
        $statement3 = $pdo->prepare($sql3);
        $statement3->bindValue(1, $resid);
        $statement3->execute();

        /* Rimozioni prenotazioni */
        $sql4 = "DELETE FROM reservations WHERE Restaurant = ?";
        $statement4 = $pdo->prepare($sql4);
        $statement4->bindValue(1, $resid);
        $statement4->execute();

        /* Rimozione ristorante */
        $sql5 = "DELETE FROM restaurants WHERE Id = ?";
        $statement5 = $pdo->prepare($sql5);
        $statement5->bindValue(1, $resid);
        $statement5->execute();

        $pdo = null;

        header("Location: dashboard.php");
    }
    catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: ../html/error.html");
        exit;
    }
?>