<?php
require 'checkSession.php';
require 'dbconnection.php';
require 'myfunctions.php';
require 'navbar.php';

$isAdmin = $_SESSION['role'] == 'AD';

if ($isAdmin) {
    $query = "select r.id, r.date_creation, r.email_client, r.statut,
                     count(d.idVoiture) as nb_voitures,
                     coalesce(sum(v.prix_jour * d.nb_jours), 0) as montant
              from reservations r
              left join details_reservation d on r.id = d.idReservation
              left join voitures v on d.idVoiture = v.id
              group by r.id
              order by r.id desc";
} else {
    $email = mysqli_real_escape_string($connection, $_SESSION['user']);
    $query = "select r.id, r.date_creation, r.email_client, r.statut,
                     count(d.idVoiture) as nb_voitures,
                     coalesce(sum(v.prix_jour * d.nb_jours), 0) as montant
              from reservations r
              left join details_reservation d on r.id = d.idReservation
              left join voitures v on d.idVoiture = v.id
              where r.email_client = '$email'
              group by r.id
              order by r.id desc";
}

$result = mysqli_query($connection, $query);
?>
<div class="top-bar">
    <h1><?php echo $isAdmin ? 'Toutes les Réservations' : 'Mes Réservations'; ?></h1>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'confirmed'): ?>
<div class="alert alert-success">Votre réservation a été confirmée avec succès !</div>
<?php endif; ?>

<?php if (mysqli_num_rows($result) == 0): ?>
<div class="empty-state">
    <p>Aucune réservation trouvée.</p>
    <?php if (!$isAdmin): ?>
    <a href="allVoitures.php" class="btn btn-primary mt-10">Réserver une voiture</a>
    <?php endif; ?>
</div>
<?php else: ?>
<table>
    <tr>
        <th>#</th>
        <?php if ($isAdmin): ?><th>Client</th><?php endif; ?>
        <th>Date</th>
        <th>Voitures</th>
        <th>Montant Total</th>
        <th>Statut</th>
        <th>Actions</th>
    </tr>
    <?php while ($r = mysqli_fetch_array($result)): ?>
    <tr>
        <td><?php echo $r['id']; ?></td>
        <?php if ($isAdmin): ?><td><?php echo htmlspecialchars($r['email_client']); ?></td><?php endif; ?>
        <td><?php echo date('d/m/Y H:i', strtotime($r['date_creation'])); ?></td>
        <td><?php echo $r['nb_voitures']; ?> voiture(s)</td>
        <td><strong><?php echo formatPrix((float)$r['montant']); ?></strong></td>
        <td><?php echo statutBadge($r['statut']); ?></td>
        <td class="table-actions">
            <a href="showReservationDetail.php?id=<?php echo $r['id']; ?>" class="btn btn-primary btn-sm">Détails</a>
            <?php if ($isAdmin): ?>
            <a href="changeStatutReservation.php?id=<?php echo $r['id']; ?>&statut=annulee"
               class="btn btn-danger btn-sm"
               onclick="return confirm('Annuler cette réservation ?')">Annuler</a>
            <a href="changeStatutReservation.php?id=<?php echo $r['id']; ?>&statut=terminee"
               class="btn btn-secondary btn-sm">Clore</a>
            <?php elseif ($r['statut'] == 'confirmee'): ?>
            <a href="changeStatutReservation.php?id=<?php echo $r['id']; ?>&statut=annulee"
               class="btn btn-danger btn-sm"
               onclick="return confirm('Annuler cette réservation ?')">Annuler</a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php endif; ?>
<?php mysqli_close($connection); ?>
</div></body></html>
