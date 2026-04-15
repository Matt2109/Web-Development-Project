<?php
    session_start();
    header("Content-Type: application/json");
    require_once "dbconfig.php";

    if (!isset($_SESSION['UID']) || !isset($_SESSION['Type'])) {
        echo json_encode(['success' => false, 'error' => 'not_logged']);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);

    $resid = isset($input['resid']) ? intval($input['resid']) : null;

    try {
        $connString = "mysql:host=".DBHOST.";dbname=".DBNAME;

        $pdo = new PDO($connString, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /* Devo fare un controllo aggiuntivo in caso di chiamata da dashboard.js */
        $row = null;
        if ($_SESSION['Type'] == 0) {
            $sql = "SELECT * FROM restaurants WHERE Id = ?";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(1, $resid);
            $statement->execute();
            $row = $statement->fetch(PDO::FETCH_ASSOC);
        }
        else {
            $sql = "SELECT * FROM restaurants WHERE Id = ? AND Owner = ?";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(1, $resid);
            $statement->bindValue(2, $_SESSION['UID']);
            $statement->execute();
            $row = $statement->fetch(PDO::FETCH_ASSOC);
        }
        
        $pdo = null;

        /* Se non viene trovato un ristorante con quell'id */
        if (!$row) {
            echo json_encode(['success' => false, 'error' => 'bad_resid']);
            exit;
        }

        $_SESSION['RID'] = $resid;

        echo json_encode(['success' => true]);
    }
    catch (PDOException $e) {
        error_log($e->getMessage());
        echo json_encode(['success' => false, 'error' => 'db_error']);
        exit;
    }
?>