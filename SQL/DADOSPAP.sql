-- ============================================================
-- 02_dados.sql — VaiJogar
-- Script 2/2: Todos os dados
-- Correr DEPOIS do 01_estrutura.sql
-- ============================================================
USE vaijogar;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE logs_atividade;
TRUNCATE TABLE avaliacoes;
TRUNCATE TABLE favoritos;
TRUNCATE TABLE marcacoes_extras;
TRUNCATE TABLE marcacoes_dias;
TRUNCATE TABLE pagamentos;
TRUNCATE TABLE marcacoes;
TRUNCATE TABLE horarios;
TRUNCATE TABLE escaloes;
TRUNCATE TABLE extras;
TRUNCATE TABLE clubes;
TRUNCATE TABLE planos;
TRUNCATE TABLE utilizadores;
SET FOREIGN_KEY_CHECKS = 1;

-- ══════════════════════════════════════════════════════════════
-- PLANOS
-- ══════════════════════════════════════════════════════════════
INSERT INTO planos (nome, preco, dias_maximos, sessoes_por_semana, descricao, ativo) VALUES
('Básico',  19.99, 1, '1 dia/semana',  '1 treino por semana — ideal para iniciantes',  1),
('Normal',  29.99, 3, '3 dias/semana', '3 treinos por semana — progressão consistente', 1),
('Premium', 39.99, 5, '5 dias/semana', '5 treinos por semana + acesso a extras',        1);

-- Extras Premium
INSERT INTO extras (plano_id, nome, preco, descricao) VALUES
(3, 'Ginásio Completo',  15.00, 'Acesso ilimitado ao ginásio do clube'),
(3, 'Fisioterapia',      20.00, '1 sessão de fisioterapia por semana'),
(3, 'Nutricionista',     25.00, 'Plano nutricional personalizado');

-- ══════════════════════════════════════════════════════════════
-- UTILIZADOR ADMIN (password: "password")
-- ══════════════════════════════════════════════════════════════
INSERT INTO utilizadores (nome, email, password, role, ativo) VALUES
('Admin VaiJogar', 'admin@vaijogar.pt',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'admin', 1);

-- ══════════════════════════════════════════════════════════════
-- CLUBES — FUTEBOL 1ª LIGA
-- ══════════════════════════════════════════════════════════════
INSERT INTO clubes (nome,modalidade,localizacao,latitude,longitude,recinto,divisao,descricao,imagem_url,telefone,email,website,facebook,instagram,inscricao_preco,ativo) VALUES
('FC Porto','futebol','Porto',41.1621,-8.6216,'Estádio do Dragão','1ª Liga','Futebol Clube do Porto, fundado em 1893. Tricampeão europeu.','https://cdn-img.staticzz.com/img/logos/equipas/9_imgbank_1728921003.png','+351 225 570 600','formacao@fcporto.pt','https://www.fcporto.pt','https://facebook.com/FCPorto','https://instagram.com/fcporto','35€/mês',1),
('Sporting CP','futebol','Lisboa',38.7614,-9.1604,'Estádio José Alvalade','1ª Liga','Sporting Clube de Portugal, fundado em 1906.','https://cdn-img.staticzz.com/img/logos/equipas/16_imgbank_1741687081.png','+351 217 511 400','formacao@sporting.pt','https://www.sporting.pt','https://facebook.com/SportingCP','https://instagram.com/sportingcp','35€/mês',1),
('SL Benfica','futebol','Lisboa',38.7527,-9.1849,'Estádio da Luz','1ª Liga','Sport Lisboa e Benfica, fundado em 1904. Mais titulado de Portugal.','https://cdn-img.staticzz.com/img/logos/equipas/4_imgbank_1683238034.png','+351 217 219 500','formacao@slbenfica.pt','https://www.slbenfica.pt','https://facebook.com/SLBenfica','https://instagram.com/slbenfica','35€/mês',1),
('SC Braga','futebol','Braga',41.5456,-8.4268,'Estádio Municipal de Braga','1ª Liga','Sporting Clube de Braga, fundado em 1921.','','+351 253 203 600','formacao@scbraga.pt','https://www.scbraga.pt','https://facebook.com/SCBraga','https://instagram.com/scbraga','30€/mês',1),
('Vitória SC','futebol','Guimarães',41.4425,-8.2962,'D. Afonso Henriques','1ª Liga','Vitória Sport Clube de Guimarães, fundado em 1922.','','+351 253 421 000','formacao@vitoriasc.pt','https://www.vitoriasc.pt','','','28€/mês',1),
('Gil Vicente','futebol','Barcelos',41.5317,-8.6151,'Cidade de Barcelos','1ª Liga','Gil Vicente Futebol Clube, fundado em 1924.','','+351 253 802 000','geral@gilvicente.pt','https://www.gilvicente.pt','','','25€/mês',1),
('FC Famalicão','futebol','Famalicão',41.4071,-8.5198,'22 de Junho','1ª Liga','Futebol Clube de Famalicão, fundado em 1931.','','+351 252 311 000','geral@fcfamalicao.pt','https://www.fcfamalicao.pt','','','25€/mês',1),
('Moreirense','futebol','Moreira de Cónegos',41.3796,-8.3399,'Comendador Joaquim de Almeida Freitas','1ª Liga','Moreirense FC, fundado em 1938.','','+351 253 561 200','geral@moreirensefc.pt','https://www.moreirensefc.pt','','','22€/mês',1),
('Estoril Praia','futebol','Estoril',38.7057,-9.3977,'António Coimbra da Mota','1ª Liga','Grupo Desportivo Estoril Praia, fundado em 1939.','https://cdn-img.staticzz.com/img/logos/equipas/1734_imgbank_1682584220.png','+351 214 680 000','formacao@gdestorilpraia.pt','https://www.gdestorilpraia.pt','','','25€/mês',1),
('FC Alverca','futebol','Alverca',38.8989,-9.0386,'Estádio FC Alverca','1ª Liga','Futebol Clube de Alverca, fundado em 1992.','','+351 219 574 200','geral@fcalverca.pt','https://www.fcalverca.pt','','','22€/mês',1),
('Rio Ave','futebol','Vila do Conde',41.3499,-8.7390,'Estádio dos Arcos','1ª Liga','Rio Ave Futebol Clube, fundado em 1939.','','+351 252 640 140','formacao@rioavefc.pt','https://www.rioavefc.pt','','','25€/mês',1),
('Nacional','futebol','Funchal',32.6666,-16.9245,'Estádio da Madeira','1ª Liga','Club Desportivo Nacional, fundado em 1910.','','+351 291 209 300','geral@cdnacional.pt','https://www.cdnacional.pt','','','22€/mês',1),
('Santa Clara','futebol','Ponta Delgada',37.7710,-25.3126,'Estádio de São Miguel','1ª Liga','Club Desportivo Santa Clara, fundado em 1921.','','+351 296 653 700','geral@cdsantaclara.pt','https://www.cdsantaclara.pt','','','22€/mês',1),
('Estrela da Amadora','futebol','Amadora',38.7596,-9.2247,'Estádio José Gomes','1ª Liga','Estrela da Amadora FC, fundado em 1932.','https://cdn-img.staticzz.com/img/logos/equipas/253884_imgbank_1755880629.png','+351 214 984 800','formacao@estreladaamadora.pt','https://www.estreladaamadora.pt','','','22€/mês',1),
('Casa Pia AC','futebol','Lisboa',38.7347,-9.1681,'Pina Manique','1ª Liga','Casa Pia Atlético Clube, fundado em 1920.','https://cdn-img.staticzz.com/img/logos/equipas/2412_imgbank_1695724045.png','+351 217 780 900','formacao@casapiaac.pt','https://www.casapiaac.pt','','','22€/mês',1),
('FC Arouca','futebol','Arouca',40.9262,-8.2449,'Estádio Municipal de Arouca','1ª Liga','Futebol Clube de Arouca, fundado em 1952.','','+351 256 943 600','geral@fcarouca.pt','https://www.fcarouca.pt','','','22€/mês',1),
('CD Tondela','futebol','Tondela',40.5256,-8.2837,'João Cardoso','1ª Liga','Clube Desportivo de Tondela, fundado em 1933.','','+351 232 814 000','geral@cdtondela.pt','https://www.cdtondela.pt','','','20€/mês',1),
('AFS Futebol SAD','futebol','Viseu',40.6560,-7.9129,'Estádio do Fontelo','1ª Liga','AVS Futebol SAD.','','+351 232 415 700','geral@avsfc.pt','https://www.avsfc.pt','','','20€/mês',1);

