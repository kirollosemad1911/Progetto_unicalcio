<?php
require_once("config.php"); 

if(isset($_POST["email"])){
    // 1. Recuperiamo i dati dal form
    $nome = $_POST["nome"];
    $cognome = $_POST["cognome"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $conf_password = $_POST["confirm_password"];

    // 2. Controllo: Le password coincidono?
    if($password != $conf_password){
        $templateParams["errore"] = "Le due password non coincidono!";
    }
    else{
        // 3. Controllo: L'email esiste già?
        // Riusiamo la funzione checkLogin per vedere se trova qualcuno
        $utenti_esistenti = $dbh->checkLogin($email);
        
        if(count($utenti_esistenti) > 0){
            $templateParams["errore"] = "Esiste già un account con questa email!";
        }
        else{
            // 4. TUTTO OK! REGISTRIAMO L'UTENTE
            
            // Creiamo l'hash della password (FONDAMENTALE PER L'ESAME)
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Salviamo nel DB
            $registrazione_ok = $dbh->registerUser($nome, $cognome, $email, $password_hash);
            
            if($registrazione_ok){
                // Reindirizziamo al login con un parametro (opzionale, per bellezza)
                header("Location: login.php");
                exit;
            } else {
                $templateParams["errore"] = "Errore generico nel database. Riprova.";
            }
        }
    }
}

$templateParams["titolo"] = "Registrazione";
$templateParams["nome"] = "template/registrazione_form.php"; 

require("template/base.php");
?>