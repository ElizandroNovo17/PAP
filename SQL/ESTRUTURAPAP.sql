-- ============================================================
-- 01_estrutura.sql — VaiJogar
-- Script 1/2: Estrutura completa da base de dados
-- Cria a BD "vaijogar" do zero com todas as tabelas
-- ============================================================

DROP DATABASE IF EXISTS vaijogar;
CREATE DATABASE vaijogar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vaijogar;

SET FOREIGN_KEY_CHECKS = 0;

-- ── UTILIZADORES ─────────────────────────────────────────────
-- Colunas com nomes que o PHP usa: id_utilizador, password
CREATE TABLE utilizadores (
    id_utilizador  INT          PRIMARY KEY AUTO_INCREMENT,
    nome           VARCHAR(255) NOT NULL,
    email          VARCHAR(255) UNIQUE NOT NULL,
    password       VARCHAR(255) NOT NULL,
    telefone       VARCHAR(20),
    role           ENUM('user','admin','club_manager') DEFAULT 'user',
    foto_perfil    VARCHAR(500),
    google_id      VARCHAR(255),
    data_registo   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    ativo          BOOLEAN      DEFAULT TRUE,
    reset_token    VARCHAR(255) DEFAULT NULL,
    reset_expires  DATETIME     DEFAULT NULL,
    INDEX idx_email (email),
    INDEX idx_role  (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── CLUBES ───────────────────────────────────────────────────
CREATE TABLE clubes (
    id              INT          PRIMARY KEY AUTO_INCREMENT,
    nome            VARCHAR(255) NOT NULL,
    modalidade      VARCHAR(100) NOT NULL,
    localizacao     VARCHAR(255),
    latitude        DECIMAL(10,8),
    longitude       DECIMAL(11,8),
    recinto         VARCHAR(255),
    divisao         VARCHAR(100),
    descricao       TEXT,
    imagem_url      VARCHAR(500),
    telefone        VARCHAR(20),
    email           VARCHAR(255),
    website         VARCHAR(255),
    facebook        VARCHAR(255),
    instagram       VARCHAR(255),
    inscricao_preco VARCHAR(50),
    data_criacao    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ativo           BOOLEAN   DEFAULT TRUE,
    INDEX idx_modalidade (modalidade),
    INDEX idx_nome       (nome),
    INDEX idx_ativo      (ativo),
    FULLTEXT ft_search   (nome, modalidade, localizacao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── PLANOS ───────────────────────────────────────────────────
CREATE TABLE planos (
    id                 INT          PRIMARY KEY AUTO_INCREMENT,
    nome               VARCHAR(100) UNIQUE NOT NULL,
    preco              DECIMAL(10,2) NOT NULL,
    dias_maximos       INT           NOT NULL,
    sessoes_por_semana VARCHAR(100),
    descricao          TEXT,
    data_criacao       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ativo              BOOLEAN   DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── ESCALÕES ─────────────────────────────────────────────────
-- Cada clube tem os seus próprios escalões
CREATE TABLE escaloes (
    id             INT          PRIMARY KEY AUTO_INCREMENT,
    club_id        INT          NOT NULL,
    nome           VARCHAR(100) NOT NULL,
    idade          VARCHAR(50),
    vagas_totais   INT DEFAULT 20,
    vagas_ocupadas INT DEFAULT 0,
    data_criacao   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (club_id) REFERENCES clubes(id) ON DELETE CASCADE,
    INDEX idx_club (club_id),
    UNIQUE KEY unique_club_escalao (club_id, nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── HORÁRIOS ─────────────────────────────────────────────────
-- Cada clube tem horários diferentes por escalão e dia
CREATE TABLE horarios (
    id                INT  PRIMARY KEY AUTO_INCREMENT,
    club_id           INT  NOT NULL,
    escalao_id        INT  NOT NULL,
    dia_semana        VARCHAR(20) NOT NULL,
    hora_inicio       TIME NOT NULL,
    hora_fim          TIME,
    vagas_disponiveis INT  DEFAULT 15,
    data_criacao      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ativo             BOOLEAN   DEFAULT TRUE,
    FOREIGN KEY (club_id)    REFERENCES clubes(id)   ON DELETE CASCADE,
    FOREIGN KEY (escalao_id) REFERENCES escaloes(id) ON DELETE CASCADE,
    INDEX idx_club_dia  (club_id, dia_semana),
    INDEX idx_escalao   (escalao_id),
    INDEX idx_ativo     (ativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── EXTRAS ───────────────────────────────────────────────────
CREATE TABLE extras (
    id        INT  PRIMARY KEY AUTO_INCREMENT,
    plano_id  INT  NOT NULL,
    nome      VARCHAR(100) NOT NULL,
    preco     DECIMAL(10,2) NOT NULL,
    descricao TEXT,
    FOREIGN KEY (plano_id) REFERENCES planos(id) ON DELETE CASCADE,
    INDEX idx_plano (plano_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── MARCAÇÕES ────────────────────────────────────────────────
CREATE TABLE marcacoes (
    id                   INT  PRIMARY KEY AUTO_INCREMENT,
    user_id              INT  NOT NULL,
    club_id              INT  NOT NULL,
    plano_id             INT  NOT NULL,
    escalao_id           INT  NOT NULL,
    referencia           VARCHAR(50) UNIQUE NOT NULL,
    status               ENUM('pendente','confirmado','cancelado','reembolsado','concluido') DEFAULT 'pendente',
    metodo               VARCHAR(30) DEFAULT 'mbway',
    preco                DECIMAL(10,2),
    dias                 VARCHAR(200),
    horario              VARCHAR(100),
    telefone_mbway       VARCHAR(20),
    cancelamento_motivo  TEXT,
    cancelado_em         DATETIME,
    criado_em            TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)     REFERENCES utilizadores(id_utilizador) ON DELETE CASCADE,
    FOREIGN KEY (club_id)     REFERENCES clubes(id)    ON DELETE CASCADE,
    FOREIGN KEY (plano_id)    REFERENCES planos(id),
    FOREIGN KEY (escalao_id)  REFERENCES escaloes(id),
    INDEX idx_user   (user_id),
    INDEX idx_club   (club_id),
    INDEX idx_status (status),
    INDEX idx_data   (criado_em)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── MARCAÇÕES_DIAS ───────────────────────────────────────────
CREATE TABLE marcacoes_dias (
    id          INT PRIMARY KEY AUTO_INCREMENT,
    marcacao_id INT NOT NULL,
    horario_id  INT NOT NULL,
    FOREIGN KEY (marcacao_id) REFERENCES marcacoes(id) ON DELETE CASCADE,
    FOREIGN KEY (horario_id)  REFERENCES horarios(id),
    UNIQUE KEY unique_marcacao_horario (marcacao_id, horario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── MARCAÇÕES_EXTRAS ─────────────────────────────────────────
CREATE TABLE marcacoes_extras (
    id          INT PRIMARY KEY AUTO_INCREMENT,
    marcacao_id INT NOT NULL,
    extra_id    INT,
    nome_extra  VARCHAR(100),
    preco_extra DECIMAL(10,2),
    FOREIGN KEY (marcacao_id) REFERENCES marcacoes(id) ON DELETE CASCADE,
    FOREIGN KEY (extra_id)    REFERENCES extras(id)    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── PAGAMENTOS ───────────────────────────────────────────────
CREATE TABLE pagamentos (
    id                   INT PRIMARY KEY AUTO_INCREMENT,
    marcacao_id          INT NOT NULL,
    valor                DECIMAL(10,2) NOT NULL,
    metodo               ENUM('cartao','mbway','multibanco','dinheiro') DEFAULT 'mbway',
    status               ENUM('pendente','pago','falhado','reembolsado') DEFAULT 'pendente',
    referencia_pagamento VARCHAR(100),
    data_pagamento       TIMESTAMP NULL,
    data_criacao         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (marcacao_id) REFERENCES marcacoes(id) ON DELETE CASCADE,
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── AVALIAÇÕES ───────────────────────────────────────────────
CREATE TABLE avaliacoes (
    id             INT PRIMARY KEY AUTO_INCREMENT,
    marcacao_id    INT NOT NULL,
    utilizador_id  INT NOT NULL,
    clube_id       INT NOT NULL,
    classificacao  INT CHECK (classificacao BETWEEN 1 AND 5),
    comentario     TEXT,
    data_avaliacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (marcacao_id)   REFERENCES marcacoes(id)              ON DELETE CASCADE,
    FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id_utilizador) ON DELETE CASCADE,
    FOREIGN KEY (clube_id)      REFERENCES clubes(id)                 ON DELETE CASCADE,
    INDEX idx_clube (clube_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── FAVORITOS ────────────────────────────────────────────────
CREATE TABLE favoritos (
    id            INT PRIMARY KEY AUTO_INCREMENT,
    utilizador_id INT NOT NULL,
    club_id       INT NOT NULL,
    data_adicao   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id_utilizador) ON DELETE CASCADE,
    FOREIGN KEY (club_id)       REFERENCES clubes(id)                  ON DELETE CASCADE,
    UNIQUE KEY unique_user_club (utilizador_id, club_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── LOGS ─────────────────────────────────────────────────────
CREATE TABLE logs_atividade (
    id             INT PRIMARY KEY AUTO_INCREMENT,
    utilizador_id  INT,
    tipo_acao      VARCHAR(100) NOT NULL,
    descricao      TEXT,
    tabela_afetada VARCHAR(100),
    data_acao      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address     VARCHAR(50),
    FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id_utilizador) ON DELETE SET NULL,
    INDEX idx_data (data_acao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ── VIEWS ────────────────────────────────────────────────────
CREATE VIEW vw_marcacoes_ativas AS
SELECT
    m.id, m.referencia, m.criado_em, m.status, m.metodo,
    m.dias, m.horario, m.preco,
    u.nome AS utilizador_nome, u.email,
    c.nome AS clube_nome, c.modalidade,
    p.nome AS plano_nome,
    e.nome AS escalao_nome
FROM marcacoes m
JOIN utilizadores u ON m.user_id     = u.id_utilizador
JOIN clubes c       ON m.club_id     = c.id
JOIN planos p       ON m.plano_id    = p.id
JOIN escaloes e     ON m.escalao_id  = e.id
WHERE m.status IN ('pendente','confirmado')
ORDER BY m.criado_em DESC;

CREATE VIEW vw_receita_por_clube AS
SELECT
    c.id, c.nome AS clube_nome, c.modalidade,
    COUNT(m.id)            AS total_marcacoes,
    COALESCE(SUM(m.preco), 0) AS receita_total
FROM clubes c
LEFT JOIN marcacoes m ON c.id = m.club_id
    AND m.status IN ('confirmado','concluido')
GROUP BY c.id, c.nome, c.modalidade
ORDER BY receita_total DESC;

-- ── TRIGGER ──────────────────────────────────────────────────
DELIMITER $$
CREATE TRIGGER tr_marcacoes_log AFTER INSERT ON marcacoes
FOR EACH ROW
BEGIN
    INSERT INTO logs_atividade (utilizador_id, tipo_acao, descricao, tabela_afetada)
    VALUES (NEW.user_id, 'nova_marcacao',
            CONCAT('Marcação criada: ', NEW.referencia), 'marcacoes');
END$$
DELIMITER ;

-- ── VERIFICAÇÃO ──────────────────────────────────────────────
SELECT TABLE_NAME, TABLE_ROWS
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_SCHEMA = 'vaijogar'
ORDER BY TABLE_NAME;
