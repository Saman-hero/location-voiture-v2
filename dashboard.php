<?php
require 'checkSession.php';
if ($_SESSION['role'] != 'AD') header('location:allVoitures.php');
require 'dbconnection.php';
require 'myfunctions.php';
require 'navbar.php';

// Statistiques
$totalVoitures    = mysqli_fetch_array(mysqli_query($connection, "select count(*) as n from voitures"))['n'];
$voituresDispo    = mysqli_fetch_array(mysqli_query($connection, "select count(*) as n from voitures where disponible=1"))['n'];
$totalClients     = mysqli_fetch_array(mysqli_query($connection, "select count(*) as n from users where role='CL'"))['n'];
$totalReservations= mysqli_fetch_array(mysqli_query($connection, "select count(*) as n from reservations"))['n'];

$today = date('Y-m-d');
$reservAujourd = mysqli_fetch_array(mysqli_query($connection, "select count(*) as n from reservations where date(date_creation)='$today'"))['n'];

$revenuMois = mysqli_fetch_array(mysqli_query($connection,
    "select coalesce(sum(v.prix_jour * d.nb_jours),0) as total
     from reservations r
     join details_reservation d on r.id = d.idReservation
     join voitures v on d.idVoiture = v.id
     where month(r.date_creation)=month(now()) and year(r.date_creation)=year(now())"))['total'];

// Dernières réservations
$lastReserv = mysqli_query($connection,
    "select r.id, r.date_creation, r.email_client, r.statut,
            count(d.idVoiture) as nb_voitures,
            sum(v.prix_jour * d.nb_jours) as montant
     from reservations r
     left join details_reservation d on r.id = d.idReservation
     left join voitures v on d.idVoiture = v.id
     group by r.id
     order by r.id desc limit 8");

// Répartition par catégorie
$parCategorie = mysqli_query($connection,
    "select c.name, count(v.id) as total
     from categories c
     left join voitures v on c.id = v.idCat
     group by c.id, c.name");
?>
<div class="top-bar">
    <h1>Tableau de bord</h1>
    <span class="user-info">Aujourd'hui : <?php echo date('d/m/Y'); ?></span>
</div>

<div class="stat-cards">
    <div class="stat-card">
        <span class="stat-label">Total Voitures</span>
        <span class="stat-value"><?php echo $totalVoitures; ?></span>
        <small><?php echo $voituresDispo; ?> disponibles</small>
    </div>
    <div class="stat-card green">
        <span class="stat-label">Réservations aujourd'hui</span>
        <span class="stat-value"><?php echo $reservAujourd; ?></span>
        <small><?php echo $totalReservations; ?> au total</small>
    </div>
    <div class="stat-card orange">
        <span class="stat-label">Revenu ce mois</span>
        <span class="stat-value" style="font-size:22px"><?php echo formatPrix((float)$revenuMois); ?></span>
    </div>
    <div class="stat-card purple">
        <span class="stat-label">Clients inscrits</span>
        <span class="stat-value"><?php echo $totalClients; ?></span>
    </div>
</div>

<div style="display:grid; grid-template-columns: 2fr 1fr; gap:22px; align-items:start;">

<div>
    <div class="section-header">
        <h2>Dernières Réservations</h2>
        <a href="allReservations.php" class="btn btn-outline btn-sm">Voir tout</a>
    </div>
    <table>
        <tr>
            <th>#</th>
            <th>Client</th>
            <th>Date</th>
            <th>Voitures</th>
            <th>Montant</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        <?php while ($r = mysqli_fetch_array($lastReserv)): ?>
        <tr>
            <td><?php echo $r['id']; ?></td>
            <td><?php echo htmlspecialchars($r['email_client']); ?></td>
            <td><?php echo date('d/m/Y', strtotime($r['date_creation'])); ?></td>
            <td><?php echo $r['nb_voitures']; ?></td>
            <td><?php echo formatPrix((float)$r['montant']); ?></td>
            <td><?php echo statutBadge($r['statut']); ?></td>
            <td>
                <a href="showReservationDetail.php?id=<?php echo $r['id']; ?>" class="btn btn-primary btn-sm">Voir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<div>
    <div class="section-header"><h2>Voitures par Catégorie</h2></div>
    <table>
        <tr><th>Catégorie</th><th>Voitures</th></tr>
        <?php while ($cat = mysqli_fetch_array($parCategorie)): ?>
        <tr>
            <td><?php echo htmlspecialchars($cat['name']); ?></td>
            <td><?php echo $cat['total']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="mt-20 section-header">
        <h2>Accès Rapides</h2>
    </div>
    <div style="display:flex; flex-direction:column; gap:10px;">
        <a href="VoitureForm.php" class="btn btn-success">+ Ajouter une Voiture</a>
        <a href="allClients.php"  class="btn btn-primary">&#9632; Gérer les Clients</a>
        <a href="allCategories.php" class="btn btn-primary">&#9632; Gérer les Catégories</a>
    </div>
</div>

</div>
<?php mysqli_close($connection); ?>
</div></body></html>
