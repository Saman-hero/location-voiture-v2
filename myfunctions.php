<?php
function calculerNbJours(string $date_debut, string $date_fin): int
{
    $d1 = new DateTime($date_debut);
    $d2 = new DateTime($date_fin);
    $diff = $d1->diff($d2);
    return max(1, $diff->days);
}

function calculerTotal(float $prix_jour, string $date_debut, string $date_fin): float
{
    return $prix_jour * calculerNbJours($date_debut, $date_fin);
}

function formatPrix(float $montant): string
{
    return number_format($montant, 2, '.', ' ') . ' MAD';
}

function formatDate(string $date): string
{
    $d = new DateTime($date);
    return $d->format('d/m/Y');
}

function statutBadge(string $statut): string
{
    $map = [
        'en_attente'  => ['label' => 'En attente',  'class' => 'badge-warning'],
        'confirmee'   => ['label' => 'Confirmée',   'class' => 'badge-success'],
        'annulee'     => ['label' => 'Annulée',     'class' => 'badge-danger'],
        'terminee'    => ['label' => 'Terminée',    'class' => 'badge-secondary'],
    ];
    $s = $map[$statut] ?? ['label' => $statut, 'class' => 'badge-secondary'];
    return "<span class='badge {$s['class']}'>{$s['label']}</span>";
}
?>
