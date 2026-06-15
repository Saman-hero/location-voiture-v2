<?php
require 'checkSession.php';
require 'dbconnection.php';
require 'navbar.php';

$num = (int)$_GET['num'];
$query = "select v.*, c.name as categorie
          from voitures v
          left join categories c on v.idCat = c.id
          where v.id = $num";
$result = mysqli_query($connection, $query);
$voiture = mysqli_fetch_array($result);

if (!$voiture) {
    echo "<div class='alert alert-danger'>Voiture introuvable.</div>";
    echo "<a href='allVoitures.php' class='btn btn-secondary'>Retour</a>";
    mysqli_close($connection);
    echo "</div></body></html>";
    exit;
}
?>
<div class="top-bar">
    <h1><?php echo htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']); ?></h1>
    <a href="allVoitures.php" class="btn btn-secondary btn-sm">&#8592; Retour</a>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:26px; background:white; border-radius:10px; box-shadow:var(--shadow); overflow:hidden;">
    <div>
        <img src="<?php echo htmlspecialchars($voiture['path']); ?>"
             alt="<?php echo $voiture['marque']; ?>"
             style="width:100%; height:320px; object-fit:cover;">
    </div>
    <div style="padding:30px;">
        <h2 style="color:var(--primary); margin-bottom:14px;">
            <?php echo $voiture['marque'] . ' ' . $voiture['modele']; ?>
        </h2>

        <table style="box-shadow:none; margin-bottom:18px;">
            <tr><td><strong>Année</strong></td><td><?php echo $voiture['annee']; ?></td></tr>
            <tr><td><strong>Catégorie</strong></td><td><?php echo $voiture['categorie'] ?? '—'; ?></td></tr>
            <tr><td><strong>Carburant</strong></td><td><?php echo $voiture['carburant']; ?></td></tr>
            <tr><td><strong>Transmission</strong></td><td><?php echo $voiture['transmission']; ?></td></tr>
            <tr><td><strong>Places</strong></td><td><?php echo $voiture['places']; ?></td></tr>
            <tr>
                <td><strong>Disponibilité</strong></td>
                <td class="<?php echo $voiture['disponible'] ? 'badge-disponible' : 'badge-indisponible'; ?>">
                    <?php echo $voiture['disponible'] ? 'Disponible' : 'Indisponible'; ?>
                </td>
            </tr>
        </table>

        <?php if ($voiture['description']): ?>
        <p style="color:#555; margin-bottom:18px; font-size:14px;">
            <?php echo htmlspecialchars($voiture['description']); ?>
        </p>
        <?php endif; ?>

        <div style="font-size:26px; font-weight:bold; color:var(--primary); margin-bottom:20px;">
            <?php echo $voiture['prix_jour']; ?> MAD<span style="font-size:15px; font-weight:normal;">/jour</span>
        </div>

        <?php if ($voiture['disponible'] && $_SESSION['role'] == 'CL'): ?>
        <form action="addToReservation.php" method="get">
            <input type="hidden" name="idVoiture" value="<?php echo $voiture['id']; ?>">
            <div class="form-group">
                <label>Date de début</label>
                <input type="date" name="date_debut" required min="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="form-group">
                <label>Date de fin</label>
                <input type="date" name="date_fin" required min="<?php echo date('Y-m-d'); ?>">
            </div>
            <button type="submit" class="btn btn-success btn-lg">Ajouter au panier</button>
        </form>
        <?php elseif ($_SESSION['role'] == 'AD'): ?>
        <div style="display:flex; gap:10px;">
            <a href="editVoiture.php?num=<?php echo $voiture['id']; ?>" class="btn btn-warning">Modifier</a>
            <a href="deleteVoiture.php?num=<?php echo $voiture['id']; ?>"
               class="btn btn-danger"
               onclick="return confirm('Supprimer cette voiture ?')">Supprimer</a>
        </div>
        <?php else: ?>
        <div class="alert alert-warning">Cette voiture est actuellement indisponible.</div>
        <?php endif; ?>
    </div>
</div>
<?php mysqli_close($connection); ?>
</div></body></html>
