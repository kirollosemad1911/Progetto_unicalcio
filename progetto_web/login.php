<?php
require_once("config.php"); 

// 1. Avviamo la sessione (Serve per ricordarsi che l'utente è loggato)
session_start(); 

// 2. Controlliamo se l'utente ha cliccato "Entra" (ha inviato il form)
if(isset($_POST["email"]) && isset($_POST["password"])){
    
    // Cerchiamo l'utente nel DB
    $login_result = $dbh->checkLogin($_POST["email"]);

    // Se l'array è vuoto, l'utente non esiste
    if(count($login_result) == 0){
        $templateParams["errore"] = "Errore! Utente non trovato.";
    }
    else {
        // L'utente esiste, controlliamo la password
       
        $db_password = $login_result[0]["password"]; 
        
        // password_verify controlla se la password scritta corrisponde all'hash nel DB
        if(password_verify($_POST["password"], $db_password)){
            
            // PASSWORD CORRETTA! 
            registerLoggedUser($login_result[0]); // Funzione helper (vedi sotto)
        }
        else{
            $templateParams["errore"] = "Errore! Password sbagliata.";
        }
    }
}

// 3. Funzione per salvare i dati in sessione
function registerLoggedUser($userRow){
    $_SESSION["idutente"] = $userRow["id"];
    $_SESSION["email"] = $userRow["email"];
    $_SESSION["nome"] = $userRow["nome"];
    $_SESSION["ruolo"] = $userRow["ruolo"]; // Importante per distinguere Admin da Player
    
    // Rimandiamo l'utente alla Home Page
    header("Location: index.php");
    exit;
}

// 4. Se l'utente è già loggato, lo mandiamo via dal login
if(isset($_SESSION["idutente"])){
    header("Location: index.php");
    exit;
}

// Parametri template
$templateParams["titolo"] = "Login";
$templateParams["nome"] = "template/login_form.php"; 

require("template/base.php");

?>
