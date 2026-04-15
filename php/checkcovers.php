<?php
    session_start();
    header("Content-Type: application/json");
    require_once "dbconfig.php";

    if (isset($_SESSION['UID'])) {
        if ($_SESSION['Type'] != 0) {
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
    $day = isset($input['day']) ? intval($input['day']) : null; 

    if ($day < 0 || $day > 6) {
        echo json_encode(['success' => false, 'error' => 'bad_input']);
        exit;
    }

    $dateTime = DateTime::createFromFormat('Y-m-d', $date);
    if (!$dateTime) {
        echo json_encode(['success' => false, 'error' => 'bad_date']);
        exit;
    }

    try {
        $connString = "mysql:host=".DBHOST.";dbname=".DBNAME;

        $pdo = new PDO($connString, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $slotSums = [];

        /* Validazione id ristorante */
        $sql1 = "SELECT * FROM Restaurants WHERE Id = ?";
        $statement1 = $pdo->prepare($sql1);
        $statement1->bindValue(1, $resid);
        $statement1->execute();
        $row1 = $statement1->fetch(PDO::FETCH_ASSOC);
        if (!$row1) {
            echo json_encode(['success' => false, 'error' => 'bad_resid']);
            exit;
        }
    
        $sql2 = "SELECT * FROM shifts WHERE Restaurant = ? AND Type = '0'"; 
        $statement2 = $pdo->prepare($sql2);
        $statement2->bindValue(1, $resid);
        $statement2->execute();

        $sql3 = "SELECT * FROM exceptions WHERE Restaurant = ? AND Date = ? AND ShiftType = '0'"; 
        $statement3 = $pdo->prepare($sql3);
        $statement3->bindValue(1, $resid);
        $statement3->bindValue(2, $date);
        $statement3->execute();
        $row3 = $statement3->fetch(PDO::FETCH_ASSOC);
        
        /* Se il ristorante ha un turno di tipo 0 continuo */
        if($row2 = $statement2->fetch(PDO::FETCH_ASSOC)) {
            /* Se è presente un'eccezione per il giorno selezionato non continuo con il calcolo */
            if (!$row3) {
                $days = $row2['Days'];
                /* Tengo conto dei giorni della settimana validi */
                if ($days[$day] == "o") {
                    $sql4 = "SELECT SUM(Covers) FROM reservations WHERE Date = ? AND Restaurant = ? AND ShiftType = '0'";
                    $statement4 = $pdo->prepare($sql4);
                    $statement4->bindValue(1, $date);
                    $statement4->bindValue(2, $resid);
                    $statement4->execute();
                    $row4 = $statement4->fetch(PDO::FETCH_ASSOC);
                    $slotSums['Lunch'] = $row4['SUM(Covers)'];
                }
            }
        } 

        $sql5 = "SELECT * FROM shifts WHERE Restaurant = ? AND Type = '1'"; 
        $statement5 = $pdo->prepare($sql5);
        $statement5->bindValue(1, $resid);
        $statement5->execute();

        $sql6 = "SELECT * FROM exceptions WHERE Restaurant = ? AND Date = ? AND ShiftType = '1'"; 
        $statement6 = $pdo->prepare($sql6);
        $statement6->bindValue(1, $resid);
        $statement6->bindValue(2, $date);
        $statement6->execute();
        $row6 = $statement6->fetch(PDO::FETCH_ASSOC);

        /* Se il ristorante ha un turno di tipo 1 continuo */
        if($row5 = $statement5->fetch(PDO::FETCH_ASSOC)) {
            /* Se è presente un'eccezione per il giorno selezionato non continuo con il calcolo */
            if (!$row6) {
                $days = $row5['Days'];
                /* Tengo conto dei giorni della settimana validi */
                if ($days[$day] == "o") {
                    $sql7 = "SELECT SUM(Covers) FROM reservations WHERE Date = ? AND Restaurant = ? AND ShiftType = '1'";
                    $statement7 = $pdo->prepare($sql7);
                    $statement7->bindValue(1, $date);
                    $statement7->bindValue(2, $resid);
                    $statement7->execute();
                    $row7 = $statement7->fetch(PDO::FETCH_ASSOC);
                    $slotSums['Dinner'] = $row7['SUM(Covers)'];
                }
            }
        } 

        $pdo = null;

        echo json_encode(['success' => true, 'data' => $slotSums]);
    }
    catch (PDOException $e) {
        error_log($e->getMessage());
        echo json_encode(['success' => false, 'error' => 'db_error']);
        exit;
    }
?>