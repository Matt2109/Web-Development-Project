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

    if (!isset($_SESSION['RID'])) {
        echo json_encode(['success' => false, 'error' => 'unset_RID']);
        exit;
    }

    $resid = intval($_SESSION['RID']);

    try {
        $connString = "mysql:host=".DBHOST.";dbname=".DBNAME;

        $pdo = new PDO($connString, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql1 = "SELECT * FROM restaurants WHERE Id = ?";
        $statement1 = $pdo->prepare($sql1);
        $statement1->bindValue(1, $resid);
        $statement1->execute();
        $row = $statement1->fetch(PDO::FETCH_ASSOC);

        /* Validazione aggiuntiva */
        if (!$row) {
            /* Non esiste un ristorante con quell'id */
            echo json_encode(['success' => false, 'error' => 'bad_resid']);
            $pdo = null;
            exit;
        }

        $sql2 = "SELECT * FROM shifts WHERE Restaurant = ?"; 
        $statement2 = $pdo->prepare($sql2);
        $statement2->bindValue(1, $resid);
        $statement2->execute();
        $result = $statement2->fetchAll(PDO::FETCH_ASSOC);

        $exceptions = null;
        foreach ($result as $res) {
            $sql3 = "SELECT * FROM exceptions WHERE Restaurant = ?";
            $statement3 = $pdo->prepare($sql3);
            $statement3->bindValue(1, $resid);
            $statement3->execute();
            $exceptions = $statement3->fetchAll(PDO::FETCH_ASSOC);
        }

        $pdo = null;

        echo json_encode([
            'success' => true,
            'restaurant' => $row,
            'shifts' => $result,
            'exceptions' => $exceptions
        ]);
    }
    catch (PDOException $e) {
        error_log($e->getMessage());
        echo json_encode(['error' => 'db_error']);
        exit;
    }
?>