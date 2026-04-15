<?php   
    $mess = null;
    if (isset($_GET['error'])) {
        switch ($_GET['error']) {
            case 'incorrect_format':
                $mess = "L'input non rispetta le espressioni regolari";
                break;
            case 'pswd_mismatch':
                $mess = "Le due password non coincidono";
                break;
            case 'existing_username':
                $mess = "Username già esistente, riprova";
                break;
            default:
                $mess = "Si è verificato un errore";
        }
    }
    
    if (isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['username']) && isset($_POST['pswd']) && isset($_POST['pswdconfirm'])) {
        
        require_once "dbconfig.php";

        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $username = $_POST['username'];
        $pswd = $_POST['pswd'];
        $pswdconfirm = $_POST['pswdconfirm'];
        $type = ($_POST['type'] == '1') ? 1 : 0;

        $regexname = "/^[A-Z][a-z]+(?:\s(?:[A-Z][a-z]+|[A-Z]'[A-Z][a-z]+))*$/";
        $regexusr = "/^[A-Za-z0-9]{4,20}$/";
        $regexpswd = "/^[\S]{8,32}$/";

        if (!preg_match($regexname, $fname) || !preg_match($regexname, $lname) || !preg_match($regexusr, $username) || !preg_match($regexpswd, $pswd)) {
            header("location: register.php?error=incorrect_format");
            exit;
        }

        if ($pswd !== $pswdconfirm) {
            header("location: register.php?error=pswd_mismatch");
            exit;
        }

        try {
            $connString = "mysql:host=".DBHOST.";dbname=".DBNAME;

            $pdo = new PDO($connString, DBUSER, DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            /* Controllo se l'username scelto è già stato utilizzato */
            $sql1 = "SELECT * FROM users WHERE Username = ?";
            $statement1 = $pdo->prepare($sql1);
            $statement1->bindValue(1, $username);
            $statement1->execute();
            $statement1->fetch();
            if ($statement1->rowCount() != 0) {
                $pdo = null;
                header("location: register.php?error=existing_username");
                exit;
            }
            
            /* Salvo le informazioni nella tabella users */
            $pswd = password_hash($pswd, PASSWORD_BCRYPT);
            $sql2 = "INSERT INTO users (Username, Password, Name, Surname, Type) VALUES (?, ?, ?, ?, ?)";
            $statement2 = $pdo->prepare($sql2);
            $statement2->bindValue(1, $username);
            $statement2->bindValue(2, $pswd);
            $statement2->bindValue(3, $fname);
            $statement2->bindValue(4, $lname);
            $statement2->bindValue(5, $type);
            $statement2->execute();
            
            $pdo = null;

            header("location: login.php");
            exit;
        }
        catch (PDOException $e) {
            error_log($e->getMessage());
            header("Location: ../html/error.html");
            exit;
        }
    }

    include "views/register.php";
?>