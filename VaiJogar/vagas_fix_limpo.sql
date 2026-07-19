SET SQL_SAFE_UPDATES = 0;
USE vaijogar;

-- ============================================================
-- VAGAS_FIX.sql - Corrige vagas por escalao e por sessao
-- Executar na BD vaijogar apos ESTRUTURAPAP.sql + DADOSPAP.sql
-- ============================================================

-- Escaloes jovens tem mais vagas (formacao) que seniores:
--   Traquinas/Sub-7      -> 22 vagas totais / 8 vagas por sessao
--   Benjamins/Sub-9      -> 22 vagas totais / 8 vagas por sessao
--   Infantis/Sub-11      -> 20 vagas totais / 7 vagas por sessao
--   Iniciados/Sub-13     -> 18 vagas totais / 7 vagas por sessao
--   Juvenis/Sub-15/Sub-17 -> 16 vagas totais / 6 vagas por sessao
--   Juniores/Sub-19      -> 14 vagas totais / 5 vagas por sessao
--   Seniores             -> 12 vagas totais / 4 vagas por sessao
--   Veteranos            -> 10 vagas totais / 4 vagas por sessao

UPDATE escaloes SET vagas_totais = 22 WHERE nome IN ('Traquinas', 'Sub-7');
UPDATE escaloes SET vagas_totais = 22 WHERE nome IN ('Benjamins', 'Sub-9');
UPDATE escaloes SET vagas_totais = 20 WHERE nome IN ('Infantis', 'Sub-11', 'Sub-10');
UPDATE escaloes SET vagas_totais = 18 WHERE nome IN ('Iniciados', 'Sub-13');
UPDATE escaloes SET vagas_totais = 16 WHERE nome IN ('Juvenis', 'Sub-15', 'Sub-17');
UPDATE escaloes SET vagas_totais = 14 WHERE nome IN ('Juniores', 'Sub-19');
UPDATE escaloes SET vagas_totais = 12 WHERE nome = 'Seniores';
UPDATE escaloes SET vagas_totais = 10 WHERE nome = 'Veteranos';

UPDATE horarios h
JOIN escaloes e ON h.escalao_id = e.id
SET h.vagas_disponiveis = 8
WHERE e.nome IN ('Traquinas', 'Sub-7', 'Benjamins', 'Sub-9');

UPDATE horarios h
JOIN escaloes e ON h.escalao_id = e.id
SET h.vagas_disponiveis = 7
WHERE e.nome IN ('Infantis', 'Sub-11', 'Sub-10', 'Iniciados', 'Sub-13');

UPDATE horarios h
JOIN escaloes e ON h.escalao_id = e.id
SET h.vagas_disponiveis = 6
WHERE e.nome IN ('Juvenis', 'Sub-15', 'Sub-17');

UPDATE horarios h
JOIN escaloes e ON h.escalao_id = e.id
SET h.vagas_disponiveis = 5
WHERE e.nome IN ('Juniores', 'Sub-19');

UPDATE horarios h
JOIN escaloes e ON h.escalao_id = e.id
SET h.vagas_disponiveis = 4
WHERE e.nome IN ('Seniores', 'Veteranos');

SELECT
    e.nome AS escalao,
    e.vagas_totais,
    e.vagas_ocupadas,
    (e.vagas_totais - e.vagas_ocupadas) AS vagas_livres,
    COUNT(h.id) AS num_horarios,
    GROUP_CONCAT(
        CONCAT(h.dia_semana, ' ', h.hora_inicio, '-', h.hora_fim, ' (', h.vagas_disponiveis, ' vagas)')
        ORDER BY FIELD(h.dia_semana, 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo')
        SEPARATOR ' | '
    ) AS horarios
FROM escaloes e
LEFT JOIN horarios h ON h.escalao_id = e.id AND h.ativo = 1
GROUP BY e.id
ORDER BY e.club_id, e.id;

SET SQL_SAFE_UPDATES = 1;
