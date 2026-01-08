-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Gen 08, 2026 alle 16:06
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unicalcio_db`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `campi`
--

CREATE TABLE `campi` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `zona` varchar(50) NOT NULL,
  `indirizzo` varchar(255) NOT NULL,
  `latitudine` decimal(10,8) DEFAULT NULL,
  `longitudine` decimal(11,8) DEFAULT NULL,
  `immagine` varchar(255) DEFAULT 'default_field.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `campi`
--

INSERT INTO `campi` (`id`, `nome`, `zona`, `indirizzo`, `latitudine`, `longitudine`, `immagine`) VALUES
(1, 'Cus Bologna', 'San Donato', 'Via del Terrapieno 27', 44.50550000, 11.38550000, 'default_field.jpg'),
(2, 'Campo Barbacan', 'Centro', 'Viale Goethe', 44.48900000, 11.33000000, 'default_field.jpg');

-- --------------------------------------------------------

--
-- Struttura della tabella `disponibilita`
--

CREATE TABLE `disponibilita` (
  `id` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `giorno` date NOT NULL,
  `fascia_oraria` varchar(50) NOT NULL,
  `zona_preferita` varchar(50) DEFAULT NULL,
  `stato` enum('attesa','gestita') NOT NULL DEFAULT 'attesa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `partite`
--

CREATE TABLE `partite` (
  `id` int(11) NOT NULL,
  `id_campo` int(11) NOT NULL,
  `data_ora` datetime NOT NULL,
  `giocatori_max` int(11) NOT NULL DEFAULT 10,
  `giocatori_attuali` int(11) NOT NULL DEFAULT 0,
  `stato` enum('aperta','chiusa','annullata') NOT NULL DEFAULT 'aperta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `partite`
--

INSERT INTO `partite` (`id`, `id_campo`, `data_ora`, `giocatori_max`, `giocatori_attuali`, `stato`) VALUES
(4, 2, '2025-12-27 22:44:00', 10, 6, 'aperta');

-- --------------------------------------------------------

--
-- Struttura della tabella `prenotazioni`
--

CREATE TABLE `prenotazioni` (
  `id` int(11) NOT NULL,
  `id_partita` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `data_prenotazione` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `prenotazioni`
--

INSERT INTO `prenotazioni` (`id`, `id_partita`, `id_utente`, `data_prenotazione`) VALUES
(8, 4, 4, '2025-12-15 18:03:41'),
(10, 4, 5, '2025-12-15 18:04:25');

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ruolo` enum('player','admin') NOT NULL DEFAULT 'player'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `nome`, `cognome`, `email`, `password`, `ruolo`) VALUES
(1, 'Marcello', 'Rossi', 'admin@unibo.it', '$2y$10$5/scMPjyOboILYQYRLpaZOlxDMaL/b.pvYaaRCtdh4Mcwp4SX4N9K', 'admin'),
(2, 'Kiro', 'Verdi', 'kiro@studio.unibo.it', '$2y$10$ING2s50z15Lh6TMiLdbjlOlEgQgkuUOeEC1aYrK4ZSJbAO48ELcLK', 'player'),
(3, 'Luca', 'Bianchi', 'luca@studio.unibo.it', 'luca2003', 'player'),
(4, 'francesca', 'de pascalis', 'francesca@unibo.it', '$2y$10$Cx.k8GSczofpCVLxZcPV0ugZAR.RxiwrXWcwgjIK3uoq1FZXb9SfK', 'player'),
(5, 'peter', 'essam', 'peter@unibo.it', '$2y$10$hHAuopJL1iTNY9WJAOxZTObf4bFm1NXYVNkE7E091L24MVNnk0J4.', 'player');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `campi`
--
ALTER TABLE `campi`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `disponibilita`
--
ALTER TABLE `disponibilita`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utente` (`id_utente`);

--
-- Indici per le tabelle `partite`
--
ALTER TABLE `partite`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_campo` (`id_campo`);

--
-- Indici per le tabelle `prenotazioni`
--
ALTER TABLE `prenotazioni`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_doppie` (`id_partita`,`id_utente`),
  ADD KEY `id_utente` (`id_utente`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `campi`
--
ALTER TABLE `campi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `disponibilita`
--
ALTER TABLE `disponibilita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `partite`
--
ALTER TABLE `partite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `prenotazioni`
--
ALTER TABLE `prenotazioni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `disponibilita`
--
ALTER TABLE `disponibilita`
  ADD CONSTRAINT `disponibilita_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `partite`
--
ALTER TABLE `partite`
  ADD CONSTRAINT `partite_ibfk_1` FOREIGN KEY (`id_campo`) REFERENCES `campi` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `prenotazioni`
--
ALTER TABLE `prenotazioni`
  ADD CONSTRAINT `prenotazioni_ibfk_1` FOREIGN KEY (`id_partita`) REFERENCES `partite` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prenotazioni_ibfk_2` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
