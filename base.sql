
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE administrateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
);

CREATE TABLE classement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    points INT DEFAULT 0,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
);

CREATE TABLE sorties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    km DECIMAL(5, 2) NOT NULL,
    nom VARCHAR(255) NOT NULL,
    code_course VARCHAR(10) NOT NULL,
    code_compense VARCHAR(10) NOT NULL,
    image VARCHAR(255),
    date DATE NOT NULL
);

CREATE TABLE participations (
    utilisateur_id INT,
    sortie_id INT,
    compense BOOLEAN,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (sortie_id) REFERENCES sorties(id) );