-- FUTEBOL 2ª LIGA
INSERT INTO clubes (nome,modalidade,localizacao,latitude,longitude,recinto,divisao,imagem_url,telefone,email,website,ativo) VALUES
('Marítimo','futebol','Funchal',32.6652,-16.9253,'Estádio dos Barreiros','2ª Liga','','+351 291 209 400','geral@csmaritimo.pt','https://www.csmaritimo.pt',1),
('Sporting CP B','futebol','Alcochete',38.7419,-8.9609,'Academia de Alcochete','2ª Liga','https://cdn-img.staticzz.com/img/logos/equipas/16_imgbank_1741687081.png','','','',1),
('Académico de Viseu','futebol','Viseu',40.6560,-7.9129,'Estádio do Fontelo','2ª Liga','','+351 232 415 700','geral@academicoviseu.pt','https://www.academicoviseu.pt',1),
('GD Chaves','futebol','Chaves',41.7416,-7.4689,'Municipal de Chaves','2ª Liga','','+351 276 332 300','geral@gdchaves.pt','https://www.gdchaves.pt',1),
('Vizela','futebol','Vizela',41.3829,-8.3065,'Estádio do Vizela','2ª Liga','','+351 253 483 200','geral@fcvizela.pt','https://www.fcvizela.pt',1),
('UD Leiria','futebol','Leiria',39.7472,-8.8077,'Magalhães Pessoa','2ª Liga','','+351 244 823 500','geral@udleiria.pt','https://www.udleiria.pt',1),
('SC União Torreense','futebol','Torres Vedras',39.0926,-9.2609,'Manuel Marques','2ª Liga','','+351 261 332 400','geral@scut.pt','',1),
('FC Penafiel','futebol','Penafiel',41.1477,-8.3920,'Municipal de Penafiel','2ª Liga','','+351 255 710 300','geral@fcpenafiel.pt','',1),
('SC Farense','futebol','Faro',37.0194,-7.9350,'São Luís','2ª Liga','','+351 289 823 900','geral@scfarense.pt','https://www.scfarense.pt',1),
('SL Benfica B','futebol','Seixal',38.6401,-9.1015,'Benfica Campus','2ª Liga','https://cdn-img.staticzz.com/img/logos/equipas/4_imgbank_1683238034.png','','','',1),
('FC Porto B','futebol','Olival',41.0911,-8.6249,'Centro de Treinos do Olival','2ª Liga','https://cdn-img.staticzz.com/img/logos/equipas/9_imgbank_1728921003.png','','','',1),
('CD Feirense','futebol','Santa Maria da Feira',40.9578,-8.6260,'Marcolino de Castro','2ª Liga','','+351 256 370 600','geral@cdfeirense.pt','',1),
('Portimonense SAD','futebol','Portimão',37.1366,-8.5341,'Municipal de Portimão','2ª Liga','','+351 282 407 400','geral@portimonense.pt','',1),
('UD Oliveirense','futebol','Oliveira de Azeméis',40.8380,-8.5100,'Carlos Osório','2ª Liga','','+351 256 682 400','geral@udoliveirense.pt','',1),
('FC Paços de Ferreira','futebol','Paços de Ferreira',41.3369,-8.2491,'Capital do Móvel','2ª Liga','','+351 255 862 200','geral@fcpf.pt','',1),
('Leixões SC','futebol','Matosinhos',41.1806,-8.6846,'Estádio do Mar','2ª Liga','','+351 229 381 900','geral@leixoessc.pt','',1);

