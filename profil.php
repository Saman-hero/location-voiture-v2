<?php
require 'checkSession.php';
require 'dbconnection.php';
require 'myfunctions.php';
require 'navbar.php';

$email  = mysqli_real_escape_string($connection, $_SESSION['user']);
$result = mysqli_query($connection, "select * from users where email='$email'");
$user   = mysqli_fetch_array($result);

$totalReserv = mysqli_fetch_array(mysqli_query($connection,
    "select count(*) as n from reservations where email_client='$email'"))['n'];
?>
<div class="top-bar">
    <h1>Mon Profil</h1>
</div>

<?php if (isset($_GET['msg'])): ?>
<div class="alert alert-<?php echo $_GET['msg'] == 'ok' ? 'success' : 'danger'; ?>">
    <?php echo $_GET['msg'] == 'ok' ? 'Profil mis à jour avec succès.' : 'Mot de passe actuel incorrect.'; ?>
</div>
<?php endif; ?>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:22px; align-items:start;">

<div class="profile-card">
    <div class="profile-header">
        <div class="profile-avatar">
            <?php echo strtoupper(substr($user['prenom'], 0, 1) . substr($user['nom'], 0, 1)); ?>
        </div>
        <div>
            <h2><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></h2>
            <p><?php echo htmlspecialchars($user['email']); ?></p>
            <p><?php echo $user['role'] == 'AD' ? 'Administrateur' : 'Client'; ?></p>
        </div>
    </div>
    <div class="profile-body">
        <?php if ($user['role'] == 'CL'): ?>
        <div class="stat-card green" style="border-radius:8px;">
            <span class="stat-label">Mes Réservations</span>
            <span class="stat-value"><?php echo $totalReserv; ?></span>
        </div>
        <?php endif; ?>
    </div>
</div>

<div>
    <div class="form-container" style="margin:0;">
        <h2 style="color:var(--primary); margin-bottom:20px;">Modifier mes informations</h2>
        <form method="post" action="updateProfil.php">
            <div class="form-row">
                <div class="form-group">
                    <label class="required">Prénom</label>
                    <input type="text" name="prenom" required value="<?php echo htmlspecialchars($user['prenom']); ?>" maxlength="100">
                </div>
                <div class="form-group">
                    <label class="required">Nom</label>
                    <input type="text" name="nom" required value="<?php echo htmlspecialchars($user['nom']); ?>" maxlength="100">
                </div>
            </div>
            <div class="divider"></div>
            <h3 style="margin-bottom:14px; color:#555; font-size:15px;">Changer le mot de passe <span class="hint">(optionnel)</span></h3>
            <div class="form-group">
                <label>Mot de passe actuel</label>
                <input type="password" name="pass_actuel" placeholder="Requis pour changer le mot de passe">
            </div>
            <div class="form-group">
                <label>Nouveau mot de passe</label>
                <input type="password" name="pass_new" placeholder="Laisser vide pour ne pas changer" minlength="6">
            </div>
            <div class="form-group">
                <label>Confirmer nouveau mot de passe</label>
                <input type="password" name="pass_new2" placeholder="Répétez le nouveau mot de passe">
            </div>
            <button type="submit" class="submit-btn">Enregistrer les modifications</button>
        </form>
    </div>
</div>

</div>
<?php mysqli_close($connection); ?>
</div></body></html>
