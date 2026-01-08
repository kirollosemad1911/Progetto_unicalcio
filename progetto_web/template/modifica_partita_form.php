<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow p-4">
            <h3>✏️ Modifica Partita</h3>
            
            <form action="modifica_partita.php" method="POST">
                <input type="hidden" name="id_partita" value="<?php echo $templateParams["partita"]["id"]; ?>">
                
                <div class="mb-3">
                    <label class="form-label">Campo</label>
                    <select name="id_campo" class="form-select">
                        <?php foreach($templateParams["campi"] as $campo): ?>
                            <?php $selected = ($campo['id'] == $templateParams["partita"]["id_campo"]) ? "selected" : ""; ?>
                            <option value="<?php echo $campo['id']; ?>" <?php echo $selected; ?>>
                                <?php echo $campo['nome']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Data e Ora</label>
                    <input type="datetime-local" name="data_ora" class="form-control" 
                           value="<?php echo $templateParams["partita"]["data_ora"]; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Giocatori Attuali</label>
                    <input type="number" name="giocatori_attuali" class="form-control" min="0" max="10" 
                           value="<?php echo $templateParams["partita"]["giocatori_attuali"]; ?>">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-secondary">Annulla</a>
                    <button type="submit" class="btn btn-success">Salva Modifiche</button>
                </div>
            </form>
        </div>
    </div>
</div>