# Migração PHP 5.x → PHP 8.x - Sistema Orion Kaizen

## ✅ O que foi feito

### 1. Arquivos de Configuração Atualizados
- ✅ `app/conexao8.php` - Usa `mysqli_connect()` ao invés de `mysql_connect()`
- ✅ `app/funcoes8.php` - Todas as funções atualizadas para mysqli_*
- ✅ Função `sql($query, $con)` agora requer o segundo parâmetro `$con`

### 2. Arquivos Migrados Manualmente

**Raiz do projeto (✅ 100%):**
- index.php
- home.php
- novo-kaizen.php
- categorias.php
- analisar-kaizen.php
- altera-senha.php
- corrigir.php
- esqueci_senha.php
- extrato-pontos.php
- kaizen-editar.php
- lider-analisar.php
- minhas-sugestoes.php
- minhas-trocas.php
- sugestoes-analisar.php
- visualizar-kaizen.php

**Diretório app/ (✅ 100%):**
- func_login.php
- func_altera_senha.php
- func_cadastra_kaizen.php
- func_edita_kaizen.php
- func_esqueci_senha.php
- func_kaizen_analisar.php
- func_pontos_trocar.php

**Diretório admin/ (✅ Principais):**
- index.php
- home.php
- kaizen-admin.php
- kaizen-editar.php
- kaizen-busca.php

### 3. Script de Migração Automática Criado
📄 **Arquivo:** `admin/migrate_to_php8.php`

Este script faz automaticamente:
- ✅ Substitui `conexao.php` → `conexao8.php`
- ✅ Substitui `funcoes.php` → `funcoes8.php`
- ✅ Converte `mysql_*` → `mysqli_*`
- ✅ Adiciona parâmetro `$con` nas chamadas `sql()`

## 🚀 Como Executar a Migração Completa

### Passo 1: Iniciar o WAMP
```
1. Inicie o WAMP Server
2. Certifique-se de que o Apache e MySQL estão rodando
3. Verifique se está usando PHP 8.x (clique no ícone do WAMP → PHP → Version)
```

### Passo 2: Executar o Script de Migração
```
1. Abra seu navegador
2. Acesse: http://localhost/orion/admin/migrate_to_php8.php
3. Aguarde a execução (pode levar 1-2 minutos)
4. Anote as estatísticas mostradas
```

O script irá processar automaticamente:
- ~200+ arquivos PHP
- Todos os diretórios (raiz, app, admin, admin/app)
- Excluindo: assets, gerador, import, PHPMailer (não precisam migração)

### Passo 3: Testar o Sistema
```
1. Teste o login: http://localhost/orion/
2. Teste o admin: http://localhost/orion/admin/
3. Teste cadastro de Kaizen
4. Teste sistema de pontos
5. Verifique extrato de pontos
```

### Passo 4: Limpar Arquivos de Migração
```
Após confirmar que tudo funciona, DELETE:
- admin/migrate_to_php8.php
- admin/migrate_includes.php
```

## ⚠️ Pontos de Atenção

### Chamadas sql() SEMPRE com $con
```php
// ❌ ERRADO (PHP 5)
$sql = sql("SELECT * FROM colaborador");

// ✅ CORRETO (PHP 8)
$sql = sql("SELECT * FROM colaborador", $con);
```

### Funções mysqli_* - Ordem dos Parâmetros
```php
// mysqli_query: conexão VEM PRIMEIRO
mysqli_query($con, $query)  // ✅ CORRETO

// mysqli_fetch_*, mysqli_num_rows: SEM $con
mysqli_fetch_array($result)  // ✅ CORRETO
mysqli_num_rows($result)     // ✅ CORRETO

// mysqli_error, mysqli_insert_id: COM $con
mysqli_error($con)          // ✅ CORRETO
mysqli_insert_id($con)      // ✅ CORRETO
```

### Arquivos que NÃO foram migrados (não precisam)
- `lib/` - Bibliotecas JavaScript
- `admin/assets/` - Bibliotecas de terceiros
- `admin/gerador/` - Gerador de código
- `app/PHPMailer_v5.1/` - PHPMailer funciona em PHP 8

## 🔍 Verificar Manualmente Após Migração

### 1. Arquivo app/sessao.php
Certifique-se de que está usando conexao8 e funcoes8:
```php
// Deve ter no início:
$sqlusu = sql("SELECT * FROM colaborador WHERE id_colaborador = ...", $con);
```

### 2. Arquivos de Email (app/mail_*.php)
Verifique se usam conexao8/funcoes8 e sql() com $con

### 3. Admin - Arquivos Restantes
Se o script automático não pegou todos, migre manualmente:
```
admin/colaborador-*.php
admin/produto-*.php
admin/troca-*.php
admin/setor-*.php
admin/unidade-*.php
admin/lidersetor-*.php
admin/app/func_*.php (restantes)
```

## 📝 Documentação Atualizada

✅ `.github/copilot-instructions.md` foi atualizado com:
- Indicação de migração para PHP 8.x
- Uso obrigatório de conexao8.php e funcoes8.php
- Documentação de mysqli_* ao invés de mysql_*
- Obrigatoriedade do parâmetro $con em sql()

## 🐛 Solução de Problemas Comuns

### Erro: "Call to undefined function mysql_connect()"
**Solução:** Arquivo ainda está usando conexao.php. Trocar para conexao8.php

### Erro: "Warning: mysqli_query() expects at least 2 parameters"
**Solução:** Falta passar $con como primeiro parâmetro

### Erro: "Call to undefined function sql()"
**Solução:** Arquivo está usando funcoes.php. Trocar para funcoes8.php

### Erro: "Too few arguments to function sql()"
**Solução:** Adicionar $con como segundo parâmetro: `sql($query, $con)`

## ✨ Benefícios da Migração

1. ✅ Compatibilidade com PHP 8.x
2. ✅ Melhor performance (mysqli é mais rápido)
3. ✅ Suporte a UTF-8MB4 (emojis, caracteres especiais)
4. ✅ Prepared statements disponíveis (segurança futura)
5. ✅ Sistema preparado para hospedagem moderna

## 📞 Suporte

Se encontrar problemas:
1. Verifique os logs de erro do PHP (WAMP → PHP → php_error.log)
2. Ative display_errors em php.ini temporariamente
3. Verifique os includes no topo de cada arquivo problema
4. Certifique-se de que todas as chamadas sql() têm $con

---

**Criado em:** 11/11/2025
**Versão PHP Origem:** 5.x
**Versão PHP Destino:** 8.x
**Status:** ✅ Pronto para execução
