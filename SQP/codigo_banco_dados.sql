CREATE DATABASE AgendaFest;
USE AgendaFest;

CREATE TABLE Usuarios
(
    nome varchar(100),    
    email varchar(100) primary key,
    senha varchar(255)
);

CREATE TABLE eventos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT,
    data_evento DATE NOT NULL,
    hora_evento TIME NOT NULL,
    local VARCHAR(255),
    categoria VARCHAR(100),
    imagem VARCHAR(255),
    usuario_email VARCHAR(100),
    FOREIGN KEY (usuario_email) REFERENCES usuarios(email)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evento_id INT NOT NULL,
    usuario_email VARCHAR(255) NOT NULL,
    comentario TEXT NOT NULL,
    data_comentario DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (evento_id) REFERENCES eventos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_email) REFERENCES usuarios(email) ON DELETE CASCADE
);

ALTER TABLE eventos ADD COLUMN visibilidade VARCHAR(10) DEFAULT 'publico';
