<?php
// 1. Recuperiamo la sessione esistente
session_start();

// 2. Distruggiamo la sessione (Cancelliamo tutte le variabili $_SESSION)
session_destroy();

// 3. Rimandiamo l'utente alla pagina di login
header("Location: login.php");
exit;
?>