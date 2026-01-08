<?php
require_once("config.php");
session_start();

// Controllo sicurezza: solo Admin
if(!isset($_SESSION["ruolo"]) || $_SESSION["ruolo"] != "admin"){
    header("Location: login.php");
    exit;
}

// Se ho inviato il form di modifica (Salva)
if(isset($_POST["id_partita"])){
    $dbh->updatePartita($_POST["id_partita"], $_POST["id_campo"], $_POST["data_ora"], $_POST["giocatori_attuali"]);
    header("Location: index.php"); // Torna alla home
    exit;
}

// Se arrivo qui, devo mostrare il form. Recupero i dati della partita
$id_partita = $_GET["id"];
$partita_info = $dbh->getPartitaById($id_partita)[0]; // Prendo la prima riga

$templateParams["campi"] = $dbh->getCampi(); // Serve per la tendina
$templateParams["partita"] = $partita_info;
$templateParams["titolo"] = "Modifica Partita";
$templateParams["nome"] = "template/modifica_partita_form.php";

require("template/base.php");
?>