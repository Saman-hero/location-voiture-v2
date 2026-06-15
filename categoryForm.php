<?php
require 'checkSession.php';
if ($_SESSION['role'] != 'AD') header('location:authForm.php?auth=role');
require 'dbconnection.php';
require 'navbar.php';

$edit = false;
$cat  = ['id' => null, 'name' => ''];

if (isset($_GET['id'])) {
    $id   = (int)$_GET['id'];
    $res  = mysqli_query($connection, "select * from categories where id=$id");
    $cat  = mysqli_fetch_array($res);
    $edit = true;
}
?>
<div class="top-bar">
    <h1><?php echo $edit ? 'Modifier la Catégorie' : 'Ajouter une Catégorie'; ?></h1>
    <a href="allCategories.php" class="btn btn-secondary btn-sm">&#8592; Retour</a>
</div>

<div class="form-container">
    <form method="post" action="saveCategory.php">
        <?php if ($edit): ?>
            <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
        <?php endif; ?>
        <div class="form-group">
            <label class="required">Nom de la catégorie</label>
            <input type="text" name="name" required maxlength="100"
                   value="<?php echo htmlspecialchars($cat['name']); ?>"
                   placeholder="Ex: SUV, Berline...">
        </div>
        <button type="submit" class="submit-btn">
            <?php echo $edit ? 'Mettre à Jour' : 'Ajouter'; ?>
        </button>
    </form>
</div>
<?php mysqli_close($connection); ?>
</div></body></html>