-- FUTEBOL 3ª DIVISÃO / DISTRITAL
INSERT INTO clubes (nome,modalidade,localizacao,latitude,longitude,recinto,divisao,imagem_url,telefone,email,ativo) VALUES
('Varzim SC','futebol','Póvoa de Varzim',41.3834,-8.7636,'Estádio do Varzim SC','3ª Divisão Nacional','','+351 252 681 900','geral@varzimsc.pt',1),
('CD Trofense','futebol','Trofa',41.3393,-8.5606,'Estádio do Trofense','3ª Divisão Nacional','','+351 252 401 200','geral@cdtrofense.pt',1),
('SC Braga B','futebol','Braga',41.5456,-8.4268,'Cidade Desportiva SC Braga','3ª Divisão Nacional','','','',1),
('AD Fafe','futebol','Fafe',41.4510,-8.1683,'Municipal de Fafe','3ª Divisão Nacional','','+351 253 390 200','geral@adfafe.pt',1),
('FC Paredes','futebol','Paredes',41.2049,-8.3315,'Estádio Cidade de Paredes','3ª Divisão Nacional','','+351 255 781 200','geral@fcparedes.pt',1),
('Amarante FC','futebol','Amarante',41.2722,-8.0826,'Municipal de Amarante','3ª Divisão Nacional','','+351 255 432 100','geral@amarantefc.pt',1),
('CD Mafra','futebol','Mafra',38.9376,-9.3272,'Municipal de Mafra','3ª Divisão Nacional','','+351 261 815 700','geral@cdmafra.pt',1),
('Amora FC','futebol','Amora',38.6207,-9.1151,'Estádio da Medideira','3ª Divisão Nacional','','+351 212 279 400','geral@amorafc.pt',1),
('CF Os Belenenses','futebol','Lisboa',38.7068,-9.2146,'Estádio do Restelo','3ª Divisão Nacional','https://cdn-img.staticzz.com/img/logos/equipas/3_imgbank_1682589777.png','+351 213 636 300','geral@cfbelenenses.pt',1),
('Académica OAF','futebol','Coimbra',40.2033,-8.4103,'Estádio Cidade de Coimbra','3ª Divisão Nacional','','+351 239 793 700','geral@academica.pt',1),
('SC Covilhã','futebol','Covilhã',40.2833,-7.5019,'Municipal José Santos Pinto','3ª Divisão Nacional','','+351 275 330 300','geral@sccovilha.pt',1),
('Lusitano GC Évora','futebol','Évora',38.5714,-7.9097,'Campo Estrela','3ª Divisão Nacional','','+351 266 730 100','geral@lusitanogce.pt',1),
('GD Carcavelos','futebol','Carcavelos',38.6928,-9.3364,'Campo do Carcavelos','Distrital/Regional','https://cdn-img.staticzz.com/img/logos/equipas/11193_imgbank.png','+351 214 584 300','geral@gdcarcavelos.pt',1),
('CD Carcavelos','futebol','Carcavelos',38.6928,-9.3364,'Campo CD Carcavelos','Distrital/Regional','https://cdn-img.staticzz.com/img/logos/equipas/11193_imgbank.png','','',1),
('GD Estoril-Praia B','futebol','Estoril',38.7057,-9.3977,'António Coimbra da Mota','Distrital/Regional','https://cdn-img.staticzz.com/img/logos/equipas/1734_imgbank_1682584220.png','','',1),
('SC Lourel','futebol','Sintra',38.8007,-9.3839,'Campo do SC Lourel','Distrital/Regional','https://cdn-img.staticzz.com/img/logos/equipas/3958_imgbank_1748614610.png','+351 219 231 400','geral@sclourel.pt',1),
('Sintrense FC','futebol','Sintra',38.8007,-9.3839,'Estádio José Martins Vieira','Distrital/Regional','https://cdn-img.staticzz.com/img/logos/equipas/3655_imgbank_1683305451.png','+351 219 241 200','geral@sintrensefc.pt',1),
('SU Sintrense','futebol','Sintra',38.8007,-9.3839,'Campo da SU Sintrense','Distrital/Regional','https://cdn-img.staticzz.com/img/logos/equipas/3655_imgbank_1683305451.png','','',1),
('GD Sesimbra','futebol','Sesimbra',38.4440,-9.1009,'Campo Municipal de Sesimbra','Distrital/Regional','','+351 212 288 500','geral@gdsesimbra.pt',1),
('GS Loures','futebol','Loures',38.8307,-9.1653,'Estádio Municipal de Loures','Distrital/Regional','','+351 219 839 700','geral@gsloures.pt',1);

