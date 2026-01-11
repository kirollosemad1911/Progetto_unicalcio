<?php
class DatabaseHelper{
    private $db;

    // 1. Costruttore: si connette al database
    public function __construct($servername, $username, $password, $dbname, $port){
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if($this->db->connect_error){
            die("Connessione fallita al db: " . $this->db->connect_error);
        }
    }

    // 2. Funzione generica per INSERT, UPDATE, DELETE 
    public function execQuery($query){
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->get_result(); 
    }

    // 3. Funzione per le SELECT (restituisce i dati come array)
    public function getResult($query){
        $res = $this->db->query($query);
        if (!$res) {
             die("Errore nella query: " . $this->db->error);
        }
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    // 4. Funzione specifica per il LOGIN (sicura contro SQL Injection)
    public function checkLogin($email){
        $query = "SELECT * FROM utenti WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Funzione per REGISTRARE un nuovo utente
    public function registerUser($nome, $cognome, $email, $password){
        // 'player' è il ruolo di default per chi si iscrive dal sito
        $query = "INSERT INTO utenti (nome, cognome, email, password, ruolo) VALUES (?, ?, ?, ?, 'player')";
        
        $stmt = $this->db->prepare($query);
        // 'ssss' significa che passiamo 4 stringhe
        $stmt->bind_param('ssss', $nome, $cognome, $email, $password);
        
        // Esegue l'inserimento e restituisce TRUE se va a buon fine
        return $stmt->execute();
    }
    // 1. Prende tutti i campi (per riempire il menu a tendina dell'Admin)
    public function getCampi(){
        return $this->getResult("SELECT * FROM campi");
    }

    // 1. Funzione per creare la partita
    public function insertPartita($id_campo, $data_ora, $giocatori_iniziali){
        // Inseriamo direttamente i giocatori iniziali che decide l'Admin
        $query = "INSERT INTO partite (id_campo, data_ora, giocatori_max, giocatori_attuali, stato) 
                  VALUES (?, ?, 10, ?, 'aperta')";
        
        $stmt = $this->db->prepare($query);
        // 'isi' = Integer (id_campo), String (data), Integer (giocatori)
        $stmt->bind_param('isi', $id_campo, $data_ora, $giocatori_iniziali);
        return $stmt->execute();
    }

    // 2. funzione: serve all'Admin per vedere chi ha fatto richiesta
    public function getRichiesteUtenti(){
        // Prende le disponibilità unendo i nomi degli utenti
        $query = "SELECT disponibilita.*, utenti.nome, utenti.cognome 
                  FROM disponibilita 
                  JOIN utenti ON disponibilita.id_utente = utenti.id 
                  WHERE disponibilita.stato = 'attesa'";
        return $this->getResult($query);
    }
    // 1. Legge tutte le partite future (unisce i dati col nome del campo)
    public function getPartite(){
        $query = "SELECT partite.*, campi.nome as nome_campo, campi.zona 
                  FROM partite 
                  JOIN campi ON partite.id_campo = campi.id 
                  ORDER BY partite.data_ora ASC";
        return $this->getResult($query);
    }

    // 2. Cancella una partita 
    public function deletePartita($id){
        $query = "DELETE FROM partite WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // 3. Cancella una richiesta di disponibilità 
    public function deleteRichiesta($id){
        $query = "DELETE FROM disponibilita WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // 4. Prende UNA partita specifica (servirà per la modifica)
    public function getPartitaById($id){
        $query = "SELECT * FROM partite WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // 5. Modifica una partita 
    public function updatePartita($id, $id_campo, $data_ora, $giocatori_iniziali){
        $query = "UPDATE partite SET id_campo=?, data_ora=?, giocatori_attuali=? WHERE id=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('isii', $id_campo, $data_ora, $giocatori_iniziali, $id);
        return $stmt->execute();
    }
    // 1. Kiro invia la sua disponibilità
    public function insertDisponibilita($id_utente, $data, $fascia, $zona){
        $query = "INSERT INTO disponibilita (id_utente, giorno, fascia_oraria, zona_preferita, stato) 
                  VALUES (?, ?, ?, ?, 'attesa')";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('isss', $id_utente, $data, $fascia, $zona);
        return $stmt->execute();
    }

    // 2. Kiro vede le sue richieste inviate
    public function getUserRichieste($id_utente){
        $query = "SELECT * FROM disponibilita WHERE id_utente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id_utente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // 3. Kiro vede le partite a cui si è iscritto
    public function getUserPrenotazioni($id_utente){
        
        $query = "SELECT prenotazioni.id_partita, partite.data_ora, campi.nome, campi.indirizzo 
                  FROM prenotazioni 
                  JOIN partite ON prenotazioni.id_partita = partite.id 
                  JOIN campi ON partite.id_campo = campi.id 
                  WHERE prenotazioni.id_utente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id_utente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // 4. PRENOTAZIONE (La parte più importante!)
    public function prenotaPartita($id_partita, $id_utente){
        // Prima controlliamo se c'è posto!
        // Aggiorniamo il contatore SOLO se è < 10
        $query_update = "UPDATE partite SET giocatori_attuali = giocatori_attuali + 1 
                         WHERE id = ? AND giocatori_attuali < 10";
        $stmt = $this->db->prepare($query_update);
        $stmt->bind_param('i', $id_partita);
        $stmt->execute();

        // Se l'aggiornamento ha funzionato (c'era posto), inseriamo la prenotazione
        if($stmt->affected_rows > 0){
            $query_ins = "INSERT INTO prenotazioni (id_partita, id_utente) VALUES (?, ?)";
            $stmt_ins = $this->db->prepare($query_ins);
            $stmt_ins->bind_param('ii', $id_partita, $id_utente);
            return $stmt_ins->execute();
        } else {
            return false; // Partita piena!
        }
    }
    // Funzione per ANNULLARE la prenotazione (Utente)
    public function cancelPrenotazione($id_partita, $id_utente){
        // 1. Cancelliamo la prenotazione
        $query_del = "DELETE FROM prenotazioni WHERE id_partita = ? AND id_utente = ?";
        $stmt = $this->db->prepare($query_del);
        $stmt->bind_param('ii', $id_partita, $id_utente);
        $stmt->execute();

        // 2. Se abbiamo cancellato davvero qualcosa, liberiamo il posto nella partita
        if($stmt->affected_rows > 0){
            $query_update = "UPDATE partite SET giocatori_attuali = giocatori_attuali - 1 WHERE id = ?";
            $stmt_up = $this->db->prepare($query_update);
            $stmt_up->bind_param('i', $id_partita);
            return $stmt_up->execute();
        }
        return false;
    }
}


?>
