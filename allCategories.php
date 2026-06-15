<?php
require 'checkSession.php';
if ($_SESSION['role'] != 'AD') header('location:authForm.php?auth=role');
require 'dbconnection.php';
require 'navbar.php';

$result = mysqli_query($connection,
    "select c.id, c.name, count(v.id) as nb_voitures
     from categories c
     left join voitures v on c.id = v.idCat
     group by c.id, c.name
     order by c.name");
?>
<div class="top-bar">
    <h1>Gestion des Catégories</h1>
    <a href="categoryForm.php" class="btn btn-success btn-sm">+ Ajouter</a>
</div>

<?php if (isset($_GET['msg'])): ?>
<div class="alert alert-<?php echo $_GET['msg'] == 'ok' ? 'success' : 'danger'; ?>">
    <?php echo $_GET['msg'] == 'ok' ? 'Opération effectuée.' : 'Impossible de supprimer (voitures associées).'; ?>
</div>
<?php endif; ?>

<table>
    <tr><th>#</th><th>Nom</th><th>Voitures</th><th>Actions</th></tr>
    <?php while ($cat = mysqli_fetch_array($result)): ?>
    <tr>
        <td><?php echo $cat['id']; ?></td>
        <td><?php echo htmlspecialchars($cat['name']); ?></td>
        <td><?php echo $cat['nb_voitures']; ?></td>
        <td class="table-actions">
            <a href="categoryForm.php?id=<?php echo $cat['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
            <a href="deleteCategory.php?id=<?php echo $cat['id']; ?>"
               class="btn btn-danger btn-sm"
               onclick="return confirm('Supprimer cette catégorie ?')">Supprimer</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php mysqli_close($connection); ?>
</div></body></html>