-- BASQUETEBOL
INSERT INTO clubes (nome,modalidade,localizacao,latitude,longitude,recinto,divisao,imagem_url,telefone,email,website,ativo) VALUES
('Sporting CP','basquetebol','Lisboa',38.7614,-9.1604,'Pavilhão João Rocha','Liga Portuguesa Basquetebol','https://cdn-img.staticzz.com/img/logos/equipas/16_imgbank_1741687081.png','+351 217 511 400','basquetebol@sporting.pt','https://www.sporting.pt',1),
('SL Benfica','basquetebol','Lisboa',38.7527,-9.1849,'Pavilhão Fidelidade','Liga Portuguesa Basquetebol','https://cdn-img.staticzz.com/img/logos/equipas/4_imgbank_1683238034.png','+351 217 219 500','basquetebol@slbenfica.pt','https://www.slbenfica.pt',1),
('FC Porto','basquetebol','Porto',41.1621,-8.6216,'Dragão Arena','Liga Portuguesa Basquetebol','https://cdn-img.staticzz.com/img/logos/equipas/9_imgbank_1728921003.png','+351 225 570 600','basquetebol@fcporto.pt','https://www.fcporto.pt',1),
('Ovarense GAVEX','basquetebol','Ovar',40.8616,-8.6256,'Arena de Ovar','Liga Portuguesa Basquetebol','','+351 256 581 900','geral@ovarensebasquetebol.pt','https://www.ovarensebasquetebol.pt',1),
('UD Oliveirense','basquetebol','Oliveira de Azeméis',40.8380,-8.5100,'Pavilhão Dr. Salvador Machado','Liga Portuguesa Basquetebol','','+351 256 682 400','basquetebol@udoliveirense.pt','',1),
('Imortal LUZiGÁS','basquetebol','Albufeira',37.0897,-8.2503,'Pavilhão de Albufeira','Liga Portuguesa Basquetebol','','+351 289 512 800','geral@imortalbasket.pt','',1),
('CA Queluz','basquetebol','Queluz',38.7548,-9.2431,'Pavilhão Henrique Miranda','Liga Portuguesa Basquetebol','','+351 214 368 200','geral@cabasquetebolqueluz.pt','',1),
('CP Esgueira','basquetebol','Aveiro',40.6415,-8.6499,'Pavilhão de Esgueira','Liga Portuguesa Basquetebol','','+351 234 312 900','geral@cpesgueira.pt','',1),
('SC Braga','basquetebol','Braga',41.5456,-8.4268,'Pavilhão Gimnodesportivo','Liga Portuguesa Basquetebol','','+351 253 203 600','basquetebol@scbraga.pt','',1),
('Vitória SC','basquetebol','Guimarães',41.4425,-8.2962,'Pavilhão Unidade Vimaranense','Liga Portuguesa Basquetebol','','+351 253 421 000','basquetebol@vitoriasc.pt','',1),
('SC Vasco da Gama','basquetebol','Seixal',38.5903,-9.0769,'Pavilhão Vasco da Gama','Liga Portuguesa Basquetebol','','+351 212 279 500','geral@scvascodagama.pt','',1),
('Galitos Barreiro','basquetebol','Barreiro',38.6523,-9.0759,'Pavilhão do Galitos','Liga Portuguesa Basquetebol','','+351 212 059 200','geral@galitosbarreiro.pt','',1),
('Illiabum Clube','basquetebol','Ílhavo',40.6019,-8.6700,'Pavilhão Capitão Adriano Nordeste','Liga Portuguesa Basquetebol','','+351 234 322 100','geral@illiabum.pt','',1),
('Sangalhos DC','basquetebol','Sangalhos',40.4869,-8.4696,'Pavilhão de Sangalhos','Liga Portuguesa Basquetebol','','+351 234 741 100','geral@sangalhosdc.pt','',1),
('Maia Basket','basquetebol','Maia',41.2357,-8.6199,'Pavilhão Municipal da Maia','Liga Portuguesa Basquetebol','','+351 229 409 700','geral@maiabasket.pt','',1),
('Guifões SC','basquetebol','Matosinhos',41.2049,-8.6656,'Pavilhão de Guifões','Liga Portuguesa Basquetebol','','+351 229 380 100','geral@guifoessc.pt','',1),
('CAB Madeira','basquetebol','Funchal',32.6669,-16.9241,'Pavilhão do Funchal','Liga Portuguesa Basquetebol','','+351 291 233 300','geral@cabmadeira.pt','',1),
('Belenenses','basquetebol','Lisboa',38.7036,-9.2057,'Pavilhão Acácio Rosa','Liga Portuguesa Basquetebol','https://cdn-img.staticzz.com/img/logos/equipas/3_imgbank_1682589777.png','+351 213 636 300','basquetebol@cfbelenenses.pt','',1),
('GD Carcavelos','basquetebol','Carcavelos',38.6928,-9.3364,'Pavilhão de Carcavelos','Divisão Regional','https://cdn-img.staticzz.com/img/logos/equipas/11193_imgbank.png','+351 214 584 300','basquetebol@gdcarcavelos.pt','',1),
('CB Carcavelos','basquetebol','Carcavelos',38.6928,-9.3364,'Pavilhão de Carcavelos','Divisão Regional','https://cdn-img.staticzz.com/img/logos/equipas/11193_imgbank.png','','','',1);

