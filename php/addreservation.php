<?php
    session_start();
    header("Content-Type: application/json");
    require_once "dbconfig.php";

    if (!isset($_SESSION['RID']) || !isset($_SESSION['Date']) || !isset($_SESSION['Hour']) || 
        !isset($_SESSION['Shift']) || !isset($_SESSION['UID']) || !isset($_SESSION['Clients']) || !isset($_SESSION['MaxCovers'])) {

        echo json_encode(['success' => false, 'error' => 'session_error']);
        exit;
    }

    /* Recupero i dati da $_SESSION */
    $resid = intval($_SESSION['RID']);
    $date = $_SESSION['Date'];
    $hour = $_SESSION['Hour'];
    $shift = ($_SESSION['Shift'] == 'lunch')? 0 : 1;
    $userid = intval($_SESSION['UID']);
    $clients = intval($_SESSION['Clients']);
    $maxCovers = intval($_SESSION['MaxCovers']);

    try {
        $connString = "mysql:host=".DBHOST.";dbname=".DBNAME;

        $pdo = new PDO($connString, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /* Controllo che il turno selezionato non abbia un eccezione */
        $sql1 = "SELECT * FROM exceptions WHERE Restaurant = ? AND ShiftType = ? AND Date = ?";
        $statement1 = $pdo->prepare($sql1);
        $statement1->bindValue(1, $resid);
        $statement1->bindValue(2, $shift);
        $statement1->bindValue(3, $date);
        $statement1->execute();
        $row1 = $statement1->fetch(PDO::FETCH_ASSOC);
        if ($row1) {
            echo json_encode(['success' => false, 'error' => 'exception_day']);
            exit;
        }

        /* Controllo sulla capienza del locale */
        $sql2 = "SELECT SUM(Covers) FROM reservations WHERE Date = ? AND Restaurant = ? AND ShiftType = ?";
        $statement2 = $pdo->prepare($sql2);
        $statement2->bindValue(1, $date);
        $statement2->bindValue(2, $resid);
        $statement2->bindValue(3, $shift);
        $statement2->execute();
        $row2 = $statement2->fetch(PDO::FETCH_ASSOC);
        if ($row2['SUM(Covers)'] + $clients > $maxCovers) {
            echo json_encode(['success' => false, 'error' => 'max_capacity']);
            exit;
        }
        

        $sql = "INSERT INTO reservations (Date, Hour, ShiftType, Restaurant, Client, Covers) VALUES (?, ?, ?, ?, ?, ?)";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1, $date);
        $statement->bindValue(2, $hour);
        $statement->bindValue(3, $shift);
        $statement->bindValue(4, $resid);
        $statement->bindValue(5, $userid);
        $statement->bindValue(6, $clients);
        $statement->execute();

        $pdo = null;

        echo json_encode(['success' => true]);
    }
    catch (PDOException $e) {
        error_log($e->getMessage());
        echo json_encode(['success' => false, 'error' => 'db_error']);
        exit;
    }
?>