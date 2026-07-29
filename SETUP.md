# 🚀 Guia Rápido de Configuração - orion-v2

## ✅ Checklist de Configuração

### 1. ✅ Cópia do Diretório
- [x] Diretório copiado de `orion` para `orion-v2`

### 2. ⏳ Criar Banco de Dados

**Abra phpMyAdmin:** `http://localhost/phpmyadmin`

**Execute no SQL:**
```sql
CREATE DATABASE IF NOT EXISTS `orionv2_frontend` 
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

### 3. ⏳ Copiar Dados do Banco

**No phpMyAdmin:**
1. Clique no banco `orionv2` (à esquerda)
2. Vá na aba **"Operações"** (topo)
3. Encontre **"Copiar banco de dados para:"**
4. Digite: `orionv2_frontend`
5. Marque: ✅ **"Estrutura e dados"**
6. Clique em **"Executar"**

⏱️ Aguarde alguns segundos... Done! ✅

### 4. ⏳ Atualizar Conexão e URL

**Opção A - Script Automático (Recomendado):**
```powershell
cd c:\wamp64\www\orion-v2
.\atualizar_conexao.ps1
```

**Opção B - Manual:**
1. Abra: `c:\wamp64\www\orion-v2\app\conexao8.php`
2. Encontre as linhas:
   ```php
   $banco = "orionv2";
   $urlSite = 'https://localhost/orion-v2/';
   ```
3. Mude para:
   ```php
   $banco = "orionv2_frontend";
   $urlSite = 'http://localhost/orion-v2/';  // Ajuste conforme seu ambiente
   ```
4. Salve (Ctrl+S)

**⚠️ Importante - URL do Site:**
- **Desenvolvimento local:** `http://localhost/orion-v2/`
- **Produção:** `https://www.orionkaizen.com.br/`
- Esta URL é usada nos emails enviados pelo sistema

### 5. ⏳ Abrir no VS Code

**No VS Code:**
- File → Open Folder → `c:\wamp64\www\orion-v2`

Ou via PowerShell:
```powershell
code c:\wamp64\www\orion-v2
```

### 6. ✅ Testar Acesso

Abra no navegador:
- **Front-end:** `http://localhost/orion-v2/`
- **Admin:** `http://localhost/orion-v2/admin/`

**Login de teste:**
- Usuário: (mesmo do sistema original)
- Senha: (mesma do sistema original)

### 7. ⏳ Configurar .htaccess (Produção)

**Para ambiente local (WAMP):**
- Não é necessário alterar o arquivo `!!!.htaccess`
- O WAMP usa URLs diretas como `http://localhost/orion-v2/`

**Para produção (Locaweb/servidor Linux):**
1. Renomeie `!!!.htaccess` para `.htaccess`
2. Edite conforme necessário:
   ```apache
   # Remove extensão .php das URLs
   RewriteEngine On
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteRule ^([^\.]+)$ $1.php [NC,L]
   
   # Redireciona para www
   RewriteCond %{HTTP_HOST} ^orionkaizen.com.br [NC]
   RewriteRule ^(.*)$ http://www.orionkaizen.com.br/$1 [L,R=301]
   ```
3. Não esqueça de atualizar `$urlSite` em `app/conexao8.php` para a URL de produção

**⚠️ Nota:** O arquivo está com `!!!` no início para evitar conflitos no ambiente local

---

## 🎨 Pronto para Trabalhar no Front-End!

### Arquivos Principais para Modificar:

**CSS:**
- `lib/css/style.css` - Estilos gerais
- `lib/css/menu.css` - Menu responsivo
- `lib/css/tabela.css` - Tabelas

**JavaScript:**
- `lib/js/functions.js` - Funções gerais

**Páginas Colaborador:**
- `home.php` - Dashboard
- `novo-kaizen.php` - Formulário
- `visualizar-kaizen.php` - Visualização
- `minhas-sugestoes.php` - Listagem

**Template:**
- `Templates/kaizen.dwt.php` - Layout base

---

## 🌍 Configuração de Ambientes

