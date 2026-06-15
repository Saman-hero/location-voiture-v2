<?php
require 'checkSession.php';
if ($_SESSION['role'] != 'AD') header('location:authForm.php?auth=role');
require 'dbconnection.php';
require 'myfunctions.php';
require 'navbar.php';

$id     = (int)$_GET['id'];
$client = mysqli_fetch_array(mysqli_query($connection, "select * from users where id=$id and role='CL'"));

if (!$client) {
    echo "<div class='alert alert-danger'>Client introuvable.</div>";
    echo "</div></body></html>";
    exit;
}

$email = mysqli_real_escape_string($connection, $client['email']);
$reservations = mysqli_query($connection,
    "select r.id, r.date_creation, r.statut,
            count(d.idVoiture) as nb_voitures,
            coalesce(sum(v.prix_jour * d.nb_jours),0) as montant
     from reservations r
     left join details_reservation d on r.id=d.idReservation
     left join voitures v on d.idVoiture=v.id
     where r.email_client='$email'
     group by r.id
     order by r.id desc");

$totalReserv  = mysqli_num_rows($reservations);
$totalDepense = mysqli_fetch_array(mysqli_query($connection,
    "select coalesce(sum(v.prix_jour*d.nb_jours),0) as t
     from reservations r
     join details_reservation d on r.id=d.idReservation
     join voitures v on d.idVoiture=v.id
     where r.email_client='$email'"))['t'];
?>
<div class="top-bar">
    <h1>Fiche Client</h1>
    <a href="allClients.php" class="btn btn-secondary btn-sm">&#8592; Retour</a>
</div>

<div class="profile-card mb-20">
    <div class="profile-header">
        <div class="profile-avatar"><?php echo strtoupper(substr($client['prenom'], 0, 1) . substr($client['nom'], 0, 1)); ?></div>
        <div>
            <h2><?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?></h2>
            <p><?php echo htmlspecialchars($client['email']); ?></p>
        </div>
    </div>
    <div class="profile-body">
        <div class="stat-cards" style="grid-template-columns:1fr 1fr;">
            <div class="stat-card green">
                <span class="stat-label">Réservations</span>
                <span class="stat-value"><?php echo $totalReserv; ?></span>
            </div>
            <div class="stat-card orange">
                <span class="stat-label">Montant total dépensé</span>
                <span class="stat-value" style="font-size:20px;"><?php echo formatPrix((float)$totalDepense); ?></span>
            </div>
        </div>
    </div>
</div>

<div class="section-header"><h2>Historique des réservations</h2></div>
<?php if ($totalReserv == 0): ?>
<div class="empty-state"><p>Ce client n'a pas encore de réservation.</p></div>
<?php else: ?>
<table>
    <tr><th>#</th><th>Date</th><th>Voitures</th><th>Montant</th><th>Statut</th><th>Action</th></tr>
    <?php
    mysqli_data_seek($reservations, 0);
    while ($r = mysqli_fetch_array($reservations)): ?>
    <tr>
        <td><?php echo $r['id']; ?></td>
        <td><?php echo date('d/m/Y H:i', strtotime($r['date_creation'])); ?></td>
        <td><?php echo $r['nb_voitures']; ?></td>
        <td><?php echo formatPrix((float)$r['montant']); ?></td>
        <td><?php echo statutBadge($r['statut']); ?></td>
        <td><a href="showReservationDetail.php?id=<?php echo $r['id']; ?>" class="btn btn-primary btn-sm">Voir</a></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php endif; ?>
<?php mysqli_close($connection); ?>
</div></body></html>
