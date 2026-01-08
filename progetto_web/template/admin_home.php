<div class="row">
    <div class="col-md-4">
        
        <div class="card shadow p-4 mb-4">
            <h4 class="text-primary">➕ Crea Partita</h4>
            
            <?php if(isset($templateParams["messaggio"])): ?>
                <div class="alert alert-info small"><?php echo $templateParams["messaggio"]; ?></div>
            <?php endif; ?>

            <form action="index.php" method="POST">
                <input type="hidden" name="azione" value="crea">
                
                <div class="mb-2">
                    <label class="form-label fw-bold small">Campo</label>
                    <select name="id_campo" class="form-select form-select-sm">
                        <?php foreach($templateParams["campi"] as $campo): ?>
                            <option value="<?php echo $campo['id']; ?>"><?php echo $campo['nome']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label fw-bold small">Data</label>
                    <input type="datetime-local" name="data_ora" class="form-control form-control-sm" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Giocatori iniziali</label>
                    <input type="number" name="giocatori_iniziali" class="form-control form-control-sm" min="0" max="10" value="0">
                </div>
                <button type="submit" class="btn btn-primary btn-sm w-100">Crea</button>
            </form>
        </div>

        <div class="card shadow p-3">
            <h5 class="text-secondary">📨 Richieste Utenti</h5>
            
            <?php if(count($templateParams["richieste"]) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Chi/Quando</th>
                                <th>Zona</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($templateParams["richieste"] as $req): ?>
                            <tr>
                                <td>
                                    <strong><?php echo $req['nome']; ?></strong><br>
                                    <span class="text-primary fw-bold" style="font-size: 0.9rem;">
                                        📅 <?php echo date("d/m/Y", strtotime($req['giorno'])); ?>
                                    </span><br>
                                    <small class="text-muted"><?php echo $req['fascia_oraria']; ?></small>
                                </td>
                                <td><small><?php echo $req['zona_preferita']; ?></small></td>
                                <td class="text-end">
                                    <form action="index.php" method="POST">
                                        <input type="hidden" name="azione" value="elimina_richiesta">
                                        <input type="hidden" name="id_richiesta" value="<?php echo $req['id']; ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm p-0 px-2" title="Elimina">X</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted small mb-0">Nessuna richiesta in attesa.</p>
            <?php endif; ?>
        </div>
    </div> 
    <div class="col-md-8">
        <div class="card shadow p-4">
            <h4>📋 Partite Programmate</h4>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Data</th>
                            <th>Campo</th>
                            <th>Stato</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($templateParams["partite"] as $partita): ?>
                        <tr>
                            <td>
                                <strong><?php echo date("d/m", strtotime($partita['data_ora'])); ?></strong><br>
                                <small><?php echo date("H:i", strtotime($partita['data_ora'])); ?></small>
                            </td>
                            <td><?php echo $partita['nome_campo']; ?></td>
                            <td>
                                <?php echo $partita['giocatori_attuali']; ?>/10
                                <?php if($partita['giocatori_attuali'] >= 10) echo '<span class="badge bg-danger">Full</span>'; ?>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="modifica_partita.php?id=<?php echo $partita['id']; ?>" class="btn btn-warning btn-sm">✏️</a>
                                    
                                    <form action="index.php" method="POST" onsubmit="return confirm('Sicuro di voler cancellare?');">
                                        <input type="hidden" name="azione" value="elimina_partita">
                                        <input type="hidden" name="id_partita" value="<?php echo $partita['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if(count($templateParams["partite"]) == 0): ?>
                <div class="alert alert-light border mt-3">Non hai ancora creato partite.</div>
            <?php endif; ?>
        </div>
    </div>
    </div>