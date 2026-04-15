<?php
    session_start();
    header("Content-Type: application/json");
    require_once "dbconfig.php";

    if (isset($_SESSION['UID'])) {
        if ($_SESSION['Type'] != 1) {
            echo json_encode(['success' => false, 'error' => 'unauthorized_user']);
            exit;
        }
    }
    else {
        echo json_encode(['success' => false, 'error' => 'not_logged']);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);

    $resid = isset($input['resid']) ? intval($input['resid']) : null;
    $date = $input['date'] ?? null;
    $owner = $_SESSION['UID'];

    $dateTime = DateTime::createFromFormat('Y-m-d', $date);
    if (!$dateTime || $dateTime->format('Y-m-d') !== $date) {
        echo json_encode(['success' => false, 'error' => 'bad_date']);
        exit;
    }

    try {
        $connString = "mysql:host=".DBHOST.";dbname=".DBNAME;

        $pdo = new PDO($connString, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /* Controllo se l'id del ristorante appartiene all'utente attualmente loggato */
        $sql1 = "SELECT * FROM restaurants WHERE Id = ? AND Owner = ?";
        $statement1 = $pdo->prepare($sql1);
        $statement1->bindValue(1, $resid);
        $statement1->bindValue(2, $owner);
        $statement1->execute();
        $row = $statement1->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            echo json_encode(['success' => false, 'error' => 'bad_resid']);
            exit;
        }

        $sql2 = "SELECT * FROM shifts WHERE Restaurant = ? AND Type = 0";
        $statement2 = $pdo->prepare($sql2);
        $statement2->bindValue(1, $resid);
        $statement2->execute();
        $row2 = $statement2->fetch(PDO::FETCH_ASSOC);
        $lunchList = null;
        if ($row2) {
            $sql3 = "SELECT Hour, ShiftType, Covers, Name, Surname FROM reservations INNER JOIN users ON reservations.Client = users.Id WHERE Restaurant = ? AND Date = ? AND ShiftType = 0";
            $statement3 = $pdo->prepare($sql3);
            $statement3->bindValue(1, $resid);
            $statement3->bindValue(2, $dateTime->format('Y-m-d'));
            $statement3->execute();
            $lunchList = $statement3->fetchAll(PDO::FETCH_ASSOC);
        }

        $sql4 = "SELECT * FROM shifts WHERE Restaurant = ? AND Type = 1";
        $statement4 = $pdo->prepare($sql4);
        $statement4->bindValue(1, $resid);
        $statement4->execute();
        $row4 = $statement4->fetch(PDO::FETCH_ASSOC);
        $dinnerList = null;
        if ($row4) {
            $sql5 = "SELECT Hour, ShiftType, Covers, Name, Surname FROM reservations INNER JOIN users ON reservations.Client = users.Id WHERE Restaurant = ? AND Date = ? AND ShiftType = 1";
            $statement5 = $pdo->prepare($sql5);
            $statement5->bindValue(1, $resid);
            $statement5->bindValue(2, $dateTime->format('Y-m-d'));
            $statement5->execute();
            $dinnerList = $statement5->fetchAll(PDO::FETCH_ASSOC);
        }

        $pdo = null;

        echo json_encode(['success' => true, 'lunch' => $lunchList, 'dinner' => $dinnerList]);
    }
    catch (PDOException $e) {
        error_log($e->getMessage());
        echo json_encode(['success' => false, 'error' => 'db_error']);
        exit;
    }
?>