### Desenvolvimento Local (WAMP)
```php
// app/conexao8.php
$servidor = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'orionv2_frontend';
$urlSite = 'http://localhost/orion-v2/';
```
- `.htaccess` deve estar como `!!!.htaccess` (desabilitado)
- Acesso via: `http://localhost/orion-v2/`

### Produção (Locaweb/Linux)
```php
// app/conexao8.php
$servidor = 'localhost'; // ou IP do servidor
$usuario = 'usuario_db';
$senha = 'senha_db';
$banco = 'nome_banco_producao';
$urlSite = 'https://www.orionkaizen.com.br/';
```
- Renomear `!!!.htaccess` para `.htaccess`
- Ajustar regras de rewrite no `.htaccess`
- Configurar SSL/HTTPS
- Verificar `AddHandler` para versão PHP correta

**⚠️ Atenção:** Nunca commite senhas reais no repositório!

---

## 🔄 Workflow Recomendado

1. **Faça alterações** em `orion-v2`
2. **Teste** em `http://localhost/orion-v2/`
3. **Compare** com `orion` original
4. **Valide** que não quebrou nada
5. **Documente** as mudanças
6. **Quando estiver OK**, aplique no `orion` original

---

## 📁 Estrutura de Pastas

```
orion-v2/
├── app/                    # Core PHP
│   ├── conexao8.php       # ⚠️ ATUALIZAR: orionv2_frontend
│   ├── funcoes8.php       # Funções principais
│   └── func_*.php         # Processadores
├── lib/                    # Assets front-end
│   ├── css/               # 🎨 Estilos para modificar
│   ├── js/                # 🎨 Scripts para modificar
│   └── img/               # Imagens
├── Templates/             # Layouts Dreamweaver
│   └── kaizen.dwt.php    # 🎨 Template base
├── admin/                 # Painel admin
├── imgs/                  # Uploads
└── *.php                  # Páginas front-end
```

---

## ⚡ Dicas de Produtividade

### VS Code - Comparar Projetos
```
Ctrl+Shift+P → "Compare Active File with..."
```

### Chrome DevTools
```
F12 → Console (ver erros JS)
F12 → Network (ver requisições AJAX)
F12 → Elements (inspecionar CSS)
```

### Live Reload
Use extensão do VS Code: **"Live Server"** ou **"PHP Server"**

---

## 🆘 Problemas Comuns

### ❌ Erro de Conexão
- Verifique `app/conexao8.php` → `$banco = "orionv2_frontend"`
- Confirme que o banco foi copiado no phpMyAdmin
- Verifique se o WAMP está rodando (ícone verde na bandeja)

### ❌ CSS não atualiza
- Limpe cache do navegador: `Ctrl+Shift+Del`
- Ou use `Ctrl+F5` (hard refresh)

### ❌ Upload de arquivos falha
- Verifique permissões da pasta `imgs/kaizen/`
- PowerShell: `icacls "c:\wamp64\www\orion-v2\imgs\kaizen" /grant Everyone:F`

### ❌ Links nos emails quebrados
- Verifique `app/conexao8.php` → `$urlSite` está correto
- Para testes locais: `http://localhost/orion-v2/`
- Para produção: `https://www.orionkaizen.com.br/`

### ❌ .htaccess causando erro 500
- Em ambiente local (WAMP), mantenha como `!!!.htaccess`
- Somente renomeie para `.htaccess` em produção (Linux/Apache)

---

## ✅ Status da Configuração

Marque conforme concluir:

- [ ] Banco `orionv2_frontend` criado
- [ ] Dados copiados do `orionv2`
- [ ] `app/conexao8.php` atualizado (banco + URL)
- [ ] URL `$urlSite` configurada corretamente
- [ ] `.htaccess` configurado (se produção)
- [ ] Testado acesso em `http://localhost/orion-v2/`
- [ ] VS Code aberto em `orion-v2`
- [ ] Primeiro kaizen de teste criado
- [ ] Emails funcionando (verificar URL nos links)

---

**Tudo pronto? Bora trabalhar! 🚀🎨**
