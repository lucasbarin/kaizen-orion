-- Script para adicionar campos de rastreamento de valor original
-- Execute este script no phpMyAdmin para o banco orionv2

-- Adicionar campo para guardar o valor original informado (kg carbono, horas ou R$ direto)
ALTER TABLE `kaizen` 
ADD COLUMN `valor_original_kaizen` DECIMAL(10,2) DEFAULT 0 COMMENT 'Valor original informado pelo colaborador antes do cálculo' AFTER `complemento_kaizen`;

-- Adicionar campo para identificar qual tipo de complemento foi usado
ALTER TABLE `kaizen` 
ADD COLUMN `tipo_complemento_kaizen` INT DEFAULT 0 COMMENT 'ID do complemento usado (0=nenhum, 1=Reais, 2=Horas, 3=Kg carbono)' AFTER `valor_original_kaizen`;
