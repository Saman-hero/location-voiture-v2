<?php
require 'checkSession.php';
require 'dbconnection.php';
require 'myfunctions.php';
require 'navbar.php';

$id = (int)$_GET['id'];

$resQuery = "select * from reservations where id = $id";
$res      = mysqli_fetch_array(mysqli_query($connection, $resQuery));

if (!$res || ($_SESSION['role'] != 'AD' && $res['email_client'] != $_SESSION['user'])) {
    echo "<div class='alert alert-danger'>Accès refusé ou réservation introuvable.</div>";
    echo "</div></body></html>";
    exit;
}

$detailsQuery = "select d.*, v.marque, v.modele, v.prix_jour, v.path
                 from details_reservation d
                 join voitures v on d.idVoiture = v.id
                 where d.idReservation = $id";
$details = mysqli_query($connection, $detailsQuery);

$total = 0;
$rows  = [];
while ($row = mysqli_fetch_array($details)) {
    $sous_total = $row['prix_jour'] * $row['nb_jours'];
    $total += $sous_total;
    $rows[] = array_merge($row, ['sous_total' => $sous_total]);
}
?>
<div class="top-bar">
    <h1>Réservation #<?php echo $id; ?></h1>
    <a href="allReservations.php" class="btn btn-secondary btn-sm">&#8592; Retour</a>
</div>

<div style="display:grid; grid-template-columns:2fr 1fr; gap:22px; align-items:start;">
<div>
    <table>
        <tr>
            <th>Voiture</th>
            <th>Début</th>
            <th>Fin</th>
            <th>Jours</th>
            <th>Prix/j</th>
            <th>Sous-total</th>
        </tr>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td>
                <div style="display:flex; align-items:center; gap:10px;">
                    <img src="<?php echo $row['path']; ?>" class="car-thumb">
                    <strong><?php echo $row['marque'] . ' ' . $row['modele']; ?></strong>
                </div>
            </td>
            <td><?php echo formatDate($row['date_debut']); ?></td>
            <td><?php echo formatDate($row['date_fin']); ?></td>
            <td><?php echo $row['nb_jours']; ?></td>
            <td><?php echo formatPrix((float)$row['prix_jour']); ?></td>
            <td><strong><?php echo formatPrix($row['sous_total']); ?></strong></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="5" style="text-align:right; font-weight:bold;">TOTAL</td>
            <td><strong style="font-size:16px; color:var(--primary);"><?php echo formatPrix($total); ?></strong></td>
        </tr>
    </table>
</div>

<div style="background:white; border-radius:10px; padding:24px; box-shadow:var(--shadow);">
    <h2 style="color:var(--primary); margin-bottom:16px;">Informations</h2>
    <div class="divider"></div>
    <p><strong>Client :</strong> <?php echo htmlspecialchars($res['email_client']); ?></p>
    <p style="margin-top:10px;"><strong>Date :</strong> <?php echo date('d/m/Y H:i', strtotime($res['date_creation'])); ?></p>
    <p style="margin-top:10px;"><strong>Statut :</strong> <?php echo statutBadge($res['statut']); ?></p>
    <div class="divider"></div>
    <p style="font-size:20px; font-weight:bold; color:var(--primary);">Total : <?php echo formatPrix($total); ?></p>

    <?php if ($_SESSION['role'] == 'AD'): ?>
    <div class="divider"></div>
    <div style="display:flex; flex-direction:column; gap:8px;">
        <a href="changeStatutReservation.php?id=<?php echo $id; ?>&statut=confirmee"   class="btn btn-success">Confirmer</a>
        <a href="changeStatutReservation.php?id=<?php echo $id; ?>&statut=annulee"     class="btn btn-danger"   onclick="return confirm('Annuler ?')">Annuler</a>
        <a href="changeStatutReservation.php?id=<?php echo $id; ?>&statut=terminee"    class="btn btn-secondary">Marquer Terminée</a>
    </div>
    <?php endif; ?>
</div>
</div>
<?php mysqli_close($connection); ?>
</div></body></html>
