# AutoLoc 🚗

Application web de gestion de location de voitures, développée en PHP/MySQL avec XAMPP.

## Fonctionnalités

**Administrateur**
- Tableau de bord : statistiques (voitures, réservations, revenus, clients)
- Gestion des voitures : ajout, modification, suppression, photo
- Gestion des catégories : Citadine, Berline, SUV, Luxe…
- Gestion des réservations : confirmation, annulation, historique
- Gestion des clients

**Client**
- Inscription / Connexion
- Catalogue des voitures disponibles avec filtres
- Réservation de voitures (dates de début/fin)
- Suivi de ses réservations

## Stack technique

- **Backend** : PHP 8 (procédural)
- **Base de données** : MySQL (via MySQLi)
- **Frontend** : HTML/CSS vanilla
- **Serveur local** : XAMPP (Apache + MySQL)

## Installation

1. Cloner le dépôt dans `htdocs` :
   ```bash
   git clone https://github.com/Saman-hero/location-voiture-v2.git
   ```

2. Créer le fichier `dbconnection.php` (non inclus pour des raisons de sécurité) :
   ```php
   <?php
   define('HOST', 'localhost');
   define('USER', 'root');
   define('PASS', '');
   define('PORT', 3306);
   define('DB', 'locationvoiture2');

   $connection = mysqli_connect(HOST, USER, PASS, DB);
   if ($connection == false) { echo "Erreur de connexion à la base de données"; exit(1); }
   ?>
   ```

3. Importer la base de données depuis phpMyAdmin ou en ligne de commande :
   ```bash
   mysql -u root -p < schema.sql
   ```

4. Accéder à l'application : `http://localhost/location-voiture-v2/`

## Comptes de test

| Rôle          | Email                  | Mot de passe |
|---------------|------------------------|--------------|
| Administrateur | admin@autoloc.ma      | admin123     |
| Client         | jean@client.ma        | client123    |

## Structure du projet

```
├── authForm.php            # Page connexion / inscription
├── dashboard.php           # Tableau de bord admin
├── allVoitures.php         # Liste des voitures
├── VoitureForm.php         # Formulaire ajout voiture
├── editVoiture.php         # Modification voiture
├── allReservations.php     # Liste des réservations
├── showReservationDetail.php # Détail d'une réservation
├── allClients.php          # Gestion des clients
├── allCategories.php       # Gestion des catégories
├── schema.sql              # Structure BDD + données de test
├── style.css               # Feuille de style globale
├── dbconnection.php        # Connexion BDD (à créer, non versionné)
└── images/                 # Icônes de l'interface
```
