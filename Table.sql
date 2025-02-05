CREATE TABLE Recette 
(
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    photo VARCHAR(255) NOT NULL,
    assets VARCHAR(255) NOT NULL,
    prix INT NOT NULL,
    tempsCuisson INT NOT NULL,
);

CREATE TABLE Stock 
(
    id SERIAL PRIMARY KEY,
    idIngredient INT,
    nombre INT,
    FOREIGN KEY (idIngredient) REFERENCES Ingredient(id)
);
CREATE TABLE Ingredient 
(
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    photo VARCHAR(255) NOT NULL,
    assets VARCHAR(255) NOT NULL,
);

CREATE TABLE RecetteIngredient 
(
    id SERIAL PRIMARY KEY,
    idRecette INT NOT NULL,
    idIngredient INT NOT NULL,
    nombre INT NOT NULL,
    FOREIGN KEY (idRecette) REFERENCES Recette (id),
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
    idRecette INT NOT NULL,
    FOREIGN KEY (idClient) REFERENCES Client(id),
    FOREIGN KEY (idRecette) REFERENCES Plat (id)
);

CREATE TABLE PlatCuit 
(
    id SERIAL PRIMARY KEY,
    idClient INT NOT NULL,
    idRecette INT NOT NULL,
    FOREIGN KEY (idClient) REFERENCES Client(id),
    FOREIGN KEY (idRecette) REFERENCES Recette (id)
);

CREATE TABLE PlatLivrer 
(
    id SERIAL PRIMARY KEY,
    idClient INT NOT NULL,
    idRecette INT NOT NULL,
    FOREIGN KEY (idClient) REFERENCES Client(id),
    FOREIGN KEY (idRecette) REFERENCES Recette (id)
);

CREATE TABLE Vente 
(
    id SERIAL PRIMARY KEY,
    idClient INT NOT NULL,
    idRecette INT NOT NULL,
    dateAchat date,
    FOREIGN KEY (idClient) REFERENCES Client(id),
    FOREIGN KEY (idRecette) REFERENCES Recette (id)
);


    (SID_DESC =
      (SID_NAME = orcl)        
      (ORACLE_HOME = C:\app\74677647\product\11.2.0\dbhome_3)  
    )