<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">Crea un Account</h3>
                
                <?php if(isset($templateParams["errore"])): ?>
                    <div class="alert alert-danger">
                        <?php echo $templateParams["errore"]; ?>
                    </div>
                <?php endif; ?>

                <form action="#" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cognome" class="form-label">Cognome</label>
                            <input type="text" class="form-control" id="cognome" name="cognome" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Universitaria</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="nome@studio.unibo.it" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="mb-4">
                        <label for="confirm_password" class="form-label">Conferma Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100 btn-lg">Registrati</button>
                </form>
                
                <div class="text-center mt-3">
                    <small>Hai già un account? <a href="login.php">Accedi qui</a></small>
                </div>
            </div>
        </div>
    </div>
</div>