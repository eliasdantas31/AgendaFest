CREATE DATABASE AgendaFest;
USE AgendaFest;

CREATE TABLE Usuarios
(
    nome varchar(100),    
    email varchar(100) primary key,
    senha varchar(255)
);