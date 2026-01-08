<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="text-center mb-4">Accedi</h3>
                <?php if(isset($templateParams["errore"])): ?>
                <div class="alert alert-danger">
                <?php echo $templateParams["errore"]; ?>
                </div>
                <?php endif; ?>
                <form action="#" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Universitaria</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="nome@studio.unibo.it">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 btn-lg">Entra</button>
                </form>
                
                <div class="text-center mt-3">
                    <small>Non hai un account? <a href="registrazione.php">Registrati qui</a></small>
                </div>
            </div>
        </div>
    </div>
</div>