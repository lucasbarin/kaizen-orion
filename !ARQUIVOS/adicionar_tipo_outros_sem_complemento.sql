-- Adicionar novo tipo "Outros" sem complemento (similar a Segurança)
-- Sistema Orion Kaizen v2.1
-- Data: 11/12/2025

-- Inserir novo tipo "Outros" sem complemento (complemento_tipo = 0, add_tipo = 0)
INSERT INTO `tipo` (`id_tipo`, `nome_tipo`, `numero_tipo`, `status_tipo`, `complemento_tipo`, `add_tipo`) 
VALUES (10, 'Outros', 10, 0, 0, 0);

-- Verificar tipos cadastrados
SELECT id_tipo, nome_tipo, numero_tipo, complemento_tipo, add_tipo FROM tipo ORDER BY numero_tipo;
