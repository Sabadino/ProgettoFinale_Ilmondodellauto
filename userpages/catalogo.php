<?php
$pdo = DBHandler::getPDO();

$sql = "SELECT m.*, mi.URL as Immagine FROM macchina m LEFT JOIN macchina_immagini mi ON m.ID = mi.ID_Macchina AND mi.Ordine = 0 WHERE m.Stato = 'Disponibile'";

if(isset($_GET['tipo']) && $_GET['tipo'] != '') {
    $sql .= " AND m.TipoVeicolo = :tipo";
}
if(isset($_GET['marca']) && $_GET['marca'] != '') {
    $sql .= " AND m.Marca = :marca";
}

$sth = $pdo->prepare($sql);

if(isset($_GET['tipo']) && $_GET['tipo'] != '') {
    $sth->bindParam(':tipo', $_GET['tipo'], PDO::PARAM_STR);
}
if(isset($_GET['marca']) && $_GET['marca'] != '') {
    $sth->bindParam(':marca', $_GET['marca'], PDO::PARAM_STR);
}

$sth->execute();
$auto = $sth->fetchAll(PDO::FETCH_ASSOC);

$marche = $pdo->query("SELECT DISTINCT Marca FROM macchina ORDER BY Marca")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo - Il Mondo dell'Auto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/ProgettoFinale_Ilmondodellauto/style/catalogo.css">
</head>
<body>

<div class="container mt-4">

    <h1>Catalogo auto</h1>

    <form method="GET" class="mb-4 d-flex gap-2">
        <select name="tipo" class="form-select w-auto">
            <option value="">Tutti</option>
            <option value="Nuovo" <?php if(isset($_GET['tipo']) && $_GET['tipo'] == 'Nuovo') echo 'selected'; ?>>Nuovo</option>
            <option value="Usato" <?php if(isset($_GET['tipo']) && $_GET['tipo'] == 'Usato') echo 'selected'; ?>>Usato</option>
            <option value="Km Zero" <?php if(isset($_GET['tipo']) && $_GET['tipo'] == 'Km Zero') echo 'selected'; ?>>Km Zero</option>
        </select>

        <select name="marca" class="form-select w-auto">
            <option value="">Tutte le marche</option>
            <?php
            foreach($marche as $m) {
                $sel = (isset($_GET['marca']) && $_GET['marca'] == $m['Marca']) ? 'selected' : '';
                echo "<option value='" . $m['Marca'] . "' " . $sel . ">" . $m['Marca'] . "</option>";
            }
            ?>
        </select>

        <button type="submit" class="btn btn-success">Cerca</button>
    </form>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        if(count($auto) == 0) {
            echo "<p>Nessuna auto trovata.</p>";
        } else {
            foreach($auto as $a) {
                echo "
                <div class='col'>
                    <div class='card h-100'>
                        <a href='dettaglio.php?id=" . $a['ID'] . "'>";
                            if($a['Immagine']) {
                                echo "<img src='/ProgettoFinale_Ilmondodellauto/" . $a['Immagine'] . "' class='card-img-top' alt='" . $a['Marca'] . "'>";
                            } else {
                                echo "<div class='no-foto'>Nessuna foto</div>";
                            }
                        echo "</a>
                        <div class='card-body'>
                            <p class='car-marca'>" . $a['Marca'] . "</p>
                            <h5 class='card-title'>" . $a['Modello'] . "</h5>
                            <div class='d-flex gap-2 mb-2'>
                                <span class='badge-spec'>" . $a['Cavalli'] . " CV</span>
                                <span class='badge-spec'>" . $a['Anno'] . "</span>
                                <span class='badge-spec'>" . $a['Carrozzeria'] . "</span>
                            </div>
                            <div class='d-flex justify-content-between align-items-center mt-3'>
                                <strong>€ " . number_format($a['Prezzo'], 0, ',', '.') . "</strong>
                                <a href='dettaglio.php?id=" . $a['ID'] . "' class='btn btn-success btn-sm'>Vedi →</a>
                            </div>
                        </div>
                    </div>
                </div>";
            }
        }
        ?>
    </div>

</div>

</body>
</html>