<?php
require 'checkSession.php';
require 'dbconnection.php';
require 'myfunctions.php';
require 'navbar.php';

$total      = 0;
$nbVoitures = 0;
$items      = [];

foreach ($_SESSION['reservation'] as $item) {
    $result  = mysqli_query($connection, "select id, marque, modele, prix_jour, path from voitures where id=" . (int)$item['idVoiture']);
    $voiture = mysqli_fetch_array($result);
    if ($voiture) {
        $nb_jours   = calculerNbJours($item['date_debut'], $item['date_fin']);
        $sous_total = $voiture['prix_jour'] * $nb_jours;
        $total     += $sous_total;
        $items[]    = compact('voiture', 'item', 'nb_jours', 'sous_total');
        $nbVoitures++;
    }
}
?>
<div class="top-bar">
    <h1>Mon Panier</h1>
    <a href="allVoitures.php" class="btn btn-secondary btn-sm">&#8592; Continuer</a>
</div>

<?php if ($nbVoitures == 0): ?>
<div class="empty-state">
    <p>Votre panier est vide.</p>
    <a href="allVoitures.php" class="btn btn-primary mt-10">Voir nos voitures</a>
</div>
<?php else: ?>

<div style="display:grid; grid-template-columns:2fr 1fr; gap:22px; align-items:start;">
<div>
    <table>
        <tr>
            <th>Voiture</th>
            <th>Dates</th>
            <th>Jours</th>
            <th>Prix/j</th>
            <th>Sous-total</th>
            <th>Action</th>
        </tr>
        <?php foreach ($items as $i): ?>
        <tr>
            <td>
                <div style="display:flex; align-items:center; gap:10px;">
                    <img src="<?php echo $i['voiture']['path']; ?>" class="car-thumb">
                    <strong><?php echo $i['voiture']['marque'] . ' ' . $i['voiture']['modele']; ?></strong>
                </div>
            </td>
            <td><?php echo formatDate($i['item']['date_debut']); ?> → <?php echo formatDate($i['item']['date_fin']); ?></td>
            <td><?php echo $i['nb_jours']; ?></td>
            <td><?php echo formatPrix((float)$i['voiture']['prix_jour']); ?></td>
            <td><strong><?php echo formatPrix($i['sous_total']); ?></strong></td>
            <td>
                <a href="deleteFromReservation.php?id=<?php echo $i['voiture']['id']; ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Retirer cette voiture ?')">Retirer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div style="background:white; border-radius:10px; padding:24px; box-shadow:var(--shadow);">
    <h2 style="color:var(--primary); margin-bottom:16px;">Récapitulatif</h2>
    <div class="divider"></div>
    <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
        <span>Nombre de voitures</span>
        <strong><?php echo $nbVoitures; ?></strong>
    </div>
    <div class="divider"></div>
    <div style="display:flex; justify-content:space-between; font-size:20px; font-weight:bold; color:var(--primary);">
        <span>Total</span>
        <span><?php echo formatPrix($total); ?></span>
    </div>
    <div class="divider"></div>
    <a href="confirmerReservation.php" class="btn btn-success btn-lg">Confirmer la Réservation</a>
</div>
</div>

<?php endif; ?>
<?php mysqli_close($connection); ?>
</div></body></html>
