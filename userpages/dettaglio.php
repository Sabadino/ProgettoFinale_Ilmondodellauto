<?php
if(!isset($_GET['id'])) {
    header('Location: catalogo.php');
    exit;
}

$pdo = DBHandler::getPDO();
$id = $_GET['id'];

$sth = $pdo->prepare("SELECT * FROM macchina WHERE ID = :id");
$sth->bindParam(':id', $id, PDO::PARAM_INT);
$sth->execute();
$macchina = $sth->fetch(PDO::FETCH_ASSOC);

if(!$macchina) {
    header('Location: catalogo.php');
    exit;
}

$queryFoto = $pdo->prepare("SELECT URL FROM macchina_immagini WHERE ID_Macchina = :id ORDER BY Ordine");
$queryFoto->bindParam(':id', $id, PDO::PARAM_INT);
$queryFoto->execute();
$foto = $queryFoto->fetchAll(PDO::FETCH_ASSOC);

$queryAcc = $pdo->prepare("SELECT a.Nome FROM accessori a JOIN macchina_accessori ma ON a.ID = ma.ID_Accessorio WHERE ma.ID_Macchina = :id");
$queryAcc->bindParam(':id', $id, PDO::PARAM_INT);
$queryAcc->execute();
$accessori = $queryAcc->fetchAll(PDO::FETCH_ASSOC);

$queryRec = $pdo->prepare("SELECT r.*, u.Nome as NomeUtente FROM recensioni r JOIN utente u ON r.ID_Utente = u.ID WHERE r.ID_Macchina = :id ORDER BY r.DataOraPubblicazione DESC");
$queryRec->bindParam(':id', $id, PDO::PARAM_INT);
$queryRec->execute();
$recensioni = $queryRec->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $macchina['Marca'] . ' ' . $macchina['Modello']; ?> - Il Mondo dell'Auto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/ProgettoFinale_Ilmondodellauto/style/dettaglio.css">
</head>
<body>

<div class="container mt-4">

    <a href="catalogo.php" class="text-decoration-none">← Torna al catalogo</a>

    <div class="row mt-3 g-4">

        <div class="col-md-6">
            <?php
            if(count($foto) > 0) {
                echo "<img src='/ProgettoFinale_Ilmondodellauto/" . $foto[0]['URL'] . "' class='foto-principale' alt='" . $macchina['Marca'] . "'>";
                echo "<div class='thumbnails mt-2 d-flex gap-2'>";
                foreach($foto as $f) {
                    echo "<img src='/ProgettoFinale_Ilmondodellauto/" . $f['URL'] . "' class='thumbnail' alt=''>";
                }
                echo "</div>";
            } else {
                echo "<div class='no-foto'>Nessuna foto</div>";
            }
            ?>

            <div class="descrizione mt-3 p-3">
                <h5>Descrizione</h5>
                <p><?php echo $macchina['Descrizione']; ?></p>
            </div>
        </div>

        <div class="col-md-6">
            <p class="car-marca"><?php echo $macchina['Marca']; ?></p>
            <h2><?php echo $macchina['Modello']; ?></h2>
            <p><?php echo $macchina['TipoVeicolo'] . ' · ' . $macchina['Anno']; ?></p>
            <h3 class="prezzo">€ <?php echo number_format($macchina['Prezzo'], 0, ',', '.'); ?></h3>
            <p class="text-muted">IVA inclusa</p>

            <div class="ctas mt-3">
                <?php
                if(isset($_SESSION['utente_id'])) {
                    echo "<a href='prenotazione.php?id=" . $macchina['ID'] . "' class='btn-prenota'>📅 Prenota test drive</a>";
                } else {
                    echo "<a href='login.php' class='btn-prenota'>📅 Prenota test drive</a>";
                }
                ?>
                <a href="https://wa.me/393802074281" target="_blank" class="btn-wa">💬 WhatsApp</a>
                <a href="https://www.subito.it" target="_blank" class="btn-subito">🔗 Vedi su Subito.it</a>
                <a href="tel:+393802074281" class="btn-tel">📞 Chiama</a>
                <?php
                if(isset($_SESSION['utente_id'])) {
                    echo "<a href='wishlist_action.php?id=" . $macchina['ID'] . "&azione=aggiungi' class='btn-wish'>♡ Salva</a>";
                }
                ?>
            </div>

            <div class="specifiche mt-4">
                <h5>Specifiche</h5>
                <table class="table table-sm">
                    <tr><td class="text-muted">Chilometri</td><td><?php echo number_format($macchina['Chilometraggio'], 0, ',', '.'); ?> km</td></tr>
                    <tr><td class="text-muted">Potenza</td><td><?php echo $macchina['Cavalli']; ?> CV</td></tr>
                    <tr><td class="text-muted">Cilindrata</td><td><?php echo $macchina['Cilindrata']; ?> cc</td></tr>
                    <tr><td class="text-muted">Carrozzeria</td><td><?php echo $macchina['Carrozzeria']; ?></td></tr>
                    <tr><td class="text-muted">Colore interni</td><td><?php echo $macchina['ColoreInterni']; ?></td></tr>
                    <tr><td class="text-muted">Neopatentati</td><td><?php echo $macchina['Neopatentati'] ? 'Sì' : 'No'; ?></td></tr>
                    <tr><td class="text-muted">Targa</td><td><?php echo $macchina['Targa']; ?></td></tr>
                </table>
            </div>

            <?php
            if(count($accessori) > 0) {
                echo "<div class='optional mt-3'>";
                echo "<h5>Optional</h5>";
                echo "<div class='d-flex flex-wrap gap-2'>";
                foreach($accessori as $a) {
                    echo "<span class='badge-acc'>" . $a['Nome'] . "</span>";
                }
                echo "</div></div>";
            }
            ?>
        </div>
    </div>

    <div class="recensioni mt-5">
        <h4>Recensioni</h4>
        <?php
        if(count($recensioni) == 0) {
            echo "<p>Nessuna recensione ancora.</p>";
        } else {
            foreach($recensioni as $r) {
                echo "
                <div class='rec-item mb-3 p-3'>
                    <strong>" . $r['NomeUtente'] . "</strong>
                    <span class='ms-2'>" . $r['Valutazione'] . "/5 ★</span>
                    <p class='mt-1 mb-0'>" . $r['Testo'] . "</p>
                </div>";
            }
        }
        ?>
    </div>

</div>

</body>
</html>