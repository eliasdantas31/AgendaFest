CREATE DATABASE AgendaFest;
USE AgendaFest;

CREATE TABLE Usuarios
(
    nome varchar(100),    
    email varchar(100) primary key,
    senha varchar(255)
);

CREATE TABLE Eventos
(
    id int primary key auto_increment,
    titulo varchar(100),
    data datetime,
    descricao text
);