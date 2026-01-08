<?php
require_once("config.php");

session_start();

// Se non è loggato, via al login
if(!isset($_SESSION["idutente"])){
    header("Location: login.php");
    exit;
}

// SE È L'ADMIN (Marcello)
// SE È L'ADMIN
if($_SESSION["ruolo"] == "admin"){
    
    // AZIONE 1: Creazione Partita (Create)
    if(isset($_POST["azione"]) && $_POST["azione"] == "crea"){
        $num_giocatori = $_POST["giocatori_iniziali"];
        $risultato = $dbh->insertPartita($_POST["id_campo"], $_POST["data_ora"], $num_giocatori);
        $templateParams["messaggio"] = $risultato ? "Partita creata!" : "Errore creazione.";
    }

    // AZIONE 2: Cancellazione Partita (Delete)
    if(isset($_POST["azione"]) && $_POST["azione"] == "elimina_partita"){
        $dbh->deletePartita($_POST["id_partita"]);
        $templateParams["messaggio"] = "Partita cancellata.";
    }

    // AZIONE 3: Cancellazione Richiesta (Delete)
    if(isset($_POST["azione"]) && $_POST["azione"] == "elimina_richiesta"){
        $dbh->deleteRichiesta($_POST["id_richiesta"]);
        $templateParams["messaggio"] = "Richiesta rimossa.";
    }

    // Caricamento Dati
    $templateParams["campi"] = $dbh->getCampi(); 
    $templateParams["richieste"] = $dbh->getRichiesteUtenti(); 
    $templateParams["partite"] = $dbh->getPartite(); // <--- NUOVO: Passiamo la lista partite
    
    $templateParams["titolo"] = "Dashboard Admin";
    $templateParams["nome"] = "template/admin_home.php";
}
// SE È UN UTENTE NORMALE (Kiro)
// ... parte Admin sopra ...

// SE È UN UTENTE NORMALE (Kiro)
else {
    // AZIONE 1: Invia Disponibilità
    if(isset($_POST["azione"]) && $_POST["azione"] == "invia_disponibilita"){
        $dbh->insertDisponibilita($_SESSION["idutente"], $_POST["giorno"], $_POST["fascia"], $_POST["zona"]);
        $templateParams["messaggio"] = "Richiesta inviata all'Admin! 📨";
    }

    // AZIONE 2: Prenota Partita
    if(isset($_POST["azione"]) && $_POST["azione"] == "prenota"){
        $esito = $dbh->prenotaPartita($_POST["id_partita"], $_SESSION["idutente"]);
        if($esito){
            $templateParams["messaggio"] = "Prenotazione confermata! Prepara gli scarpini 👟";
        } else {
            $templateParams["errore"] = "Errore: Partita piena o sei già iscritto.";
        }
    }

    // ... dopo if($_POST["azione"] == "prenota") ...

    // AZIONE 3: Annulla Prenotazione
    if(isset($_POST["azione"]) && $_POST["azione"] == "annulla_prenotazione"){
        $dbh->cancelPrenotazione($_POST["id_partita"], $_SESSION["idutente"]);
        $templateParams["messaggio"] = "Prenotazione cancellata. Posto liberato! 🗑️";
    }

    // ... poi continua con il caricamento dati ...

    // Carichiamo i dati per Kiro
    $templateParams["partite_disponibili"] = $dbh->getPartite(); // Le partite create da Marcello
    $templateParams["mie_richieste"] = $dbh->getUserRichieste($_SESSION["idutente"]);
    $templateParams["mie_prenotazioni"] = $dbh->getUserPrenotazioni($_SESSION["idutente"]);
$templateParams["ids_prenotate_utente"] = array_column($templateParams["mie_prenotazioni"], "id_partita");
    $templateParams["titolo"] = "Home Giocatore";
    $templateParams["nome"] = "template/home_content.php";
}

require("template/base.php");
?>