# Deploy para Produção - Novo Tipo "Outros"
**Sistema Orion Kaizen v2.1**  
**Data:** 11/12/2025

---

## 🚀 PASSO A PASSO PARA PRODUÇÃO

### ⚠️ IMPORTANTE: Alteração Somente no Banco de Dados
**NÃO é necessário enviar arquivos PHP!** O sistema já está preparado para tipos sem complemento.

---

## 📋 ETAPA 1: Backup (Obrigatório)

### 1.1 Exportar tabela `tipo` antes da alteração:

**Via phpMyAdmin:**
1. Acesse phpMyAdmin em produção
2. Selecione o banco de dados (`orionkaizen` ou `orionv2_frontend`)
3. Clique na tabela `tipo`
4. Clique em "Exportar"
5. Selecione "SQL"
6. Salve como: `backup_tipo_antes_alteracao_11-12-2025.sql`

**Via SQL (se tiver acesso direto):**
```sql
SELECT * FROM tipo 
ORDER BY numero_tipo 
INTO OUTFILE '/tmp/backup_tipo_20251211.csv'
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n';
```

---

## 📝 ETAPA 2: Executar SQL em Produção

### Opção A: Via phpMyAdmin (Recomendado)

1. **Acesse phpMyAdmin** no servidor de produção
2. **Selecione o banco de dados** (normalmente `orionkaizen` ou `orionv2_frontend`)
3. Clique na aba **SQL** no topo
4. **Cole o SQL abaixo:**

```sql
-- Adicionar novo tipo "Outros" sem complemento
-- Similar ao tipo "Segurança" (não exige R$ ou horas)
INSERT INTO `tipo` (`id_tipo`, `nome_tipo`, `numero_tipo`, `status_tipo`, `complemento_tipo`, `add_tipo`) 
VALUES (10, 'Outros', 10, 0, 0, 0);

-- Verificar se foi inserido corretamente
SELECT id_tipo, nome_tipo, numero_tipo, complemento_tipo, add_tipo 
FROM tipo 
ORDER BY numero_tipo;
```

5. Clique em **Executar**
6. **Verifique a mensagem de sucesso:** "1 linha inserida"
7. **Confira o resultado** da query SELECT na parte inferior

### Opção B: Via Terminal SSH

Se você tiver acesso SSH ao servidor:

```bash
# Conectar ao MySQL
mysql -u SEU_USUARIO -p NOME_DO_BANCO

# Executar o INSERT
INSERT INTO `tipo` (`id_tipo`, `nome_tipo`, `numero_tipo`, `status_tipo`, `complemento_tipo`, `add_tipo`) 
VALUES (10, 'Outros', 10, 0, 0, 0);

# Verificar
SELECT id_tipo, nome_tipo, numero_tipo, complemento_tipo, add_tipo FROM tipo ORDER BY numero_tipo;

# Sair
EXIT;
```

---

## ✅ ETAPA 3: Validação Pós-Deploy

### 3.1 Verificar no Banco de Dados

Execute esta query para confirmar:

```sql
SELECT id_tipo, nome_tipo, complemento_tipo, add_tipo 
FROM tipo 
WHERE id_tipo = 10;
```

**Resultado esperado:**
```
+---------+-----------+------------------+----------+
| id_tipo | nome_tipo | complemento_tipo | add_tipo |
+---------+-----------+------------------+----------+
|      10 | Outros    |                0 |        0 |
+---------+-----------+------------------+----------+
```

### 3.2 Testar no Sistema Web

#### Teste 1: Formulário de Novo Kaizen
1. Acesse: `https://SEU_DOMINIO/novo-kaizen.php`
2. No campo "Selecione o tipo de benefício", role até o final
3. **Verifique:** Deve aparecer "Outros" como última opção
4. **Selecione** o novo "Outros"
5. **Verifique:** NÃO deve aparecer campos de R$ ou horas
6. **Verifique:** NÃO deve aparecer campo "Classifique a ideia"

#### Teste 2: Criar Kaizen Teste
1. Preencha o formulário completo com o novo tipo "Outros"
2. Adicione descrições obrigatórias
3. Anexe pelo menos 1 arquivo
4. **Envie** o kaizen
5. **Resultado esperado:** "Sugestão cadastrada com sucesso!"

#### Teste 3: Visualizar Kaizen Criado
1. Acesse "Meus Kaizens" ou clique no kaizen criado
2. **Verifique:** Tipo aparece como "Outros"
3. **Verifique:** Card "Benefício Anual" NÃO deve aparecer
4. **Verifique:** Demais informações exibidas corretamente

