<?php
    session_start();

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

    if (!isset($_SESSION['RID'])) {
        header("Location: dashboard.php");
        exit;
    }  
    $resid = $_SESSION['RID'];

    $error = null;
    if (isset($_GET['error'])) {
        switch ($_GET['error']) {
            case 'incorrect_time':
                $error = "Orario non compatibile con il turno selezionato";
                break;
            case 'existing_shift':
                $error = "Hai già aggiunto questo turno";
                break;
            default:
                $error = "Si è verificato un errore";
                break;
        }
    }

    if (isset($_POST['opening']) && isset($_POST['closure'])) {
        require_once "dbconfig.php";

        $week = ['lun', 'mar', 'mer', 'gio', 'ven', 'sab', 'dom'];      
        $opening = $_POST['opening'];
        $closure = $_POST['closure'];
        $type = ($_POST['type'] == "1") ? 1 : 0;
        $days = "xxxxxxx";

        /* Controllo sugli orari */
        $dto = DateTime::createFromFormat('H:i', $opening);
        $dtc = DateTime::createFromFormat('H:i', $closure);
        $minLunchTime = DateTime::createFromFormat('H:i', '11:00');
        $maxLunchTime = DateTime::createFromFormat('H:i', '17:00');
        $maxDinnerTime = DateTime::createFromFormat('H:i', '23:00');
        if (!$dto || !$dtc) {
            header("Location: addshift.php?error=incorrect_time");
            exit;
        }
        if ($type == 0) {
            if ($dto < $minLunchTime || $dtc > $maxLunchTime) {
                header("Location: addshift.php?error=incorrect_time");
                exit;
            }
        } 
        else {
            if ($dto < $maxLunchTime || $dtc > $maxDinnerTime) {
                header("Location: addshift.php?error=incorrect_time");
                exit;
            }
        }

        /* Prelievo checkbox */
        for ($i = 0; $i < sizeof($week); $i++) {
            if (isset($_POST[$week[$i]]))
                $days[$i] = "o";
        }
        
        try {
            $connString = "mysql:host=".DBHOST.";dbname=".DBNAME;

            $pdo = new PDO($connString, DBUSER, DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            /* Devo controllare che non esista già un turno dello stesso tipo */
            $sql = "SELECT * FROM shifts WHERE Restaurant = ? AND Type = ?";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(1, $resid);
            $statement->bindValue(2, $type);
            $statement->execute();
            $res = $statement->fetch(PDO::FETCH_ASSOC);
            if ($res) {
                $pdo = null;
                header("Location: addshift.php?error=existing_shift");
                exit;
            }

            $sql2 = "INSERT INTO shifts (Restaurant, Opening, Closure, Days, Type) VALUES (?, ?, ?, ?, ?)";
            $statement2 = $pdo->prepare($sql2);
            $statement2->bindValue(1, $resid);
            $statement2->bindValue(2, $opening);
            $statement2->bindValue(3, $closure);
            $statement2->bindValue(4, $days);
            $statement2->bindValue(5, $type);
            $statement2->execute();
            
            $pdo = null;

            header("location: dashboard.php");
        }
        catch (PDOException $e) {
            error_log($e->getMessage());
            header("Location: ../html/error.html");
            exit;
        }
    }

    include "views/addshift.php";
?>