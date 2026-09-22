-- =====================================================
-- Criação da Base de Dados
-- =====================================================
CREATE DATABASE IF NOT EXISTS biblioteca
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE biblioteca;

-- =====================================================
-- Tabela: livros
-- =====================================================
CREATE TABLE IF NOT EXISTS livros (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    titulo        VARCHAR(150)   NOT NULL,
    autor         VARCHAR(100)   NOT NULL,
    editora       VARCHAR(100)   NOT NULL,
    ano_publicacao INT           NOT NULL,
    genero        VARCHAR(50)    NOT NULL,
    preco         DECIMAL(10,2)  NOT NULL DEFAULT 0.00,
    estoque       INT            NOT NULL DEFAULT 0,
    data_cadastro DATE           NOT NULL DEFAULT (CURRENT_DATE),
    created_at    TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP      DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Dados de exemplo (opcional, para testar o SELECT)
-- =====================================================
INSERT INTO livros (titulo, autor, editora, ano_publicacao, genero, preco, estoque) VALUES
('Dom Casmurro',              'Machado de Assis',     'Companhia das Letras', 1899, 'Romance',        49.90, 12),
('O Cortiço',                 'Aluísio Azevedo',      'Ática',                1890, 'Naturalismo',    39.90,  8),
('Grande Sertão: Veredas',    'João Guimarães Rosa',  'Nova Fronteira',       1956, 'Romance',        79.90,  5),
('Capitães da Areia',         'Jorge Amado',          'Companhia das Letras', 1937, 'Romance',        54.90, 10),
('Memórias Póstumas de Brás Cubas', 'Machado de Assis', 'Penguin',            1881, 'Romance',        45.00,  7);