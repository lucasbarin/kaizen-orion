-- =====================================================
-- Script de Atualização: Sistema de Valores Kaizen v2.1
-- Data: 01/12/2025
-- Descrição: Remove cálculo automático e permite entrada direta de R$ ou horas
-- =====================================================

-- IMPORTANTE: Fazer backup do banco antes de executar!
-- Comando: mysqldump -u root -p orionv2_frontend > backup_antes_atualizacao.sql

USE orionv2_frontend;

-- =====================================================
-- 1. ATUALIZAR TABELA comp
-- =====================================================

-- Adicionar nova coluna para tipo de entrada
ALTER TABLE `comp` 
ADD COLUMN `tipo_entrada` VARCHAR(10) NOT NULL DEFAULT 'reais' 
COMMENT 'Tipo de entrada: reais, horas ou nenhum'
AFTER `formato_comp`;

-- Migrar dados de calculo_comp para tipo_entrada
UPDATE `comp` SET `tipo_entrada` = 'reais' WHERE `id_comp` = 1;
UPDATE `comp` SET `tipo_entrada` = 'reais' WHERE `id_comp` = 2;
UPDATE `comp` SET `tipo_entrada` = 'horas' WHERE `id_comp` = 3;

-- Remover coluna antiga (após confirmar que migração funcionou)
-- DESCOMENTAR APENAS APÓS TESTAR:
-- ALTER TABLE `comp` DROP COLUMN `calculo_comp`;

-- =====================================================
-- 2. GARANTIR FORMATO CORRETO
-- =====================================================

-- Comp 1 e 2 devem aceitar decimais (formato_comp = 2)
UPDATE `comp` SET `formato_comp` = 2 WHERE `id_comp` IN (1, 2);

-- Comp 3 pode ser inteiro ou decimal (deixar como está)
-- Se quiser aceitar decimais em horas também: UPDATE `comp` SET `formato_comp` = 2 WHERE `id_comp` = 3;

-- =====================================================
-- 3. ATUALIZAR TABELA kaizen
-- =====================================================

-- Adicionar comentário explicativo no campo complemento_kaizen
ALTER TABLE `kaizen` 
MODIFY COLUMN `complemento_kaizen` DECIMAL(10,2) DEFAULT 0 
COMMENT 'DEPRECADO v2.1 - Usar valor_original_kaizen';

-- =====================================================
-- 4. MIGRAÇÃO DE DADOS ANTIGOS
-- =====================================================

-- Migrar kaizens antigos que têm complemento_kaizen mas não têm valor_original_kaizen
UPDATE `kaizen` 
SET `valor_original_kaizen` = `complemento_kaizen` 
WHERE `complemento_kaizen` > 0 
  AND (`valor_original_kaizen` = 0 OR `valor_original_kaizen` IS NULL);

-- =====================================================
-- 5. VERIFICAÇÕES
-- =====================================================

-- Verificar estrutura da tabela comp
SELECT * FROM `comp` ORDER BY `id_comp`;

-- Verificar quantos kaizens foram migrados
SELECT 
    COUNT(*) as total_migrados,
    SUM(CASE WHEN valor_original_kaizen > 0 THEN 1 ELSE 0 END) as com_valor,
    SUM(CASE WHEN complemento_kaizen > 0 THEN 1 ELSE 0 END) as com_complemento_antigo
FROM `kaizen`;

-- Verificar distribuição por tipo de complemento
SELECT 
    tipo_complemento_kaizen,
    COUNT(*) as quantidade,
    SUM(valor_original_kaizen) as soma_valores
FROM `kaizen`
WHERE status_kaizen = 2
GROUP BY tipo_complemento_kaizen;

-- =====================================================
-- FIM DO SCRIPT
-- =====================================================

-- PRÓXIMOS PASSOS:
-- 1. Verificar se todas as queries executaram sem erros
-- 2. Validar os resultados das queries de verificação
-- 3. Testar cadastro de novo kaizen
-- 4. Se tudo OK, pode descomentar a linha de DROP COLUMN calculo_comp
