<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoLoc - Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-left">
        <div class="car-icon">&#128663;</div>
        <h1>AutoLoc</h1>
        <p>La plateforme de location de voiture simple, rapide et fiable au Maroc.</p>
    </div>
    <div class="auth-right">
        <?php
        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'login';
        ?>
        <h2>Bienvenue</h2>
        <div class="auth-tabs">
            <a href="authForm.php?tab=login"     class="<?php echo $tab == 'login'    ? 'active' : ''; ?>">Connexion</a>
            <a href="authForm.php?tab=register"  class="<?php echo $tab == 'register' ? 'active' : ''; ?>">Inscription</a>
        </div>

        <?php if ($tab == 'login'): ?>
        <form method="post" action="chechAuth.php">
            <div class="form-group">
                <label for="login" class="required">Email</label>
                <input type="email" id="login" name="login" required placeholder="votre@email.com">
            </div>
            <div class="form-group">
                <label for="pass" class="required">Mot de passe</label>
                <input type="password" id="pass" name="pass" required placeholder="••••••••">
            </div>
            <button type="submit" class="submit-btn">Se connecter</button>
        </form>
        <p class="mt-10 text-center" style="font-size:14px; color:#888;">
            Pas encore inscrit ? <a href="authForm.php?tab=register">Créer un compte</a>
        </p>
        <?php else: ?>
        <form method="post" action="registerUser.php">
            <div class="form-row">
                <div class="form-group">
                    <label class="required">Prénom</label>
                    <input type="text" name="prenom" required placeholder="Jean" maxlength="100">
                </div>
                <div class="form-group">
                    <label class="required">Nom</label>
                    <input type="text" name="nom" required placeholder="Dupont" maxlength="100">
                </div>
            </div>
            <div class="form-group">
                <label class="required">Email</label>
                <input type="email" name="email" required placeholder="votre@email.com">
            </div>
            <div class="form-group">
                <label class="required">Mot de passe</label>
                <input type="password" name="pass" required placeholder="Minimum 6 caractères" minlength="6">
            </div>
            <div class="form-group">
                <label class="required">Confirmer le mot de passe</label>
                <input type="password" name="pass2" required placeholder="Répétez le mot de passe">
            </div>
            <button type="submit" class="submit-btn">Créer mon compte</button>
        </form>
        <p class="mt-10 text-center" style="font-size:14px; color:#888;">
            Déjà inscrit ? <a href="authForm.php?tab=login">Se connecter</a>
        </p>
        <?php endif; ?>

        <?php
        if (isset($_GET['auth']))
            switch ($_GET['auth']) {
                case 'false':    echo "<p class='message-err'>Email ou mot de passe incorrect.</p>"; break;
                case 'nonAuth':  echo "<p class='message-err'>Vous devez être connecté pour accéder à cette page.</p>"; break;
                case 'again':    echo "<p class='message-err'>Merci de vous reconnecter.</p>"; break;
                case 'role':     echo "<p class='message-err'>Accès non autorisé pour votre rôle.</p>"; break;
                case 'exists':   echo "<p class='message-err'>Cet email est déjà utilisé.</p>"; break;
                case 'passmatch':echo "<p class='message-err'>Les mots de passe ne correspondent pas.</p>"; break;
            }
        if (isset($_GET['msg']) && $_GET['msg'] == 'registered')
            echo "<p class='message-ok'>Compte créé avec succès ! Vous pouvez vous connecter.</p>";
        ?>
    </div>
</div>
</body>
</html>