-- VOLEIBOL
INSERT INTO clubes (nome,modalidade,localizacao,latitude,longitude,recinto,divisao,imagem_url,telefone,email,website,ativo) VALUES
('SL Benfica','voleibol','Lisboa',38.7527,-9.1849,'Pavilhão Fidelidade','Campeonato Nacional Voleibol','https://cdn-img.staticzz.com/img/logos/equipas/4_imgbank_1683238034.png','+351 217 219 500','voleibol@slbenfica.pt','https://www.slbenfica.pt',1),
('Sporting CP','voleibol','Lisboa',38.7614,-9.1604,'Pavilhão João Rocha','Campeonato Nacional Voleibol','https://cdn-img.staticzz.com/img/logos/equipas/16_imgbank_1741687081.png','+351 217 511 400','voleibol@sporting.pt','https://www.sporting.pt',1),
('Sporting de Espinho','voleibol','Espinho',41.0072,-8.6410,'Pavilhão Arquiteto Jerónimo Reis','Campeonato Nacional Voleibol','','+351 227 340 200','geral@spespinho.pt','',1),
('Académica de Espinho','voleibol','Espinho',41.0072,-8.6410,'Pavilhão Nave Polivalente','Campeonato Nacional Voleibol','','+351 227 340 100','geral@academicaespinho.pt','',1),
('Castêlo da Maia GC','voleibol','Maia',41.2676,-8.6174,'Pavilhão Municipal Castêlo da Maia','Campeonato Nacional Voleibol','','+351 229 409 500','geral@castelodamaia.pt','',1),
('Leixões SC','voleibol','Matosinhos',41.1856,-8.6894,'Pavilhão Ilídio Ramos','Campeonato Nacional Voleibol','','+351 229 381 900','voleibol@leixoessc.pt','',1),
('Vitória SC','voleibol','Guimarães',41.4425,-8.2962,'Pavilhão Unidade Vimaranense','Campeonato Nacional Voleibol','','+351 253 421 000','voleibol@vitoriasc.pt','',1),
('VC Viana','voleibol','Viana do Castelo',41.6932,-8.8329,'Pavilhão Municipal Sta Maria Maior','Campeonato Nacional Voleibol','','+351 258 829 300','geral@vcviana.pt','',1),
('Esmoriz GC','voleibol','Esmoriz',40.9577,-8.6275,'Pavilhão Gimnodesportivo de Esmoriz','Campeonato Nacional Voleibol','','+351 256 741 200','geral@esmoriz.pt','',1),
('GC Vilacondense','voleibol','Vila do Conde',41.3520,-8.7431,'Pavilhão Municipal Vila do Conde','Campeonato Nacional Voleibol','','+351 252 640 500','geral@gcvilacondense.pt','',1),
('SC Caldas','voleibol','Caldas da Rainha',39.4036,-9.1387,'Pavilhão Rainha D. Leonor','Campeonato Nacional Voleibol','','+351 262 831 200','geral@sccaldas.pt','',1),
('CV Oeiras','voleibol','Oeiras',38.6979,-9.3086,'Pavilhão do Jamor','Campeonato Nacional Voleibol','','+351 214 408 900','geral@cvoeiras.pt','',1),
('CN Ginástica','voleibol','Barreiro',38.6599,-9.2050,'Pavilhão Alfredo da Silva','Campeonato Nacional Voleibol','','+351 212 059 100','geral@cnginastica.pt','',1),
('GD Carcavelos','voleibol','Carcavelos',38.6928,-9.3364,'Pavilhão de Carcavelos','Divisão Regional','https://cdn-img.staticzz.com/img/logos/equipas/11193_imgbank.png','+351 214 584 300','voleibol@gdcarcavelos.pt','',1),
('VC Braga','voleibol','Braga',41.5456,-8.4268,'Pavilhão Gimnodesportivo Braga','Divisão Regional','','+351 253 203 700','geral@vcbraga.pt','',1),
('VC Sintra','voleibol','Sintra',38.8007,-9.3839,'Pavilhão Municipal de Sintra','Divisão Regional','','+351 219 231 500','geral@vcsintra.pt','',1),
('GD Sesimbra VC','voleibol','Sesimbra',38.4440,-9.1009,'Pavilhão Municipal de Sesimbra','Divisão Regional','','+351 212 288 500','geral@gdsesimbra.pt','',1),
('Ala de Gondomar','voleibol','Gondomar',41.1446,-8.5326,'Pavilhão Municipal de Gondomar','Divisão Regional','','+351 224 650 300','geral@alagondomar.pt','',1),
('SO Marinhense','voleibol','Marinha Grande',39.7476,-8.9323,'Pavilhão Municipal Marinha Grande','Divisão Regional','','+351 244 572 100','geral@somarinhense.pt','',1),
('GDC Gueifães','voleibol','Maia',41.2565,-8.6147,'Pavilhão de Gueifães','Divisão Regional','','','','',1);

-- ══════════════════════════════════════════════════════════════
-- ESCALÕES E HORÁRIOS ESPECÍFICOS POR CLUBE
-- Cada clube tem os seus próprios escalões e dias de treino
-- ══════════════════════════════════════════════════════════════

-- ── HELPER: insere escalão e retorna o id via variável ───────
-- Usamos INSERT IGNORE + SELECT para ser idempotente

-- ════════════════════
-- FC PORTO (futebol)
-- ════════════════════
SET @c = (SELECT id FROM clubes WHERE nome='FC Porto' AND modalidade='futebol' LIMIT 1);
INSERT IGNORE INTO escaloes (club_id,nome,idade,vagas_totais,vagas_ocupadas) VALUES
(@c,'Traquinas','Sub-7',22,4),(@c,'Benjamins','Sub-9',22,6),(@c,'Infantis','Sub-11',24,8),
(@c,'Iniciados','Sub-13',24,10),(@c,'Juvenis','Sub-15',24,12),(@c,'Juniores','Sub-19',22,14),(@c,'Seniores','18+',28,18);
-- Traquinas: Seg/Qua/Sex manhã
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'09:00','10:00',8,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE e.nome='Traquinas' AND e.club_id=@c;
-- Benjamins: Ter/Qui manhã
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'10:00','11:00',11,1 FROM escaloes e,(SELECT 'Terça' dia UNION SELECT 'Quinta') d WHERE e.nome='Benjamins' AND e.club_id=@c;
-- Infantis: Seg/Qua/Sex tarde
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'15:30','17:00',8,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE e.nome='Infantis' AND e.club_id=@c;
-- Iniciados: Seg/Ter/Qui tarde
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'17:00','18:30',8,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Terça' UNION SELECT 'Quinta') d WHERE e.nome='Iniciados' AND e.club_id=@c;
-- Juvenis: Seg/Qua/Sex tarde-noite
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'18:00','19:30',8,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE e.nome='Juvenis' AND e.club_id=@c;
-- Juniores: Ter/Qui/Sáb noite
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'19:30','21:00',7,1 FROM escaloes e,(SELECT 'Terça' dia UNION SELECT 'Quinta' UNION SELECT 'Sábado') d WHERE e.nome='Juniores' AND e.club_id=@c;
-- Seniores: Seg/Qua/Sex/Sáb noite
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'20:00','21:30',7,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta' UNION SELECT 'Sábado') d WHERE e.nome='Seniores' AND e.club_id=@c;

