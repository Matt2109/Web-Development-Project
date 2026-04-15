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

    $error = null;
    if (isset($_GET['error'])) {
        switch ($_GET['error']) {
            case 'missing_type':
                $error = "Includi anche la tipologia per aggiungere il ristorante";
                break;
            case 'incorrect_format':
                $error = "L'input non rispetta il formato richiesto";
                break;
            case 'existing_resaturant':
                $error = "Possiedi un altro locale con lo stesso nome";
                break;
            default:
                $error = "Si è verificato un errore";
                break;
        }
    }

    if (isset($_POST['name']) && isset($_POST['covers']) && isset($_POST['city']) && isset($_POST['address'])) {
        require_once "dbconfig.php";

        if (!isset($_POST['type'])) {
            header("Location: addrestaurant.php?error=missing_type");
            exit;
        }
        
        $name = $_POST['name'];
        $covers = $_POST['covers'];
        $city = $_POST['city'];
        $address = $_POST['address'];
        $type = intval($_POST['type']);
        $owner = $_SESSION['UID'];

        $regexname = "/^[A-Z][a-z]+(?:\s(?:[A-Z][a-z]+|[A-Z]'[A-Z][a-z]+))*$/";
        $regexaddress = "/^[A-Z][a-z]+(?:\s(?:[A-Z][a-z]+|[A-Z]'[A-Z][a-z]+))*\s[1-9][0-9]{0,3}$/";

        if (!preg_match($regexname, $name) || !preg_match($regexname, $city) || !preg_match($regexaddress, $address)) {
            header("location: addrestaurant.php?error=incorrect_format");
            exit;
        }

        try {
            $connString = "mysql:host=".DBHOST.";dbname=".DBNAME;

            $pdo = new PDO($connString, DBUSER, DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            /* Controllo se il il proprietario ha già un locale con lo stesso nome */
            $sql1 = "SELECT Name, Owner FROM restaurants WHERE Owner = ?";
            $statement1 = $pdo->prepare($sql1);
            $statement1->bindValue(1, $owner);
            $statement1->execute();
            while ($row = $statement1->fetch(PDO::FETCH_ASSOC)) {
                if ($row['Name'] == $name) {
                    header("Location: addrestaurant.php?existing_resaturant");
                    exit;
                }
            }
            
            $sql2 = "INSERT INTO restaurants (Name, MaxCovers, City, Address, Type, Owner) VALUES (?, ?, ?, ?, ?, ?)";
            $statement2 = $pdo->prepare($sql2);
            $statement2->bindValue(1, $name);
            $statement2->bindValue(2, $covers);
            $statement2->bindValue(3, $city);
            $statement2->bindValue(4, $address);
            $statement2->bindValue(5, $type);
            $statement2->bindValue(6, $owner);
            $statement2->execute();
            
            $pdo = null;

            header("Location: dashboard.php");
        }
        catch (PDOException $e) {
            error_log($e->getMessage());
            header("Location: ../html/error.html");
            exit;
        }
    }

    include "views/addrestaurant.php";
?>