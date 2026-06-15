<?php
require 'checkSession.php';
if ($_SESSION['role'] != 'AD') header('location:authForm.php?auth=role');
require 'dbconnection.php';
require 'navbar.php';

$num    = (int)$_GET['num'];
$result = mysqli_query($connection, "select * from voitures where id = $num");
$voiture = mysqli_fetch_array($result);
?>
<div class="top-bar">
    <h1>Modifier la Voiture</h1>
    <a href="allVoitures.php" class="btn btn-secondary btn-sm">&#8592; Retour</a>
</div>

<div class="form-container">
    <form action="updateVoiture.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $voiture['id']; ?>">
        <p class="hint">ID : <?php echo $voiture['id']; ?></p>

        <div class="form-row">
            <div class="form-group">
                <label class="required">Marque</label>
                <input type="text" name="marque" required value="<?php echo htmlspecialchars($voiture['marque']); ?>" maxlength="100">
            </div>
            <div class="form-group">
                <label class="required">Modèle</label>
                <input type="text" name="modele" required value="<?php echo htmlspecialchars($voiture['modele']); ?>" maxlength="100">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="required">Année</label>
                <input type="number" name="annee" required min="1990" max="2030" value="<?php echo $voiture['annee']; ?>">
            </div>
            <div class="form-group">
                <label class="required">Prix/Jour (MAD)</label>
                <input type="number" name="prix_jour" required step="0.01" min="0" value="<?php echo $voiture['prix_jour']; ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="required">Carburant</label>
                <select name="carburant" required>
                    <?php foreach (['Essence','Diesel','Electrique','Hybride'] as $c)
                        echo "<option value='$c'" . ($voiture['carburant'] == $c ? " selected" : "") . ">$c</option>"; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="required">Places</label>
                <input type="number" name="places" required min="2" max="9" value="<?php echo $voiture['places']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="required">Transmission</label>
            <div class="radio-group">
                <label><input type="radio" name="transmission" value="Manuelle"    <?php if ($voiture['transmission']=='Manuelle')    echo 'checked'; ?>> Manuelle</label>
                <label><input type="radio" name="transmission" value="Automatique" <?php if ($voiture['transmission']=='Automatique') echo 'checked'; ?>> Automatique</label>
            </div>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description"><?php echo htmlspecialchars($voiture['description']); ?></textarea>
        </div>
        <div class="form-group">
            <label>Nouvelle Photo <span class="hint">(laisser vide pour garder l'actuelle)</span></label>
            <img src="<?php echo $voiture['path']; ?>" class="car-thumb" style="margin-bottom:8px; display:block;"><br>
            <input type="file" name="photo" accept="image/png, image/jpeg, image/jpg, image/gif">
            <div class="hint">Formats : PNG, JPG, JPEG, GIF. Max : 2 Mo</div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="required">Catégorie</label>
                <select name="category" required>
                    <?php
                    $cats = mysqli_query($connection, "select * from categories order by name");
                    while ($cat = mysqli_fetch_array($cats))
                        echo "<option value='{$cat['id']}'" . ($voiture['idCat'] == $cat['id'] ? " selected" : "") . ">{$cat['name']}</option>";
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label class="required">Disponible</label>
                <div class="radio-group">
                    <label><input type="radio" name="disponible" value="1" <?php if ($voiture['disponible'])  echo 'checked'; ?>> Oui</label>
                    <label><input type="radio" name="disponible" value="0" <?php if (!$voiture['disponible']) echo 'checked'; ?>> Non</label>
                </div>
            </div>
        </div>
        <button type="submit" class="submit-btn">Mettre à Jour</button>
    </form>
</div>
<?php mysqli_close($connection); ?>
</div></body></html>