-- ════════════════════
-- SPORTING CP (futebol)
-- ════════════════════
SET @c = (SELECT id FROM clubes WHERE nome='Sporting CP' AND modalidade='futebol' LIMIT 1);
INSERT IGNORE INTO escaloes (club_id,nome,idade,vagas_totais,vagas_ocupadas) VALUES
(@c,'Traquinas','Sub-7',20,3),(@c,'Benjamins','Sub-9',20,5),(@c,'Infantis','Sub-11',22,7),
(@c,'Iniciados','Sub-13',22,9),(@c,'Juvenis','Sub-15',22,11),(@c,'Juniores','Sub-19',20,13),(@c,'Seniores','18+',26,16);
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'09:30','10:30',7,1 FROM escaloes e,(SELECT 'Terça' dia UNION SELECT 'Quinta' UNION SELECT 'Sábado') d WHERE e.nome='Traquinas' AND e.club_id=@c;
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'10:30','11:30',10,1 FROM escaloes e,(SELECT 'Terça' dia UNION SELECT 'Quinta') d WHERE e.nome='Benjamins' AND e.club_id=@c;
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'15:00','16:30',8,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE e.nome='Infantis' AND e.club_id=@c;
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'16:30','18:00',8,1 FROM escaloes e,(SELECT 'Terça' dia UNION SELECT 'Quinta' UNION SELECT 'Sábado') d WHERE e.nome='Iniciados' AND e.club_id=@c;
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'18:00','19:30',8,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE e.nome='Juvenis' AND e.club_id=@c;
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'19:30','21:00',8,1 FROM escaloes e,(SELECT 'Terça' dia UNION SELECT 'Quinta' UNION SELECT 'Sábado') d WHERE e.nome='Juniores' AND e.club_id=@c;
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'20:30','22:00',9,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE e.nome='Seniores' AND e.club_id=@c;

-- ════════════════════
-- SL BENFICA (futebol)
-- ════════════════════
SET @c = (SELECT id FROM clubes WHERE nome='SL Benfica' AND modalidade='futebol' LIMIT 1);
INSERT IGNORE INTO escaloes (club_id,nome,idade,vagas_totais,vagas_ocupadas) VALUES
(@c,'Traquinas','Sub-7',21,5),(@c,'Benjamins','Sub-9',21,8),(@c,'Infantis','Sub-11',21,10),
(@c,'Iniciados','Sub-13',21,12),(@c,'Juvenis','Sub-15',21,15),(@c,'Juniores','Sub-19',21,18),(@c,'Seniores','18+',27,20);
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'09:00','10:00',7,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE e.nome='Traquinas' AND e.club_id=@c;
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'10:00','11:00',10,1 FROM escaloes e,(SELECT 'Terça' dia UNION SELECT 'Quinta') d WHERE e.nome='Benjamins' AND e.club_id=@c;
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'15:00','16:00',7,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE e.nome='Infantis' AND e.club_id=@c;
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'16:00','17:00',7,1 FROM escaloes e,(SELECT 'Terça' dia UNION SELECT 'Quinta' UNION SELECT 'Sábado') d WHERE e.nome='Iniciados' AND e.club_id=@c;
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'17:00','18:30',7,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE e.nome='Juvenis' AND e.club_id=@c;
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'19:00','20:30',7,1 FROM escaloes e,(SELECT 'Terça' dia UNION SELECT 'Quinta' UNION SELECT 'Sábado') d WHERE e.nome='Juniores' AND e.club_id=@c;
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'20:00','21:30',9,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE e.nome='Seniores' AND e.club_id=@c;

-- ════════════════════════════════════════════════
-- RESTANTES CLUBES FUTEBOL — escalões padrão
-- (horários diferentes por clube)
-- ════════════════════════════════════════════════

-- SC Braga
SET @c = (SELECT id FROM clubes WHERE nome='SC Braga' AND modalidade='futebol' LIMIT 1);
INSERT IGNORE INTO escaloes (club_id,nome,idade,vagas_totais,vagas_ocupadas) VALUES
(@c,'Traquinas','Sub-7',18,3),(@c,'Benjamins','Sub-9',18,5),(@c,'Infantis','Sub-11',20,6),
(@c,'Iniciados','Sub-13',20,8),(@c,'Juvenis','Sub-15',20,10),(@c,'Juniores','Sub-19',18,12),(@c,'Seniores','18+',24,15);
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'09:00','10:00',6,1 FROM escaloes e,(SELECT 'Terça' dia UNION SELECT 'Quinta' UNION SELECT 'Sábado') d WHERE e.nome='Traquinas' AND e.club_id=@c;
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'10:00','11:00',9,1 FROM escaloes e,(SELECT 'Terça' dia UNION SELECT 'Quinta') d WHERE e.nome='Benjamins' AND e.club_id=@c;
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'16:00','17:30',7,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE e.nome='Infantis' AND e.club_id=@c;
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'17:00','18:30',7,1 FROM escaloes e,(SELECT 'Terça' dia UNION SELECT 'Quinta' UNION SELECT 'Sábado') d WHERE e.nome='Iniciados' AND e.club_id=@c;
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'18:30','20:00',7,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE e.nome='Juvenis' AND e.club_id=@c;
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'20:00','21:30',6,1 FROM escaloes e,(SELECT 'Terça' dia UNION SELECT 'Quinta' UNION SELECT 'Sábado') d WHERE e.nome='Juniores' AND e.club_id=@c;
INSERT IGNORE INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT @c,e.id,d.dia,'20:30','22:00',8,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE e.nome='Seniores' AND e.club_id=@c;

