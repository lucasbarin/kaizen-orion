# 🚀 Guia de Deploy - Orion Kaizen v2

## 📦 Arquivos para IGNORAR no FTP

### ❌ NÃO ENVIAR para Produção

#### 1. Documentação (Markdown)
```
*.md
SETUP.md
README_FRONTEND.md
RELATORIO_FINALIZACAO.md
docs/
guia/README.md
.github/
```

#### 2. Scripts PowerShell (Windows)
```
*.ps1
atualizar_conexao.ps1
admin/fix-headers.ps1
guia/criar-placeholders.ps1
guia/capturar-telas.ps1
```

#### 3. Arquivos de Desenvolvimento
```
_status.php                    # Status de desenvolvimento
home-bs5-v2.php               # Versão teste/desenvolvimento
.vscode/                      # Configurações VS Code (se existir)
.git/                         # Repositório Git (se existir)
.gitignore                    # Configuração Git
```

#### 4. Backups e Temporários
```
backups/                      # Backups locais
!ARQUIVOS/                    # Arquivos SQL/temporários
*.bak
*.tmp
*.log
```

#### 5. Guias de Usuário (Opcional)
```
guia/                         # Guias HTML de utilização
```
**Decisão:** Se quiser manter guias acessíveis online, envie. Caso contrário, ignore.

---

## ✅ Arquivos OBRIGATÓRIOS para Enviar

### Aplicação Principal
```
✅ *.php                      # Todas as páginas PHP
✅ app/                       # Lógica de negócio
   ├── conexao8.php          # ⚠️ AJUSTAR URL e credenciais
   ├── funcoes8.php
   ├── func_*.php
   └── PHPMailer-6.7.1/
✅ admin/                     # Painel administrativo
✅ Templates/                 # Templates Dreamweaver
✅ lib/                       # Assets (CSS, JS, imagens)
✅ imgs/                      # Uploads (criar vazia se não houver)
✅ assets/                    # Assets extras
```

### Arquivos de Configuração
```
✅ !!!.htaccess              # ⚠️ RENOMEAR para .htaccess
✅ robots.txt
✅ sitemap.xml
```

---

## ⚙️ Checklist de Deploy

### Antes do Upload

- [ ] **Backup do servidor atual** (se houver sistema rodando)
- [ ] **Testar localmente** - tudo funcionando em `localhost`
- [ ] **⚠️ CORRIGIR SHORT TAGS PHP:**
  ```powershell
  cd c:\wamp64\www\orion-v2
  .\fix-short-tags.ps1
  ```
  **Por quê?** Muitos servidores têm `short_open_tag = Off` e não aceitam `<?` (precisa ser `<?php`)
  
- [ ] **Atualizar `app/conexao8.php`:**
  ```php
  $servidor = 'localhost'; // ou IP do banco
  $usuario = 'usuario_producao';
  $senha = 'senha_producao';
  $banco = 'nome_banco_producao';
  $urlSite = 'https://www.orionkaizen.com.br/'; // ⚠️ URL FINAL
  ```
- [ ] **Verificar emails** - SMTP configurado em `app/configuracoes.php`
- [ ] **Limpar dados de teste** (se houver)

### Durante o Upload (FTP)

- [ ] **Conectar via FTP** ao servidor
- [ ] **Criar estrutura de pastas** (se necessário):
  ```
  /public_html/
  ├── app/
  ├── admin/
  ├── Templates/
  ├── lib/
  ├── imgs/
  │   └── kaizen/    # ⚠️ Permissões 755 ou 777
  └── assets/
  ```
- [ ] **Upload seletivo** - ignorar arquivos da lista acima
- [ ] **Renomear `!!!.htaccess` para `.htaccess`**
- [ ] **Ajustar permissões:**
  ```
  imgs/kaizen/          → 755 ou 777 (escrita)
  imgs/produto/         → 755 ou 777 (escrita)
  imgs/colaborador/     → 755 ou 777 (escrita)
  ```

### Após o Upload

