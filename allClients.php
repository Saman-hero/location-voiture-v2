<?php
require 'checkSession.php';
if ($_SESSION['role'] != 'AD') header('location:authForm.php?auth=role');
require 'dbconnection.php';
require 'navbar.php';

$search = '';
if (!empty($_GET['s'])) {
    $s = mysqli_real_escape_string($connection, $_GET['s']);
    $search = "where u.role='CL' and (u.nom like '%$s%' or u.prenom like '%$s%' or u.email like '%$s%')";
} else {
    $search = "where u.role='CL'";
}

$result = mysqli_query($connection,
    "select u.id, u.nom, u.prenom, u.email,
            count(r.id) as nb_reservations
     from users u
     left join reservations r on u.email = r.email_client
     $search
     group by u.id
     order by u.nom, u.prenom");
?>
<div class="top-bar">
    <h1>Gestion des Clients</h1>
    <span><?php echo mysqli_num_rows($result); ?> client(s)</span>
</div>

<form method="get" class="search-bar">
    <input type="text" name="s" placeholder="Rechercher un client..." value="<?php echo htmlspecialchars($_GET['s'] ?? ''); ?>">
    <button type="submit" class="btn btn-primary">Rechercher</button>
    <a href="allClients.php" class="btn btn-secondary">Réinitialiser</a>
</form>

<?php if (mysqli_num_rows($result) == 0): ?>
<div class="empty-state"><p>Aucun client trouvé.</p></div>
<?php else: ?>
<table>
    <tr>
        <th>#</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Email</th>
        <th>Réservations</th>
        <th>Actions</th>
    </tr>
    <?php while ($client = mysqli_fetch_array($result)): ?>
    <tr>
        <td><?php echo $client['id']; ?></td>
        <td><?php echo htmlspecialchars($client['nom']); ?></td>
        <td><?php echo htmlspecialchars($client['prenom']); ?></td>
        <td><?php echo htmlspecialchars($client['email']); ?></td>
        <td><?php echo $client['nb_reservations']; ?></td>
        <td class="table-actions">
            <a href="showClient.php?id=<?php echo $client['id']; ?>" class="btn btn-primary btn-sm">Voir</a>
            <a href="deleteClient.php?id=<?php echo $client['id']; ?>"
               class="btn btn-danger btn-sm"
               onclick="return confirm('Supprimer ce client ?')">Supprimer</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php endif; ?>
<?php mysqli_close($connection); ?>
</div></body></html>