-- ════════════════════════════════════════════════════════════
-- TODOS OS OUTROS CLUBES FUTEBOL — escalões padrão com
-- horários ligeiramente diferentes para variar
-- ════════════════════════════════════════════════════════════
-- Usamos um bloco genérico para os restantes clubes de futebol

-- Vitória SC
SET @c=(SELECT id FROM clubes WHERE nome='Vitória SC' AND modalidade='futebol' LIMIT 1);
INSERT IGNORE INTO escaloes(club_id,nome,idade,vagas_totais,vagas_ocupadas) VALUES(@c,'Traquinas','Sub-7',18,3),(@c,'Benjamins','Sub-9',18,4),(@c,'Infantis','Sub-11',20,6),(@c,'Iniciados','Sub-13',20,8),(@c,'Juvenis','Sub-15',20,10),(@c,'Juniores','Sub-19',18,11),(@c,'Seniores','18+',24,14);
INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo) SELECT @c,e.id,d.dia,'09:00','10:00',6,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE e.nome='Traquinas' AND e.club_id=@c;
INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo) SELECT @c,e.id,d.dia,'10:00','11:00',9,1 FROM escaloes e,(SELECT 'Terça' dia UNION SELECT 'Quinta') d WHERE e.nome='Benjamins' AND e.club_id=@c;
INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo) SELECT @c,e.id,d.dia,'15:30','17:00',7,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE e.nome='Infantis' AND e.club_id=@c;
INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo) SELECT @c,e.id,d.dia,'17:00','18:30',7,1 FROM escaloes e,(SELECT 'Terça' dia UNION SELECT 'Quinta' UNION SELECT 'Sábado') d WHERE e.nome='Iniciados' AND e.club_id=@c;
INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo) SELECT @c,e.id,d.dia,'18:00','19:30',7,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE e.nome='Juvenis' AND e.club_id=@c;
INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo) SELECT @c,e.id,d.dia,'19:30','21:00',6,1 FROM escaloes e,(SELECT 'Terça' dia UNION SELECT 'Quinta' UNION SELECT 'Sábado') d WHERE e.nome='Juniores' AND e.club_id=@c;
INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo) SELECT @c,e.id,d.dia,'20:00','21:30',8,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE e.nome='Seniores' AND e.club_id=@c;

