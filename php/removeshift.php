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
    
    $resid = intval($_GET['resid']);
    $shift = intval($_GET['shift']);

    try {
        $connString = "mysql:host=".DBHOST.";dbname=".DBNAME;

        $pdo = new PDO($connString, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /* Validazione id */
        $sql = "SELECT * FROM restaurants WHERE Id = ? AND Owner = ?";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1, $resid);
        $statement->bindValue(2, $_SESSION['UID']);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            header("Location: ../html/error.html");
            exit;
        }

        $sql1 = "DELETE FROM exceptions WHERE Restaurant = ? AND ShiftType = ?";
        $statement1 = $pdo->prepare($sql1);
        $statement1->bindValue(1, $resid);
        $statement1->bindValue(2, $shift);
        $statement1->execute();

        $sql2 = "DELETE FROM shifts WHERE Restaurant = ? AND Type = ?";
        $statement2 = $pdo->prepare($sql2);
        $statement2->bindValue(1, $resid);
        $statement2->bindValue(2, $shift);
        $statement2->execute();

        $pdo = null;

        header("Location: dashboard.php");
    }
    catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: ../html/error.html");
        exit;
    }
?>