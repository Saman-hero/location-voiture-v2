CREATE DATABASE IF NOT EXISTS locationvoiture2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE locationvoiture2;

CREATE TABLE IF NOT EXISTS users (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    nom    VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email  VARCHAR(150) NOT NULL UNIQUE,
    pass   VARCHAR(32)  NOT NULL,
    role   ENUM('AD', 'CL') DEFAULT 'CL'
);

CREATE TABLE IF NOT EXISTS categories (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS voitures (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    marque       VARCHAR(100) NOT NULL,
    modele       VARCHAR(100) NOT NULL,
    annee        INT NOT NULL,
    prix_jour    DECIMAL(10,2) NOT NULL,
    carburant    ENUM('Essence', 'Diesel', 'Electrique', 'Hybride') DEFAULT 'Essence',
    transmission ENUM('Manuelle', 'Automatique') DEFAULT 'Manuelle',
    places       INT DEFAULT 5,
    description  TEXT,
    disponible   TINYINT(1) DEFAULT 1,
    path         VARCHAR(255) DEFAULT 'photos/nopicture.jpeg',
    idCat        INT,
    FOREIGN KEY (idCat) REFERENCES categories(id)
);

CREATE TABLE IF NOT EXISTS reservations (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    date_creation DATETIME NOT NULL,
    email_client  VARCHAR(150) NOT NULL,
    statut        ENUM('en_attente','confirmee','annulee','terminee') DEFAULT 'confirmee'
);

CREATE TABLE IF NOT EXISTS details_reservation (
    idReservation INT  NOT NULL,
    idVoiture     INT  NOT NULL,
    date_debut    DATE NOT NULL,
    date_fin      DATE NOT NULL,
    nb_jours      INT  NOT NULL,
    PRIMARY KEY (idReservation, idVoiture),
    FOREIGN KEY (idReservation) REFERENCES reservations(id) ON DELETE CASCADE,
    FOREIGN KEY (idVoiture)     REFERENCES voitures(id)
);

-- ===================== DONNÉES DE TEST =====================

INSERT INTO users (nom, prenom, email, pass, role) VALUES
('Admin',   'System', 'admin@autoloc.ma',  MD5('admin123'), 'AD'),
('Dupont',  'Jean',   'jean@client.ma',    MD5('client123'), 'CL'),
('Alaoui',  'Fatima', 'fatima@client.ma',  MD5('client123'), 'CL'),
('Benali',  'Karim',  'karim@client.ma',   MD5('client123'), 'CL'),
('Martin',  'Sophie', 'sophie@client.ma',  MD5('client123'), 'CL');

INSERT INTO categories (name) VALUES
('Citadine'), ('Berline'), ('SUV'), ('Utilitaire'), ('Luxe'), ('Monospace');

INSERT INTO voitures (marque, modele, annee, prix_jour, carburant, transmission, places, description, disponible, idCat) VALUES
('Renault',  'Clio',      2022, 250.00, 'Essence',   'Manuelle',    5, 'Citadine économique et confortable', 1, 1),
('Peugeot',  '308',       2023, 350.00, 'Diesel',    'Manuelle',    5, 'Berline spacieuse idéale pour les familles', 1, 2),
('Dacia',    'Duster',    2022, 400.00, 'Diesel',    'Manuelle',    5, 'SUV robuste tout-terrain', 1, 3),
('Mercedes', 'Classe C',  2023, 800.00, 'Essence',   'Automatique', 5, 'Berline de luxe haut de gamme', 1, 5),
('Toyota',   'Yaris',     2023, 220.00, 'Hybride',   'Automatique', 5, 'Citadine hybride économique', 1, 1),
('Ford',     'Transit',   2021, 600.00, 'Diesel',    'Manuelle',    9, 'Utilitaire spacieux pour déplacements professionnels', 1, 4),
('BMW',      'X5',        2023, 950.00, 'Diesel',    'Automatique', 5, 'SUV premium avec toutes les options', 1, 3),
('Volkswagen','Golf',     2022, 300.00, 'Essence',   'Manuelle',    5, 'Berline fiable et polyvalente', 1, 2),
('Hyundai',  'Tucson',    2023, 450.00, 'Hybride',   'Automatique', 5, 'SUV hybride moderne et confortable', 1, 3),
('Tesla',    'Model 3',   2023,1200.00, 'Electrique','Automatique', 5, 'Berline électrique haut de gamme', 1, 5),
('Renault',  'Kangoo',    2021, 350.00, 'Diesel',    'Manuelle',    5, 'Utilitaire compact pratique', 0, 4),
('Citroën',  'C3',        2022, 230.00, 'Essence',   'Manuelle',    5, 'Petite citadine agréable', 1, 1),
('Kia',      'Sportage',  2023, 480.00, 'Hybride',   'Automatique', 5, 'SUV coréen moderne et fiable', 1, 3),
('Peugeot',  '5008',      2022, 550.00, 'Diesel',    'Automatique', 7, 'Grand SUV familial 7 places', 1, 6),
('Audi',     'A4',        2023, 750.00, 'Essence',   'Automatique', 5, 'Berline premium allemande', 1, 5);

INSERT INTO reservations (date_creation, email_client, statut) VALUES
('2026-05-10 10:30:00', 'jean@client.ma',   'terminee'),
('2026-05-18 14:00:00', 'fatima@client.ma', 'terminee'),
('2026-06-01 09:15:00', 'karim@client.ma',  'confirmee'),
('2026-06-10 11:45:00', 'jean@client.ma',   'confirmee'),
('2026-06-13 16:20:00', 'sophie@client.ma', 'confirmee'),
('2026-06-14 08:00:00', 'fatima@client.ma', 'en_attente'),
('2026-06-15 10:00:00', 'karim@client.ma',  'annulee');

INSERT INTO details_reservation (idReservation, idVoiture, date_debut, date_fin, nb_jours) VALUES
(1, 1, '2026-05-10', '2026-05-15', 5),
(1, 5, '2026-05-10', '2026-05-13', 3),
(2, 3, '2026-05-18', '2026-05-22', 4),
(3, 7, '2026-06-05', '2026-06-10', 5),
(4, 2, '2026-06-12', '2026-06-15', 3),
(5, 9, '2026-06-15', '2026-06-18', 3),
(6, 4, '2026-06-20', '2026-06-25', 5),
(7, 1, '2026-06-16', '2026-06-18', 2);
