drop database musicalize;
CREATE DATABASE musicalize;
USE musicalize;

CREATE TABLE musicos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    instrumento VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status BOOLEAN DEFAULT 1,
    telefone VARCHAR(20) DEFAULT NULL
);

CREATE TABLE eventos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(100) NOT NULL,
    data DATE,
    horario TIME,
    tipo VARCHAR(50),
    observacoes TEXT
);

CREATE TABLE tons(
	id VARCHAR(3) PRIMARY KEY,
    descricao VARCHAR(50) NOT NULL
);

INSERT INTO tons (id, descricao) VALUES 
    ('C', 'C (Dó)'),
    ('C#', 'C# (Dó#)'),
    ('D', 'D (Ré)'),
    ('D#', 'D# (Ré#)'),
    ('E', 'E (Mi)'),
    ('F', 'F (Fá)'),
    ('F#', 'F# (Fá#)'),
    ('G', 'G (Sol)'),
    ('G#', 'G# (Sol#)'),
    ('A', 'A (Lá)'),
    ('A#', 'A# (Lá#)'),
    ('B', 'B (Si)'),
    ('Cm', 'Cm (Dó menor)'),
    ('C#m', 'C#m (Dó# menor)'),
    ('Dm', 'Dm (Ré menor)'),
    ('D#m', 'D#m (Ré# menor)'),
    ('Em', 'Em (Mi menor)'),
    ('Fm', 'Fm (Fá menor)'),
    ('F#m', 'F#m (Fá# menor)'),
    ('Gm', 'Gm (Sol menor)'),
    ('G#m', 'G#m (Sol# menor)'),
    ('Am', 'Am (Lá menor)'),
    ('A#m', 'A#m (Lá# menor)'),
    ('Bm', 'Bm (Si menor)');

CREATE TABLE escalas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    evento_id INT NOT NULL,
    dataEscala DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (evento_id) REFERENCES eventos(id)
);

CREATE TABLE escala_musicos(
	escala_id INT NOT NULL,
    musico_id INT NOT NULL,
    PRIMARY KEY (escala_id, musico_id),
    foreign key (escala_id) references escalas(id),
    foreign key (musico_id) references musicos(id)
);

CREATE TABLE musicas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(100) NOT NULL,
    artista VARCHAR(100),
    duracao VARCHAR(20),
    tom VARCHAR(10),
    letra TEXT,
    cifra TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE escala_detalhes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    escala_id INT NOT NULL,
    musico_id INT NOT NULL,
    musica_id INT NOT NULL,
    tom_id VARCHAR(3) NOT NULL,
    FOREIGN KEY (escala_id) REFERENCES escalas(id),
    FOREIGN KEY (musico_id) REFERENCES musicos(id),
    FOREIGN KEY (musica_id) REFERENCES musicas(id),
    FOREIGN KEY (tom_id) REFERENCES tons(id)
);

CREATE TABLE jejum_semanal (
	id INT AUTO_INCREMENT PRIMARY KEY,
    musico_id INT NOT NULL, 
    dia_semana INT NOT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (musico_id) REFERENCES musicos(id)
);
