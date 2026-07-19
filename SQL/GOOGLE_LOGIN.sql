USE vaijogar;

ALTER TABLE utilizadores
    CHANGE password_hash password VARCHAR(255) NOT NULL,
    ADD COLUMN reset_token VARCHAR(100) NULL AFTER google_id,
    ADD COLUMN reset_expires DATETIME NULL AFTER reset_token;