#### Teste 4: Admin - Buscar Kaizens
1. Acesse: `https://SEU_DOMINIO/admin/kaizen-busca.php`
2. Clique em "Filtros"
3. **Verifique:** Tipo "Outros" aparece na lista de filtros
4. Filtre pelo novo tipo
5. **Verifique:** Kaizen aparece na tabela
6. **Verifique:** Coluna de valores mostra "-" (sem valores)

#### Teste 5: Gráficos
1. Na mesma página (kaizen-busca.php)
2. **Verifique:** Gráfico "Kaizens por Tipo" inclui "Outros"
3. **Verifique:** Gráfico "Benefícios em R$" NÃO inclui este tipo (correto)
4. **Verifique:** Gráfico "Horas Economizadas" NÃO inclui este tipo (correto)

#### Teste 6: Exportar Excel
1. Filtre pelo tipo "Outros" (ID 10)
2. Clique em "Exportar para Excel"
3. Abra o arquivo
4. **Verifique:** Colunas de valores (R$ e Horas) ficam vazias
5. **Verifique:** Demais dados exportados corretamente

---

## 🔄 ETAPA 4: Rollback (Se Necessário)

Se algo der errado e precisar reverter:

```sql
-- Remover o tipo inserido
DELETE FROM tipo WHERE id_tipo = 10;

-- Verificar se foi removido
SELECT id_tipo, nome_tipo FROM tipo ORDER BY numero_tipo;
```

**⚠️ ATENÇÃO:** Se já existirem kaizens cadastrados com o tipo 10, você precisará:
1. Primeiro alterar os kaizens para outro tipo:
```sql
UPDATE kaizen SET tipo_kaizen = 1 WHERE tipo_kaizen = 10;
```
2. Depois deletar o tipo:
```sql
DELETE FROM tipo WHERE id_tipo = 10;
```

---

## 📊 CHECKLIST DE VALIDAÇÃO

- [ ] Backup da tabela `tipo` realizado
- [ ] SQL executado com sucesso em produção
- [ ] Query SELECT confirma ID 10 inserido
- [ ] Novo tipo aparece no formulário novo-kaizen.php
- [ ] Selecionando o tipo, campos de valores NÃO aparecem
- [ ] Kaizen teste criado com sucesso
- [ ] Visualização do kaizen OK (sem card de benefício)
- [ ] Tipo aparece nos filtros do admin
- [ ] Gráficos exibem corretamente
- [ ] Exportação Excel funciona corretamente

---

## 🕐 MELHOR HORÁRIO PARA DEPLOY

**Recomendação:** Execute fora do horário de pico

- ✅ Ideal: Após expediente ou fim de semana
- ✅ Tempo estimado: **2 minutos** (apenas SQL)
- ✅ Impacto: **ZERO** (não afeta sistema em uso)
- ✅ Downtime: **Nenhum** (alteração em tempo real)

---

## 📞 SUPORTE

### Se encontrar problemas:

**Erro: Duplicate entry '10' for key 'PRIMARY'**
- Causa: ID 10 já existe
- Solução: Altere o ID no SQL para 11, 12, etc.

**Erro: Table 'tipo' doesn't exist**
- Causa: Banco de dados errado
- Solução: Verifique o nome correto do banco

**Tipo não aparece no formulário**
- Causa: Cache do navegador
- Solução: Limpe o cache (Ctrl+Shift+Del) e recarregue

**Campos de R$ aparecem mesmo no tipo novo**
- Causa: complemento_tipo diferente de 0
- Solução: Execute:
```sql
UPDATE tipo SET complemento_tipo = 0, add_tipo = 0 WHERE id_tipo = 10;
```

---

## 📄 ARQUIVOS DE REFERÊNCIA

No desenvolvimento (localhost), foram criados:

1. **SQL para produção:**
   - `C:\wamp64\www\orion-v2\!ARQUIVOS\adicionar_tipo_outros_sem_complemento.sql`

2. **Documentação técnica:**
   - `C:\wamp64\www\orion-v2\!ARQUIVOS\ADICAO_TIPO_OUTROS_SEM_COMPLEMENTO.md`

---

## ✅ CONCLUSÃO

Esta alteração é **extremamente simples e segura**:

- ✅ Apenas 1 INSERT no banco de dados
- ✅ Não requer envio de arquivos PHP
- ✅ Sistema já compatível (desde v1.0 com tipo "Segurança")
- ✅ Zero risco de quebrar funcionalidades existentes
- ✅ Rollback trivial (1 DELETE)
- ✅ Tempo de execução: < 2 minutos

**Pronto para produção!** 🚀
