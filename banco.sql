CREATE DATABASE IF NOT EXISTS agendamentos;
USE agendamentos;

CREATE TABLE agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    cpf VARCHAR(20),
    telefone VARCHAR(20),
    data DATE,
    horario TIME,
    obs TEXT,
    UNIQUE KEY (data, horario)
);