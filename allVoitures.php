<?php
require 'checkSession.php';
require 'dbconnection.php';
require 'trace.php';
require 'navbar.php';

// --- Construction de la requête avec filtres ---
$where = [];

if (!empty($_GET['criterion'])) {
    $c = mysqli_real_escape_string($connection, $_GET['criterion']);
    $where[] = "(v.marque like '%$c%' or v.modele like '%$c%' or v.description like '%$c%')";
}
if (!empty($_GET['idCat'])) {
    $idCat = (int)$_GET['idCat'];
    $where[] = "v.idCat = $idCat";
}
if (!empty($_GET['carburant'])) {
    $carb = mysqli_real_escape_string($connection, $_GET['carburant']);
    $where[] = "v.carburant = '$carb'";
}
if (!empty($_GET['transmission'])) {
    $trans = mysqli_real_escape_string($connection, $_GET['transmission']);
    $where[] = "v.transmission = '$trans'";
}
if (isset($_GET['dispo']) && $_GET['dispo'] !== '') {
    $dispo = (int)$_GET['dispo'];
    $where[] = "v.disponible = $dispo";
}

$whereClause = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Tri
$sortOptions = ['marque' => 'Marque', 'prix_jour' => 'Prix', 'annee' => 'Année'];
$sort = in_array($_GET['sort'] ?? '', array_keys($sortOptions)) ? $_GET['sort'] : 'marque';
$order = ($_GET['order'] ?? 'asc') == 'desc' ? 'desc' : 'asc';

// Pagination
$perPage = 9;
$page    = max(1, (int)($_GET['page'] ?? 1));
$offset  = ($page - 1) * $perPage;

$countResult = mysqli_query($connection, "select count(*) as n from voitures v $whereClause");
$totalVoitures = mysqli_fetch_array($countResult)['n'];
$totalPages = max(1, ceil($totalVoitures / $perPage));

$query = "select v.*, c.name as categorie
          from voitures v
          left join categories c on v.idCat = c.id
          $whereClause
          order by v.$sort $order
          limit $perPage offset $offset";

$result = mysqli_query($connection, $query);
trace($query);

// Paramètres de pagination sans 'page'
$params = $_GET;
unset($params['page']);
$queryString = http_build_query($params);
$baseUrl = 'allVoitures.php?' . ($queryString ? $queryString . '&' : '');
?>
<div class="top-bar">
    <h1>Nos Voitures</h1>
    <div style="display:flex; gap:10px; align-items:center;">
        <?php if ($_SESSION['role'] == 'AD'): ?>
        <a href="VoitureForm.php" class="btn btn-success btn-sm">+ Ajouter</a>
        <?php endif; ?>
        <a href="showReservation.php" class="btn btn-primary btn-sm">
            &#128722; Panier (<?php echo count($_SESSION['reservation']); ?>)
        </a>
    </div>
</div>

<?php require 'search.php'; ?>

<?php
if (isset($_GET['msg'])):
    $msgs = ['added' => 'Voiture ajoutée au panier.', 'already' => 'Déjà dans le panier.', 'dates' => 'Dates invalides.'];
    $cls = isset($_GET['msg']) && $_GET['msg'] != 'added' ? 'alert-warning' : 'alert-success';
    echo "<div class='alert $cls'>" . ($msgs[$_GET['msg']] ?? '') . "</div>";
endif;
?>

<div class="section-header">
    <div>
        <strong><?php echo $totalVoitures; ?></strong> voiture(s) trouvée(s)
        &nbsp; Tri :
        <?php foreach ($sortOptions as $key => $label): ?>
            <a href="<?php echo $baseUrl; ?>sort=<?php echo $key; ?>&order=<?php echo $order; ?>">
                <?php echo $label; ?>
            </a>&nbsp;
        <?php endforeach; ?>
        | <a href="<?php echo $baseUrl; ?>sort=<?php echo $sort; ?>&order=<?php echo $order == 'asc' ? 'desc' : 'asc'; ?>">
            <?php echo $order == 'asc' ? '&#8593; Asc' : '&#8595; Desc'; ?>
          </a>
    </div>
</div>

<?php if ($totalVoitures == 0): ?>
<div class="empty-state">
    <p>Aucune voiture trouvée pour ces critères.</p>
    <a href="allVoitures.php" class="btn btn-primary mt-10">Voir toutes les voitures</a>
</div>
<?php else: ?>

<div class="cards-grid">
<?php while ($voiture = mysqli_fetch_array($result)): ?>
<div class="car-card">
    <img src="<?php echo htmlspecialchars($voiture['path']); ?>" alt="<?php echo $voiture['marque']; ?>">
    <div class="car-card-body">
        <div class="car-card-title"><?php echo $voiture['marque'] . ' ' . $voiture['modele']; ?></div>
        <div class="car-card-meta">
            <span><?php echo $voiture['annee']; ?></span>
            <span><?php echo $voiture['carburant']; ?></span>
            <span><?php echo $voiture['transmission']; ?></span>
            <span><?php echo $voiture['places']; ?> places</span>
            <span><?php echo $voiture['categorie'] ?? '—'; ?></span>
        </div>
        <div class="car-card-price"><?php echo $voiture['prix_jour']; ?> MAD/jour</div>
        <div class="<?php echo $voiture['disponible'] ? 'badge-disponible' : 'badge-indisponible'; ?>">
            <?php echo $voiture['disponible'] ? '&#10003; Disponible' : '&#10007; Indisponible'; ?>
        </div>
    </div>
    <div class="car-card-footer">
        <a href="showVoiture.php?num=<?php echo $voiture['id']; ?>" class="btn btn-primary btn-sm">Détails</a>

        <?php if ($voiture['disponible'] && $_SESSION['role'] == 'CL'): ?>
        <form action="addToReservation.php" method="get" style="display:flex; gap:6px; align-items:center; flex-wrap:wrap;">
            <input type="hidden" name="idVoiture" value="<?php echo $voiture['id']; ?>">
            <input type="date" name="date_debut" required min="<?php echo date('Y-m-d'); ?>" style="padding:4px 8px; font-size:12px; width:130px;">
            <input type="date" name="date_fin"   required min="<?php echo date('Y-m-d'); ?>" style="padding:4px 8px; font-size:12px; width:130px;">
            <button type="submit" class="btn btn-success btn-sm">Réserver</button>
        </form>
        <?php endif; ?>

        <?php if ($_SESSION['role'] == 'AD'): ?>
        <a href="editVoiture.php?num=<?php echo $voiture['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
        <a href="deleteVoiture.php?num=<?php echo $voiture['id']; ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Supprimer cette voiture ?')">Supprimer</a>
        <?php endif; ?>
    </div>
</div>
<?php endwhile; ?>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="<?php echo $baseUrl; ?>page=<?php echo $page-1; ?>">&laquo;</a>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <?php if ($i == $page): ?>
            <span class="current"><?php echo $i; ?></span>
        <?php else: ?>
            <a href="<?php echo $baseUrl; ?>page=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?>
        <a href="<?php echo $baseUrl; ?>page=<?php echo $page+1; ?>">&raquo;</a>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php endif; ?>
<?php mysqli_close($connection); ?>
</div></body></html>
