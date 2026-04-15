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
    $shift = $input['shift'] ?? null;
    $shift = ($shift == '0')? 0 : 1;
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
        $row1 = $statement1->fetch(PDO::FETCH_ASSOC);
        if (!$row1) {
            echo json_encode(['success' => false, 'error' => 'bad_resid']);
            exit;
        }

        /* Controllo l'esistenza del turno per il ristorante */
        $sql2 = "SELECT * FROM shifts WHERE Restaurant = ? AND Type = ?";
        $statement2 = $pdo->prepare($sql2);
        $statement2->bindValue(1, $resid);
        $statement2->bindValue(2, $shift);
        $statement2->execute();
        $row2 = $statement2->fetch(PDO::FETCH_ASSOC);
        if (!$row2) {
            echo json_encode(['success' => false, 'error' => 'bad_shift']);
            exit;
        }

        /* Controllo che non sia già presenta un'eccezione per la data e turno selezionati */
        $sql3 = "SELECT * FROM exceptions WHERE Restaurant = ? AND ShiftType = ? AND Date = ?";
        $statement3 = $pdo->prepare($sql3);
        $statement3->bindValue(1, $resid);
        $statement3->bindValue(2, $shift);
        $statement3->bindValue(3, $date);
        $statement3->execute();
        $row3 = $statement3->fetch(PDO::FETCH_ASSOC);
        if ($row3) {
            echo json_encode(['success' => false, 'error' => 'repeated_exception']);
            exit;
        }

        $sql = "INSERT INTO exceptions (Restaurant, ShiftType, Date) VALUES (?, ?, ?)";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1, $resid);
        $statement->bindValue(2, $shift);
        $statement->bindValue(3, $date);
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