CREATE TABLE Plat 
(
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    photo VARCHAR(50) NOT NULL,
    asset VARCHAR(50) NOT NULL,
    prix INT NOT NULL,
    tempsCuisson INT NOT NULL,
);

CREATE TABLE Ingredient 
(
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    photo VARCHAR(50) NOT NULL,
    asset VARCHAR(50) NOT NULL,
);

CREATE TABLE PlatIngredient 
(
    id SERIAL PRIMARY KEY,
    idPlat INT NOT NULL,
    idIngredient INT NOT NULL,
    nombre INT NOT NULL,
    FOREIGN KEY (idPlat) REFERENCES Plat (id),
    FOREIGN KEY (idIngredient) REFERENCES Ingredient (id)
);

CREATE TABLE Client 
(
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    mdp VARCHAR(50) NOT NULL,
    photo VARCHAR(50) NOT NULL
);

CREATE TABLE Latabatra 
(
    id INT NOT NULL,
    nombrePlace INT NOT NULL,
);

CREATE TABLE Place 
(
    id SERIAL PRIMARY KEY,
);

CREATE TABLE PlaceClient
(
    id SERIAL PRIMARY KEY,
    idClient INT NOT NULL,
    idLatabatra INT NOT NULL,
    idPlace INT NOT NULL,
    FOREIGN KEY (idLatabatra) REFERENCES Latabatra(id),
    FOREIGN KEY (idPlace) REFERENCES Place(id),
    FOREIGN KEY (idClient) REFERENCES Client(id),
);

CREATE TABLE Commande 
(
    id SERIAL PRIMARY KEY,
    idClient INT NOT NULL,
    idPlat INT NOT NULL,
    FOREIGN KEY (idClient) REFERENCES Client(id),
    FOREIGN KEY (idPlat) REFERENCES Plat (id)
);

CREATE TABLE PlatCuit 
(
    id SERIAL PRIMARY KEY,
    idClient INT NOT NULL,
    idPlat INT NOT NULL,
    FOREIGN KEY (idClient) REFERENCES Client(id),
    FOREIGN KEY (idPlat) REFERENCES Plat (id)
);

CREATE TABLE PlatLivrer 
(
    id SERIAL PRIMARY KEY,
    idClient INT NOT NULL,
    idPlat INT NOT NULL,
    FOREIGN KEY (idClient) REFERENCES Client(id),
    FOREIGN KEY (idPlat) REFERENCES Plat (id)
);

CREATE TABLE Vente 
(
    id SERIAL PRIMARY KEY,
    idClient INT NOT NULL,
    idPlat INT NOT NULL,
    dateAchat date,
    FOREIGN KEY (idClient) REFERENCES Client(id),
    FOREIGN KEY (idPlat) REFERENCES Plat (id)
);
