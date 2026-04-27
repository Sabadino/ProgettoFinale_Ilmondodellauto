<?php
if(isset($_SESSION['utente_id'])) {
    header('Location: catalogo.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Il Mondo dell'Auto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/ProgettoFinale_Ilmondodellauto/style/auth.css">
</head>
<body>

<div class="auth-wrap">
    <div class="auth-card">

        <h4 class="text-center mb-1">Il Mondo <em>dell'Auto</em></h4>
        <p class="text-center text-muted mb-4">Accedi per prenotare e salvare le auto</p>

        <h2 class="mb-3">Bentornato</h2>

        <?php
        if(isset($_GET['errore'])) {
            echo "<div class='alert-errore'>Email o password errati</div>";
        }
        ?>

        <form action="/ProgettoFinale_Ilmondodellauto/userpages/login_action.php" method="POST">
            <div class="fg">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="fg">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Accedi</button>
        </form>

        <p class="text-center mt-3">Non hai un account? <a href="register.php">Registrati</a></p>

    </div>
</div>

</body>
</html>