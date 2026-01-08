<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>UniCalcio - <?php echo $templateParams["titolo"]; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="index.php">⚽ UniCalcio</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                <?php if(isset($_SESSION["idutente"])): ?>
                
        
                <li class="nav-item">
                <a class="nav-link active" href="logout.php">Esci</a>
                </li>

                <?php else: ?>
                <li class="nav-item">
                <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="registrazione.php">Registrati</a>
                </li>
        
                <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        <?php
            if(isset($templateParams["nome"])){
                require($templateParams["nome"]);
            }
        ?>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">© 2025 UniCalcio - Progetto Tecnologie Web</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>