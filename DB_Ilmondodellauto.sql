CREATE DATABASE Concessionario;

USE Concessionario;

CREATE TABLE UTENTE (
    ID INT NOT NULL AUTO_INCREMENT,
    Nome VARCHAR(255) NOT NULL,
    Cognome VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL,
    Username VARCHAR(255) NOT NULL,
    Telefono VARCHAR(20),
    Password VARCHAR(255) NOT NULL,
    Ruolo ENUM('utente', 'admin') DEFAULT 'utente',
    PRIMARY KEY (ID),
    UNIQUE (Email),
    UNIQUE (Username),
    CHECK (Email LIKE '%@%.%')
);

CREATE TABLE MACCHINA (
    ID INT NOT NULL AUTO_INCREMENT,
    Marca VARCHAR(255) NOT NULL,
    Modello VARCHAR(255) NOT NULL,
    Anno SMALLINT NOT NULL,
    Stato ENUM('Disponibile', 'Prenotata', 'Venduta') DEFAULT 'Disponibile',
    Cilindrata DECIMAL(5,1) NOT NULL,
    PotenzaKw INT NOT NULL,
    Cavalli INT NOT NULL,
    Chilometraggio INT NOT NULL,
    Carrozzeria ENUM('Berlina', 'Due Volumi', 'Station Wagon', 'SUV', 'City Car', 'Monovolume', 'Cabrio', 'Furgonato', 'Bus', 'Pick Up', 'Utilitaria'),
    ColoreInterni VARCHAR(50),
    TipoVeicolo ENUM('Usato', 'Nuovo', 'Km Zero'),
    Neopatentati BOOLEAN DEFAULT FALSE,
    Targa VARCHAR(10) NOT NULL,
    Descrizione TEXT,
    Prezzo DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (ID),
    UNIQUE (Targa),
    CHECK (Cilindrata > 0),
    CHECK (PotenzaKw > 0),
    CHECK (Cavalli > 0),
    CHECK (Chilometraggio >= 0),
    CHECK (Prezzo > 0)
);

CREATE TABLE MACCHINA_IMMAGINI (
    ID INT NOT NULL AUTO_INCREMENT,
    ID_Macchina INT NOT NULL,
    URL VARCHAR(255) NOT NULL,
    Ordine INT NOT NULL,
    PRIMARY KEY (ID),
    CHECK (Ordine >= 0),
    FOREIGN KEY (ID_Macchina) REFERENCES MACCHINA(ID) ON DELETE CASCADE
);

CREATE TABLE ACCESSORI (
    ID INT NOT NULL AUTO_INCREMENT,
    Nome VARCHAR(255) NOT NULL,
    Categoria ENUM('Sicurezza', 'Comfort', 'Estetica', 'Tecnologia', 'Altro'),
    PRIMARY KEY (ID)
);

CREATE TABLE MACCHINA_ACCESSORI (
    ID_Macchina INT NOT NULL,
    ID_Accessorio INT NOT NULL,
    PRIMARY KEY (ID_Macchina, ID_Accessorio),
    FOREIGN KEY (ID_Macchina) REFERENCES MACCHINA(ID) ON DELETE CASCADE,
    FOREIGN KEY (ID_Accessorio) REFERENCES ACCESSORI(ID) ON DELETE CASCADE
);

CREATE TABLE RECENSIONI (
    ID INT NOT NULL AUTO_INCREMENT,
    DataOraPubblicazione DATETIME NOT NULL,
    Valutazione DECIMAL(2,1) NOT NULL,
    Testo TEXT,
    ID_Utente INT NOT NULL,
    ID_Macchina INT NOT NULL,

    PRIMARY KEY (ID),
    CHECK (Valutazione >= 1 AND Valutazione <= 5),
    FOREIGN KEY (ID_Utente) REFERENCES UTENTE(ID),
    FOREIGN KEY (ID_Macchina) REFERENCES MACCHINA(ID) ON DELETE CASCADE
);

CREATE TABLE PRENOTAZIONE (
    ID INT NOT NULL AUTO_INCREMENT,
    ID_Utente INT NOT NULL,
    ID_Macchina INT NOT NULL,
    TipoPrenotazione ENUM('Test Drive', 'Acquisto', 'Visita'),
    DataOraPrenotazione DATETIME NOT NULL,
    Stato ENUM('In attesa', 'Confermata', 'Annullata', 'Completata') DEFAULT 'In attesa',
    PRIMARY KEY (ID),
    FOREIGN KEY (ID_Utente) REFERENCES UTENTE(ID),
    FOREIGN KEY (ID_Macchina) REFERENCES MACCHINA(ID) ON DELETE CASCADE
);

CREATE TABLE MACCHINE_SALVATE (
    ID_Utente INT NOT NULL,
    ID_Macchina INT NOT NULL,
    PRIMARY KEY (ID_Utente, ID_Macchina),
    FOREIGN KEY (ID_Utente) REFERENCES UTENTE(ID) ON DELETE CASCADE,
    FOREIGN KEY (ID_Macchina) REFERENCES MACCHINA(ID) ON DELETE CASCADE
);

ALTER TABLE utente ADD COLUMN Ruolo ENUM('utente','admin') DEFAULT 'utente';
ALTER TABLE recensioni ADD COLUMN Testo TEXT;