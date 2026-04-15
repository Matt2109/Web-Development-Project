<?php
    $mess = null;

    if (isset($_GET['error'])) {
        $mess = "Credenziali errate, riprova";
    }

    if (isset($_POST['username']) && isset($_POST['pswd'])) {

        require_once "dbconfig.php";
        
        $username = $_POST['username'];
        $pswd = $_POST['pswd'];

        try {
            $connString = "mysql:host=".DBHOST.";dbname=".DBNAME;

            $pdo = new PDO($connString, DBUSER, DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            /* Controllo se esiste il record che ha come username quello ricevuto dal frontend */
            $sql = "SELECT * FROM users WHERE Username = ?";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(1, $username);
            $statement->execute();
            $row = $statement->fetch(PDO::FETCH_ASSOC);

            $pdo = null;

            if (!$row) {
                header("location: login.php?error=wrong_credentials");
                exit;
            }

            $type = $row['Type'];
            $hash = $row['Password'];
            if (password_verify($pswd, $hash)) {
                /* Setto i parametri di sessione */
                session_start();
                $_SESSION['UID'] = $row['Id'];
                $_SESSION["Name"] = $row['Name'];
                $_SESSION["Surname"] = $row['Surname'];
                $_SESSION["Username"] = $row['Username'];
                $_SESSION["Type"] = $row['Type'];

                switch ($type) {
                    case 0:
                        header("location: index.php");
                        break;
                    case 1:
                        header("location: dashboard.php");
                        break;
                }
                exit;
            } 
            else {
                header("location: login.php?error=wrong_credentials");
                exit;
            }
        }
        catch (PDOException $e) {
            error_log($e->getMessage());
            header("Location: ../html/error.html");
            exit;
        }
    }

    include "views/login.php";
?>