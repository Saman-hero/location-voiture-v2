<?php
// Include this file after checkSession.php to render the sidebar + page wrapper start
$currentPage = basename($_SERVER['PHP_SELF']);
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] == 'AD';

function navLink(string $href, string $label, string $page, string $current): string
{
    $active = ($current == $page) ? ' class="active"' : '';
    return "<a href='$href'$active>$label</a>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoLoc</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="page-wrapper">
<nav class="sidebar">
    <div class="sidebar-logo">Auto<span>Loc</span></div>
    <div class="sidebar-user">
        Bonjour, <strong><?php echo htmlspecialchars($_SESSION['prenom'] ?? $_SESSION['user']); ?></strong><br>
        <small><?php echo $isAdmin ? 'Administrateur' : 'Client'; ?></small>
    </div>
    <div class="sidebar-nav">
        <?php if ($isAdmin): ?>
            <?php echo navLink('dashboard.php',       '&#9632; Tableau de bord', 'dashboard.php',       $currentPage); ?>
            <div class="nav-divider"></div>
            <?php echo navLink('allVoitures.php',     '&#9632; Voitures',        'allVoitures.php',     $currentPage); ?>
            <?php echo navLink('allCategories.php',   '&#9632; Catégories',      'allCategories.php',   $currentPage); ?>
            <div class="nav-divider"></div>
            <?php echo navLink('allReservations.php', '&#9632; Réservations',    'allReservations.php', $currentPage); ?>
            <?php echo navLink('allClients.php',      '&#9632; Clients',         'allClients.php',      $currentPage); ?>
        <?php else: ?>
            <?php echo navLink('allVoitures.php',     '&#9632; Nos Voitures',    'allVoitures.php',     $currentPage); ?>
            <?php echo navLink('showReservation.php', '&#9632; Mon Panier',      'showReservation.php', $currentPage); ?>
            <?php echo navLink('allReservations.php', '&#9632; Mes Réservations','allReservations.php', $currentPage); ?>
        <?php endif; ?>
        <div class="nav-divider"></div>
        <?php echo navLink('profil.php', '&#9632; Mon Profil', 'profil.php', $currentPage); ?>
    </div>
    <div class="sidebar-bottom">
        <a href="disconnect.php">&#9632; Déconnexion</a>
    </div>
</nav>
<div class="main-content">