-- Macro para clubes futebol restantes (escalões padrão, horários variados)
-- Gil Vicente
SET @c=(SELECT id FROM clubes WHERE nome='Gil Vicente' AND modalidade='futebol' LIMIT 1);
INSERT IGNORE INTO escaloes(club_id,nome,idade,vagas_totais,vagas_ocupadas) VALUES(@c,'Traquinas','Sub-7',16,2),(@c,'Benjamins','Sub-9',16,3),(@c,'Infantis','Sub-11',18,5),(@c,'Iniciados','Sub-13',18,7),(@c,'Juvenis','Sub-15',18,9),(@c,'Juniores','Sub-19',16,10),(@c,'Seniores','18+',22,13);
INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo) SELECT @c,e.id,d.dia,'09:00','10:00',6,1 FROM escaloes e,(SELECT 'Terça' dia UNION SELECT 'Quinta' UNION SELECT 'Sábado') d WHERE e.nome IN('Traquinas','Benjamins') AND e.club_id=@c;
INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo) SELECT @c,e.id,d.dia,'16:00','17:30',6,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE e.nome IN('Infantis','Iniciados') AND e.club_id=@c;
INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo) SELECT @c,e.id,d.dia,'19:00','20:30',6,1 FROM escaloes e,(SELECT 'Terça' dia UNION SELECT 'Quinta' UNION SELECT 'Sábado') d WHERE e.nome IN('Juvenis','Juniores') AND e.club_id=@c;
INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo) SELECT @c,e.id,d.dia,'20:30','22:00',7,1 FROM escaloes e,(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE e.nome='Seniores' AND e.club_id=@c;

-- FC Famalicão, Moreirense, Estoril, Alverca, Rio Ave, Nacional, Santa Clara,
-- Estrela Amadora, Casa Pia, Arouca, Tondela, AFS, e todos da 2ª/3ª divisão
-- → escalões padrão + horários padrão (Seg/Qua/Sex manhã jovens, tarde mid, noite seniores)
INSERT IGNORE INTO escaloes(club_id,nome,idade,vagas_totais,vagas_ocupadas)
SELECT c.id,e.nome,e.idade,e.vt,e.vo FROM clubes c
JOIN (SELECT 'Traquinas' nome,'Sub-7' idade,16 vt,2 vo UNION SELECT 'Benjamins','Sub-9',16,3 UNION SELECT 'Infantis','Sub-11',18,5
      UNION SELECT 'Iniciados','Sub-13',18,6 UNION SELECT 'Juvenis','Sub-15',18,8 UNION SELECT 'Juniores','Sub-19',16,10 UNION SELECT 'Seniores','18+',22,13) e
WHERE c.modalidade='futebol'
AND c.nome NOT IN('FC Porto','Sporting CP','SL Benfica','SC Braga','Vitória SC','Gil Vicente');

-- Horários padrão futebol para restantes clubes
INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT c.id,e.id,d.dia,'09:00','10:00',6,1 FROM clubes c
JOIN escaloes e ON e.club_id=c.id AND e.nome IN('Traquinas','Benjamins')
JOIN(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d
WHERE c.modalidade='futebol'
AND c.nome NOT IN('FC Porto','Sporting CP','SL Benfica','SC Braga','Vitória SC','Gil Vicente');

INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT c.id,e.id,d.dia,'15:30','17:00',6,1 FROM clubes c
JOIN escaloes e ON e.club_id=c.id AND e.nome IN('Infantis','Iniciados')
JOIN(SELECT 'Terça' dia UNION SELECT 'Quinta' UNION SELECT 'Sábado') d
WHERE c.modalidade='futebol'
AND c.nome NOT IN('FC Porto','Sporting CP','SL Benfica','SC Braga','Vitória SC','Gil Vicente');

INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT c.id,e.id,d.dia,'18:00','19:30',6,1 FROM clubes c
JOIN escaloes e ON e.club_id=c.id AND e.nome IN('Juvenis','Juniores')
JOIN(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d
WHERE c.modalidade='futebol'
AND c.nome NOT IN('FC Porto','Sporting CP','SL Benfica','SC Braga','Vitória SC','Gil Vicente');

INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT c.id,e.id,d.dia,'20:00','21:30',7,1 FROM clubes c
JOIN escaloes e ON e.club_id=c.id AND e.nome='Seniores'
JOIN(SELECT 'Terça' dia UNION SELECT 'Quinta' UNION SELECT 'Sábado') d
WHERE c.modalidade='futebol'
AND c.nome NOT IN('FC Porto','Sporting CP','SL Benfica','SC Braga','Vitória SC','Gil Vicente');

-- ════════════════════════════════════════════════════════════
-- BASQUETEBOL — escalões e horários
-- ════════════════════════════════════════════════════════════
INSERT IGNORE INTO escaloes(club_id,nome,idade,vagas_totais,vagas_ocupadas)
SELECT c.id,e.nome,e.idade,e.vt,e.vo FROM clubes c
JOIN (SELECT 'Minis' nome,'Sub-10' idade,18 vt,3 vo UNION SELECT 'Benjamins','Sub-12',18,4
      UNION SELECT 'Infantis','Sub-14',20,6 UNION SELECT 'Cadetes','Sub-16',20,8
      UNION SELECT 'Juvenis','Sub-18',20,10 UNION SELECT 'Juniores','Sub-20',18,12 UNION SELECT 'Seniores','18+',24,15) e
WHERE c.modalidade='basquetebol';

INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT c.id,e.id,d.dia,'09:00','10:00',6,1 FROM clubes c
JOIN escaloes e ON e.club_id=c.id AND e.nome IN('Minis','Benjamins')
JOIN(SELECT 'Sábado' dia UNION SELECT 'Quarta') d WHERE c.modalidade='basquetebol';

INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT c.id,e.id,d.dia,'17:00','18:30',7,1 FROM clubes c
JOIN escaloes e ON e.club_id=c.id AND e.nome IN('Infantis','Cadetes')
JOIN(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE c.modalidade='basquetebol';

INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT c.id,e.id,d.dia,'19:00','20:30',7,1 FROM clubes c
JOIN escaloes e ON e.club_id=c.id AND e.nome IN('Juvenis','Juniores')
JOIN(SELECT 'Terça' dia UNION SELECT 'Quinta' UNION SELECT 'Sábado') d WHERE c.modalidade='basquetebol';

INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT c.id,e.id,d.dia,'21:00','22:30',8,1 FROM clubes c
JOIN escaloes e ON e.club_id=c.id AND e.nome='Seniores'
JOIN(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE c.modalidade='basquetebol';

-- ════════════════════════════════════════════════════════════
-- VOLEIBOL — escalões e horários
-- ════════════════════════════════════════════════════════════
INSERT IGNORE INTO escaloes(club_id,nome,idade,vagas_totais,vagas_ocupadas)
SELECT c.id,e.nome,e.idade,e.vt,e.vo FROM clubes c
JOIN (SELECT 'Minis' nome,'Sub-12' idade,18 vt,3 vo UNION SELECT 'Infantis','Sub-14',20,5
      UNION SELECT 'Cadetes','Sub-16',20,7 UNION SELECT 'Juvenis','Sub-18',20,9
      UNION SELECT 'Juniores','Sub-20',18,11 UNION SELECT 'Seniores','18+',24,14 UNION SELECT 'Masters','35+',18,8) e
WHERE c.modalidade='voleibol';

INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT c.id,e.id,d.dia,'09:00','10:30',6,1 FROM clubes c
JOIN escaloes e ON e.club_id=c.id AND e.nome IN('Minis','Infantis')
JOIN(SELECT 'Sábado' dia UNION SELECT 'Quarta') d WHERE c.modalidade='voleibol';

INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT c.id,e.id,d.dia,'17:30','19:00',7,1 FROM clubes c
JOIN escaloes e ON e.club_id=c.id AND e.nome IN('Cadetes','Juvenis')
JOIN(SELECT 'Terça' dia UNION SELECT 'Quinta' UNION SELECT 'Sexta') d WHERE c.modalidade='voleibol';

INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT c.id,e.id,d.dia,'19:30','21:00',7,1 FROM clubes c
JOIN escaloes e ON e.club_id=c.id AND e.nome IN('Juniores','Seniores')
JOIN(SELECT 'Segunda' dia UNION SELECT 'Quarta' UNION SELECT 'Sexta') d WHERE c.modalidade='voleibol';

INSERT IGNORE INTO horarios(club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo)
SELECT c.id,e.id,d.dia,'20:00','21:30',6,1 FROM clubes c
JOIN escaloes e ON e.club_id=c.id AND e.nome='Masters'
JOIN(SELECT 'Terça' dia UNION SELECT 'Sábado') d WHERE c.modalidade='voleibol';

-- ══════════════════════════════════════════════════════════════
-- VERIFICAÇÃO FINAL
-- ══════════════════════════════════════════════════════════════
SELECT 'utilizadores' AS tabela, COUNT(*) AS total FROM utilizadores
UNION ALL SELECT 'clubes',    COUNT(*) FROM clubes
UNION ALL SELECT 'planos',    COUNT(*) FROM planos
UNION ALL SELECT 'escaloes',  COUNT(*) FROM escaloes
UNION ALL SELECT 'horarios',  COUNT(*) FROM horarios
UNION ALL SELECT 'extras',    COUNT(*) FROM extras;