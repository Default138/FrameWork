-- =====================================================
-- Banco de dados de teste do framework de estatística
-- =====================================================
CREATE DATABASE IF NOT EXISTS estatistica
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE estatistica;

-- Conjunto 1: notas de três turmas
CREATE TABLE IF NOT EXISTS notas (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    turma  VARCHAR(20)   NOT NULL,
    nota   DECIMAL(4,2)  NOT NULL
);

INSERT INTO notas (turma, nota) VALUES
('Turma A',7.5),('Turma A',6.0),('Turma A',8.0),('Turma A',5.5),('Turma A',7.0),('Turma A',6.5),('Turma A',8.5),('Turma A',5.0),('Turma A',7.5),('Turma A',6.0),('Turma A',8.0),('Turma A',6.5),('Turma A',7.0),
('Turma B',8.5),('Turma B',9.0),('Turma B',7.5),('Turma B',8.0),('Turma B',9.5),('Turma B',8.0),('Turma B',7.0),('Turma B',8.5),('Turma B',9.0),('Turma B',8.0),('Turma B',7.5),('Turma B',8.5),('Turma B',9.0),
('Turma C',5.0),('Turma C',6.5),('Turma C',5.5),('Turma C',6.0),('Turma C',4.5),('Turma C',5.5),('Turma C',7.0),('Turma C',6.0),('Turma C',5.0),('Turma C',6.5),('Turma C',5.5),('Turma C',6.0),('Turma C',5.0),('Turma C',6.5);

-- Conjunto 2 (reutilização): produção diária de peças por máquina
CREATE TABLE IF NOT EXISTS producao (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    maquina  VARCHAR(20)  NOT NULL,
    pecas    INT          NULL
);

INSERT INTO producao (maquina, pecas) VALUES
('Maquina 1',102),('Maquina 1',98),('Maquina 1',105),('Maquina 1',99),('Maquina 1',101),('Maquina 1',103),
('Maquina 2',110),('Maquina 2',115),('Maquina 2',108),('Maquina 2',112),('Maquina 2',114),('Maquina 2',109),
('Maquina 3',100),('Maquina 3',104),('Maquina 3',97),('Maquina 3',102),('Maquina 3',NULL),('Maquina 3',101);

-- Conjunto 3 (reutilização): temperaturas registradas por sala
CREATE TABLE IF NOT EXISTS temperaturas (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    sala   VARCHAR(20)   NOT NULL,
    graus  DECIMAL(5,2)  NOT NULL
);

INSERT INTO temperaturas (sala, graus) VALUES
('Sala 1',21.5),('Sala 1',22.0),('Sala 1',21.8),('Sala 1',22.3),('Sala 1',21.9),
('Sala 2',22.1),('Sala 2',21.7),('Sala 2',22.4),('Sala 2',21.6),('Sala 2',22.0),
('Sala 3',21.8),('Sala 3',22.2),('Sala 3',21.9),('Sala 3',22.1),('Sala 3',21.7);
