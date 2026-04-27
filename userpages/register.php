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
    <title>Registrati - Il Mondo dell'Auto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/ProgettoFinale_Ilmondodellauto/style/auth.css">
</head>
<body>

<div class="auth-wrap">
    <div class="auth-card">

        <h4 class="text-center mb-1">Il Mondo <em>dell'Auto</em></h4>
        <p class="text-center text-muted mb-4">Crea il tuo account gratuito</p>

        <h2 class="mb-3">Registrati</h2>

        <?php
        if(isset($_GET['errore'])) {
            echo "<div class='alert-errore'>Email o username già in uso</div>";
        }
        ?>

        <form action="/ProgettoFinale_Ilmondodellauto/userpages/register_action.php" method="POST">
            <div class="fg">
                <label>Nome</label>
                <input type="text" name="nome" required>
            </div>
            <div class="fg">
                <label>Cognome</label>
                <input type="text" name="cognome" required>
            </div>
            <div class="fg">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="fg">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="fg">
                <label>Telefono</label>
                <input type="text" name="telefono">
            </div>
            <div class="fg">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Crea account</button>
        </form>

        <p class="text-center mt-3">Hai già un account? <a href="login.php">Accedi</a></p>

    </div>
</div>

</body>
</html>