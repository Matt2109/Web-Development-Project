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

    $id = intval($_GET['id']);

    try {
        $connString = "mysql:host=".DBHOST.";dbname=".DBNAME;

        $pdo = new PDO($connString, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /* Devo controllare che l'id passato dal get sia valido */
        $sql1 = "SELECT * FROM exceptions INNER JOIN restaurants ON exceptions.Restaurant = restaurants.Id WHERE exceptions.Id = ? AND Owner = ?";
        $statement1 = $pdo->prepare($sql1);
        $statement1->bindValue(1, $id);
        $statement1->bindValue(2, $_SESSION['UID']);
        $statement1->execute();
        $row = $statement1->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            header("Location: ../html/error.html");
            exit;
        }

        $sql = "DELETE FROM exceptions WHERE Id = ?";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1, $id);
        $statement->execute();

        $pdo = null;

        header("Location: dashboard.php");
    }
    catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: ../html/error.html");
        exit;
    }
?>