- [ ] **Testar login** - front-end e admin
- [ ] **Testar criação de Kaizen**
- [ ] **Verificar upload de imagens**
- [ ] **Testar envio de emails**
- [ ] **Validar URLs** - links corretos
- [ ] **Testar em mobile** - responsividade
- [ ] **Verificar erros PHP** - ativar `error_reporting` temporariamente
- [ ] **SSL/HTTPS** - certificado configurado

---

## 🔧 Configurações Específicas do Servidor

### Locaweb (PHP)
```apache
# .htaccess
AddHandler php80-script .php    # Ajustar versão PHP (56, 70, 80, 81, 82)
suPHP_ConfigPath /home/seuusuario/
```

### Permissões Recomendadas
```
Arquivos PHP:     644
Pastas:           755
Upload (imgs/):   755 ou 777
.htaccess:        644
```

---

## 🚨 Erros Comuns no Deploy

### ❌ Erro 500 - Internal Server Error
**Causas:**
- `.htaccess` com regras incompatíveis
- Versão PHP incorreta no `AddHandler`
- Permissões incorretas
- **Short tags PHP (`<?`) não suportadas** ⚠️

**Solução:**
1. Execute `.\fix-short-tags.ps1` localmente
2. Faça upload dos arquivos corrigidos
3. Se persistir, renomeie `.htaccess` para `.htaccess.bak`
4. Teste se funciona
5. Se funcionar, revise regras do `.htaccess`

### ❌ Tela Branca Após Login (Admin)
**Causas:**
- **Short tags PHP (`<?`) desabilitadas no servidor** (mais comum)
- Erro fatal de PHP não exibido
- Sessão não iniciada

**Solução:**
1. Execute `.\fix-short-tags.ps1` para corrigir todos os `<?` → `<?php`
2. Upload dos arquivos: `admin/app/*.php`, `admin/*.php`, `app/*.php`
3. Se persistir, adicione no topo de `admin/home.php`:
   ```php
   <?php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```
4. Acesse novamente e veja o erro específico

### ❌ Erro de Conexão com Banco
**Causas:**
- Credenciais incorretas em `app/conexao8.php`
- Banco não existe no servidor
- Servidor MySQL diferente de `localhost`

**Solução:**
1. Verifique credenciais no painel do hosting
2. Confirme nome do banco
3. Teste IP do servidor MySQL

### ❌ Upload de Imagens Falha
**Causas:**
- Permissões insuficientes na pasta `imgs/`
- Caminho incorreto no código

**Solução:**
```bash
chmod 755 imgs/kaizen/
chmod 755 imgs/produto/
chmod 755 imgs/colaborador/
```

### ❌ Links nos Emails Quebrados
**Causas:**
- `$urlSite` com URL incorreta em `app/conexao8.php`

**Solução:**
```php
// app/conexao8.php
$urlSite = 'https://www.orionkaizen.com.br/'; // ⚠️ Sempre com / no final
```

---

## 📋 Comandos FTP Úteis

### FileZilla (Recomendado)
```
1. Site Manager → New Site
2. Host: ftp.seudominio.com.br
3. Protocol: FTP
4. Encryption: Use explicit FTP over TLS
5. Logon Type: Normal
6. User/Password: do painel de controle
```

**Filtros de Upload:**
```
Menu: View → Filename filters → Edit filter rules
Adicionar: *.md, *.ps1, *.bak, backups/*, docs/*
```

### Via Terminal (Linux/Mac)
```bash
# Conectar
ftp ftp.seudominio.com.br

# Upload recursivo (sem arquivos ignorados)
mput *.php
cd app
mput *.php
cd ..
# Repetir para cada pasta
```

---

## 🔄 Rollback (Em Caso de Problema)

Se algo der errado:

1. **Restaurar backup** do servidor
2. **Verificar logs de erro** do servidor
3. **Testar versão local** novamente
4. **Deploy incremental** - pasta por pasta

---

## ✅ Deploy Bem-Sucedido

Após confirmar que tudo funciona:

- [ ] Desativar `error_reporting` detalhado
- [ ] Configurar backup automático
- [ ] Monitorar logs de erro
- [ ] Documentar credenciais em local seguro
- [ ] Informar equipe sobre novo sistema

---

**🎉 Sistema em Produção! Bom trabalho!**
