<div class="container pb-5">
    
    <?php if(isset($templateParams["messaggio"])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $templateParams["messaggio"]; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if(isset($templateParams["errore"])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $templateParams["errore"]; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Ciao, <?php echo $_SESSION["nome"]; ?>! 👋</h2>
    </div>

    <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="partite-tab" data-bs-toggle="tab" data-bs-target="#partite" type="button">⚽ Gioca Ora</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="dispo-tab" data-bs-toggle="tab" data-bs-target="#dispo" type="button">📅 Dai Disponibilità</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="profilo-tab" data-bs-toggle="tab" data-bs-target="#profilo" type="button">👤 I Miei Impegni</button>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">

        <div class="tab-pane fade show active" id="partite">
            <h4>Partite in Programma</h4>
            <div class="row">
                <?php foreach($templateParams["partite_disponibili"] as $partita): ?>
                    <?php if($partita['stato'] == 'aperta'): ?>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 shadow-sm border-primary">
                            <div class="card-body">
                                <h5 class="card-title text-primary"><?php echo $partita['nome_campo']; ?></h5>
                                <p class="card-text mb-2">
                                    📅 <strong><?php echo date("d/m H:i", strtotime($partita['data_ora'])); ?></strong><br>
                                    📍 Zona: <?php echo $partita['zona']; ?>
                                </p>
                                
                                <div class="progress mb-3" style="height: 20px;">
                                    <?php $percentuale = ($partita['giocatori_attuali'] / 10) * 100; ?>
                                    <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $percentuale; ?>%">
                                        <?php echo $partita['giocatori_attuali']; ?>/10
                                    </div>
                                </div>

                                <?php 
                                    // Verifichiamo se l'utente è già iscritto a QUESTA partita specifica
                                    // Usiamo l'array di ID che abbiamo creato in index.php
                                    $gia_prenotato = in_array($partita['id'], $templateParams["ids_prenotate_utente"]);
                                ?>

                                <?php if($partita['giocatori_attuali'] >= 10): ?>
                                    <button class="btn btn-danger w-100" disabled>SOLD OUT ❌</button>

                                <?php elseif($gia_prenotato): ?>
                                    <button class="btn btn-secondary w-100" disabled>Sei già iscritto ✅</button>

                                <?php else: ?>
                                    <form action="index.php" method="POST">
                                        <input type="hidden" name="azione" value="prenota">
                                        <input type="hidden" name="id_partita" value="<?php echo $partita['id']; ?>">
                                        <button type="submit" class="btn btn-primary w-100">Prenota Posto ⚽</button>
                                    </form>
                                <?php endif; ?>
                                
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                
                <?php if(count($templateParams["partite_disponibili"]) == 0): ?>
                    <div class="alert alert-info">Non ci sono partite in programma al momento.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="tab-pane fade" id="dispo">
            <div class="card p-4 shadow-sm bg-light border-0">
                <h4>Non trovi partite? Dicci quando sei libero!</h4>
                <p>Marcello (l'Admin) creerà una partita basandosi su queste richieste.</p>
                
                <form action="index.php" method="POST">
                    <input type="hidden" name="azione" value="invia_disponibilita">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Giorno</label>
                        <input type="date" name="giorno" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Fascia Oraria</label>
                        <select name="fascia" class="form-select">
                            <option>Pomeriggio (15:00 - 18:00)</option>
                            <option>Sera (19:00 - 21:00)</option>
                            <option>Notte (21:00 - 23:00)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Zona Preferita</label>
                        <select name="zona" class="form-select">
                            <option>Tutte</option>
                            <option>Centro</option>
                            <option>San Donato</option>
                            <option>Saragozza</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning w-100 text-dark fw-bold">Invia Richiesta 📨</button>
                </form>
            </div>
        </div>

        <div class="tab-pane fade" id="profilo">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h5 class="text-success">✅ Partite Confermate</h5>
                    <?php if(count($templateParams["mie_prenotazioni"]) > 0): ?>
                        <ul class="list-group">
    <?php foreach($templateParams["mie_prenotazioni"] as $pren): ?>
        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">
            <div>
                <strong><?php echo date("d/m/Y H:i", strtotime($pren['data_ora'])); ?></strong>
                <br>
                Campo: <?php echo $pren['nome']; ?>
                <br>
                <small><?php echo $pren['indirizzo']; ?></small>
            </div>
            
            <form action="index.php" method="POST" onsubmit="return confirm('Sicuro di voler rinunciare alla partita?');">
                <input type="hidden" name="azione" value="annulla_prenotazione">
                <input type="hidden" name="id_partita" value="<?php echo $pren['id_partita']; ?>">
                <button type="submit" class="btn btn-danger btn-sm">Disdici ❌</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>
                    <?php else: ?>
                        <p class="text-muted">Non hai prenotazioni attive.</p>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <h5 class="text-secondary">⏳ Le tue richieste in attesa</h5>
                    <?php if(count($templateParams["mie_richieste"]) > 0): ?>
                        <ul class="list-group">
                            <?php foreach($templateParams["mie_richieste"] as $rich): ?>
                                <li class="list-group-item">
                                    Giorno: <strong><?php echo $rich['giorno']; ?></strong><br>
                                    Fascia: <?php echo $rich['fascia_oraria']; ?>
                                    <span class="badge bg-warning text-dark float-end">In attesa</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">Non hai inviato richieste di disponibilità.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>