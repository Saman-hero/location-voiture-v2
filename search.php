<?php
require 'dbconnection.php';
$categories = mysqli_query($connection, "select * from categories order by name");
?>
<form method="get" action="allVoitures.php" class="search-bar">
    <input type="text" name="criterion" placeholder="Rechercher une voiture..." value="<?php echo htmlspecialchars($_GET['criterion'] ?? ''); ?>">

    <select name="idCat">
        <option value="">Toutes les catégories</option>
        <?php while ($cat = mysqli_fetch_array($categories)):
            $sel = (isset($_GET['idCat']) && $_GET['idCat'] == $cat['id']) ? 'selected' : ''; ?>
            <option value="<?php echo $cat['id']; ?>" <?php echo $sel; ?>><?php echo $cat['name']; ?></option>
        <?php endwhile; ?>
    </select>

    <select name="carburant">
        <option value="">Tous carburants</option>
        <?php foreach (['Essence','Diesel','Electrique','Hybride'] as $c):
            $sel = (isset($_GET['carburant']) && $_GET['carburant'] == $c) ? 'selected' : ''; ?>
            <option value="<?php echo $c; ?>" <?php echo $sel; ?>><?php echo $c; ?></option>
        <?php endforeach; ?>
    </select>

    <select name="transmission">
        <option value="">Toutes transmissions</option>
        <?php foreach (['Manuelle','Automatique'] as $t):
            $sel = (isset($_GET['transmission']) && $_GET['transmission'] == $t) ? 'selected' : ''; ?>
            <option value="<?php echo $t; ?>" <?php echo $sel; ?>><?php echo $t; ?></option>
        <?php endforeach; ?>
    </select>

    <select name="dispo">
        <option value="">Disponibilité</option>
        <option value="1" <?php echo (isset($_GET['dispo']) && $_GET['dispo'] === '1') ? 'selected' : ''; ?>>Disponible</option>
        <option value="0" <?php echo (isset($_GET['dispo']) && $_GET['dispo'] === '0') ? 'selected' : ''; ?>>Indisponible</option>
    </select>

    <button type="submit" class="btn btn-primary">Rechercher</button>
    <a href="allVoitures.php" class="btn btn-secondary">Réinitialiser</a>
</form>
