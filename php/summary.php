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
    $shift = $input['shift'] ?? null;
    $hour = $input['hour'] ?? null;
    $clients = isset($input['clients']) ? intval($input['clients']) : null;

    if ($shift != 'lunch' && $shift != 'dinner') {
        echo json_encode(['success' => false, 'error' => 'bad_input']);
        exit;
    }

    /* Validazione data */
    $dateTime = new DateTime($date.$hour);
    $currentDate = new DateTime();
    if (!$dateTime || $dateTime < $currentDate) {
        echo json_encode(['success' => false, 'error' => 'bad_date']);
        exit;
    }

    try {
        $connString = "mysql:host=".DBHOST.";dbname=".DBNAME;

        $pdo = new PDO($connString, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /* Validazione id ristorante */
        $sql = "SELECT * FROM Restaurants WHERE Id = ?";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1, $resid);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            echo json_encode(['success' => false, 'error' => 'bad_resid']);
            exit;
        }

        $pdo = null;

        $_SESSION['Restaurant'] = $row['Name'];
        $_SESSION['Address'] = $row['Address'];
        $_SESSION['City'] = $row['City'];
        $_SESSION['Date'] = $dateTime->format('Y-m-d');
        $_SESSION['Shift'] = $shift;
        $_SESSION['Hour'] = $hour;
        $_SESSION['Clients'] = $clients;
        $_SESSION['MaxCovers'] = $row['MaxCovers'];

        echo json_encode(['success' => true]);
    }
    catch (PDOException $e) {
        error_log($e->getMessage());
        echo json_encode(['success' => false, 'error' => 'db_error']);
        exit;
    }
?>