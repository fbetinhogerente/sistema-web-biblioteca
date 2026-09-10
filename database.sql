CREATE DATABASE IF NOT EXISTS biblioteca_web
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE biblioteca_web;

CREATE TABLE utilizadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    perfil ENUM('Administrador','Funcionario') NOT NULL DEFAULT 'Funcionario',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE autores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    nacionalidade VARCHAR(80)
);

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT
);

CREATE TABLE livros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    isbn VARCHAR(30) UNIQUE,
    editora VARCHAR(120),
    ano_publicacao YEAR,
    autor_id INT NOT NULL,
    categoria_id INT NOT NULL,
    estado ENUM('Disponivel','Emprestado') DEFAULT 'Disponivel',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (autor_id) REFERENCES autores(id),
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

CREATE TABLE leitores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(120) UNIQUE,
    telefone VARCHAR(30),
    endereco VARCHAR(200),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE emprestimos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    livro_id INT NOT NULL,
    leitor_id INT NOT NULL,
    utilizador_id INT NOT NULL,
    data_emprestimo DATE NOT NULL,
    data_prevista DATE NOT NULL,
    data_devolucao DATE NULL,
    estado ENUM('Ativo','Devolvido','Atrasado') DEFAULT 'Ativo',
    FOREIGN KEY (livro_id) REFERENCES livros(id),
    FOREIGN KEY (leitor_id) REFERENCES leitores(id),
    FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id)
);

INSERT INTO categorias (nome, descricao) VALUES
('Informática','Livros de informática e tecnologias'),
('Gestão','Livros de gestão e administração'),
('Literatura','Obras literárias'),
('Ciências','Livros científicos');

INSERT INTO autores (nome, nacionalidade) VALUES
('Autor Demonstrativo','Moçambicana');

-- Para o primeiro utilizador, crie a conta através do mecanismo de registo
-- da aplicação ou substitua o valor abaixo por um hash bcrypt.
