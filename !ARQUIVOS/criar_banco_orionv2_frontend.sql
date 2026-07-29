-- Script para criar banco de dados para a versão de testes do front-end
-- Execute este script no phpMyAdmin

-- Criar novo banco
CREATE DATABASE IF NOT EXISTS `orionv2_frontend` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- IMPORTANTE: Depois de criar, vá em phpMyAdmin:
-- 1. Selecione o banco 'orionv2'
-- 2. Clique em "Operações" → "Copiar banco de dados para:"
-- 3. Digite: orionv2_frontend
-- 4. Marque: "Estrutura e dados"
-- 5. Clique em "Executar"

-- Ou use este comando SQL (pode levar alguns minutos):
-- mysqldump -u root orionv2 | mysql -u root orionv2_frontend
