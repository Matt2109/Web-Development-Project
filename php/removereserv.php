<?php
    header("Content-Type: application/json");
    require_once "dbconfig.php";
    session_start();

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

    /* Questo è l'id della prenotazione ricevuto dal frontend, devo verificare sia valido */
    $id = isset($input['id']) ? intval($input['id']) : null;
    $uid = $_SESSION['UID'];

    try {
        $connString = "mysql:host=".DBHOST.";dbname=".DBNAME;

        $pdo = new PDO($connString, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /* Verifico sia presente una prenotazione con quell'id e che appartenga all'utente attualmente loggato */
        $sql1 = "SELECT * FROM reservations WHERE Id = ? AND Client = ?";
        $statement1 = $pdo->prepare($sql1);
        $statement1->bindValue(1, $id);
        $statement1->bindValue(2, $uid);
        $statement1->execute();
        $row = $statement1->fetch(PDO::FETCH_ASSOC);
        
        /* Se non trovo corrispondenze nel database comunico al frontend l'errore */
        if (!$row) {
            echo json_encode(['success' => false, 'error' => 'bad_id']);
            $pdo = null;
            exit;
        }

        $sql2 = "DELETE FROM reservations WHERE Id = ?";
        $statement2 = $pdo->prepare($sql2);
        $statement2->bindValue(1, $id);
        $statement2->execute();

        $pdo = null;

        echo json_encode(['success' => true]);
    }
    catch (PDOException $e) {
        error_log($e->getMessage());
        echo json_encode(['success' => false, 'error' => 'db_error']);
        exit;
    }
?>