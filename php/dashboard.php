<?php
    require_once "dbconfig.php";

    session_start();

    if (isset($_SESSION['UID'])) {
        if ($_SESSION['Type'] != 1) {
            header("Location: index.php");
            exit;
        }
    }
    else {
        header("Location: login.php");
        exit;
    }

    $owner = $_SESSION['UID'];
    $restaurants = null;
    $lunchShifts = null;
    $dinnerShifts = null;
    try {
        $connString = "mysql:host=".DBHOST.";dbname=".DBNAME;

        $pdo = new PDO($connString, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM restaurants WHERE Owner = ?";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1, $owner);
        $statement->execute();
        $restaurants = $statement->fetchAll(PDO::FETCH_ASSOC);

        $i = 0;
        foreach ($restaurants as $restaurant) {
            $sql1 = "SELECT * FROM shifts WHERE Restaurant = ? AND Type = 0";
            $statement1 = $pdo->prepare($sql1);
            $statement1->bindValue(1, $restaurant['Id']);
            $statement1->execute();
            $lunchShifts[$i] = $statement1->fetch(PDO::FETCH_ASSOC);

            $sql2 = "SELECT * FROM shifts WHERE Restaurant = ? AND Type = 1";
            $statement2 = $pdo->prepare($sql2);
            $statement2->bindValue(1, $restaurant['Id']);
            $statement2->execute();
            $dinnerShifts[$i] = $statement2->fetch(PDO::FETCH_ASSOC);

            $i++;
        }

        $pdo = null;
    }
    catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: ../html/error.html");
        exit;
    }

    include "views/dashboard.php"
?>