<?php
require 'checkSession.php';
if ($_SESSION['role'] != 'AD') header('location:authForm.php?auth=role');
require 'dbconnection.php';
require 'navbar.php';
?>
<div class="top-bar">
    <h1>Ajouter une Voiture</h1>
    <a href="allVoitures.php" class="btn btn-secondary btn-sm">&#8592; Retour</a>
</div>

<div class="form-container">
    <?php
    if (isset($_GET['error']))
        switch ($_GET['error']) {
            case 'typephoto': echo "<div class='alert alert-danger'>Type de fichier non autorisé.</div>"; break;
            case 'sizefile':  echo "<div class='alert alert-danger'>Taille maximale : 2 Mo.</div>"; break;
        }
    ?>
    <form action="addVoiture.php" method="post" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label class="required">Marque</label>
                <input type="text" name="marque" required placeholder="Ex: Renault" maxlength="100">
            </div>
            <div class="form-group">
                <label class="required">Modèle</label>
                <input type="text" name="modele" required placeholder="Ex: Clio" maxlength="100">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="required">Année</label>
                <input type="number" name="annee" required min="1990" max="2030" placeholder="2024">
            </div>
            <div class="form-group">
                <label class="required">Prix/Jour (MAD)</label>
                <input type="number" name="prix_jour" required step="0.01" min="0" placeholder="0.00">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="required">Carburant</label>
                <select name="carburant" required>
                    <?php foreach (['Essence','Diesel','Electrique','Hybride'] as $c)
                        echo "<option value='$c'>$c</option>"; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="required">Nombre de Places</label>
                <input type="number" name="places" required min="2" max="9" value="5">
            </div>
        </div>
        <div class="form-group">
            <label class="required">Transmission</label>
            <div class="radio-group">
                <label><input type="radio" name="transmission" value="Manuelle" checked> Manuelle</label>
                <label><input type="radio" name="transmission" value="Automatique"> Automatique</label>
            </div>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" placeholder="Description de la voiture..."></textarea>
        </div>
        <div class="form-group">
            <label>Photo</label>
            <input type="file" name="photo" accept="image/png, image/jpeg, image/jpg, image/gif">
            <div class="hint">Formats : PNG, JPG, JPEG, GIF. Max : 2 Mo</div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="required">Catégorie</label>
                <select name="category" required>
                    <option value="">Choisir une catégorie</option>
                    <?php
                    $cats = mysqli_query($connection, "select * from categories order by name");
                    while ($cat = mysqli_fetch_array($cats))
                        echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label class="required">Disponible</label>
                <div class="radio-group">
                    <label><input type="radio" name="disponible" value="1" checked> Oui</label>
                    <label><input type="radio" name="disponible" value="0"> Non</label>
                </div>
            </div>
        </div>
        <button type="submit" class="submit-btn">Ajouter la Voiture</button>
    </form>
</div>
<?php mysqli_close($connection); ?>
</div></body></html>
