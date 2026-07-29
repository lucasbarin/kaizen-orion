# Sistema Orion Kaizen v2.0

## 🎯 Sobre o Projeto

Sistema de gestão de Kaizen com gamificação através de pontos para colaboradores da Orion Carbons.

**Versão:** 2.0  
**Status:** Produção  
**Última atualização:** 19 de novembro de 2025

**Stack Tecnológico:**
- PHP 8.x (migrado de PHP 5.x)
- MySQL 8.0 (mysqli + UTF-8MB4)
- Bootstrap 5.3.2 (migrado de Bootstrap 3)
- jQuery 1.11.3 + Plugins (maskMoney, inputmask)
- PHPMailer 6.7.1

---

## ⚙️ Configuração de Ambiente

### 1. **Requisitos**
- WAMP/XAMPP/LAMP (PHP 8.0+)
- MySQL 8.0+
- Apache 2.4+

### 2. **Criar Banco de Dados**

Execute no phpMyAdmin:

```sql
CREATE DATABASE IF NOT EXISTS `orionv2_frontend` 
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

**Importar estrutura:**
```sql
-- Use o arquivo SQL em !ARQUIVOS/criar_banco_orionv2_frontend.sql
```

### 3. **Configurar Conexão**

Edite: `app/conexao8.php`

```php
$servidor = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'orionv2_frontend';
```

### 4. **Configurar Email (SMTP)**

Edite: `app/configuracoes.php`

```php
$usuario = "seu-email@dominio.com";
$senha = "sua-senha";
$remetente = "seu-email@dominio.com";
$servidor_smtp = "smtp.dominio.com";
$porta = 587;
$tsl = true;
```

### 5. **Permissões de Pastas**

```bash
chmod -R 755 imgs/
chmod -R 755 imgs/kaizen/
```

---

## 📁 Estrutura do Projeto

### Front-end (Colaboradores)
- `home.php` - Dashboard do colaborador
### Arquivos Core (PHP 8)
- `app/conexao8.php` - Conexão mysqli
- `app/funcoes8.php` - Funções principais
- `app/func_*.php` - Processadores de formulários
- `app/sessao.php` - Autenticação colaborador
- `app/sessao2.php` - Autenticação admin

### Assets
- `assets/css/estilos-v2.css` - CSS centralizado (novo)
- `lib/css/orion-custom.css` - Variáveis e navbar
- `lib/js/` - jQuery + Plugins

### Templates
- `Templates/kaizen.dwt.php` - Layout front-end (Adobe Dreamweaver)

---

## 🆕 Funcionalidades v2.0

### **Sistema de Complementos**
9 tipos de benefícios com cálculo automático:
- **Reais economizados:** valor direto em R$
- **Horas poupadas:** calcula valor em R$ (horas × custo_linha_parada)
- **Kg carbono reduzido:** calcula valor em R$ (kg × produtividade_usd × câmbio)

**Campos salvos:**
- `valor_original_kaizen` - Valor informado pelo usuário
- `tipo_complemento_kaizen` - Tipo de complemento (1, 2 ou 3)
- `complemento_kaizen` - Valor calculado em R$

### **Upload Múltiplo de Anexos**
- Drag-and-drop de arquivos ✨ NOVO
- Formatos: JPG, PNG, PDF, Excel, Word, ZIP
- Máximo: 10MB por arquivo
- Exclusão individual de anexos
- Tabela: `kaizen_anexos`

### **Campos de Descrição**
- `situacao_atual` - Cenário antes da melhoria
- `onde_kaizen` - Objetivo e aplicação
- `resultado_kaizen` - Resultados obtidos

### **Sistema de Pontos**
- Pontos por kaizen aprovado
- Resgate em catálogo de produtos
- Log completo de transações
- Email automático de notificações (PHPMailer 6.7.1)

---

## 🎨 CSS Centralizado

**Arquivo:** `assets/css/estilos-v2.css` (450 linhas)

**Componentes:**
- Headers coloridos (`.analise-header`, `.edit-header`, `.wizard-header`)
- Formulários wizard (`.form-wizard`, `.form-step`)
- Upload drag-and-drop (`.file-upload-area`)
- Gestão de anexos (`.anexos-atuais`)
- Cards e info boxes
- Animações e responsividade

---

## 🔧 Configurações do Sistema

### Variáveis de Cálculo
Edite no admin: `pg-config.php` (tabela `pg`, id=101)

- **Limite de Alerta:** R$ 5.000,00 (campo `nome3_pg`)
- **Custo Linha Parada:** R$/hora (campo `nome4_pg`)
- **Produtividade Carbono:** USD/kg (campo `nome5_pg`)
- **Câmbio USD:** R$/USD (campo `nome6_pg`)

### Fórmulas
```php
// Reais direto
$valor_real = $valor_informado;

// Horas → R$
$valor_real = $horas * $custo_linha_parada;

// Kg carbono → R$
$valor_real = $kg * ($produtividade_usd * $cambio);
```

---

## 🔒 Notas de Segurança

⚠️ **Código legado mantém padrões originais:**
- Senhas em texto simples
- SQL sem prepared statements
- Sem proteção CSRF
- Sessões com IDs estáticos

**Para produção externa:** Implementar password_hash(), PDO, tokens CSRF.

---

## 📚 Documentação Adicional

- `SETUP.md` - Guia rápido de configuração
- `RELATORIO_FINALIZACAO.md` - Status final do projeto
- `docs/historico/` - Logs de migração PHP 8 e Bootstrap 5
- `.github/copilot-instructions.md` - Instruções para IA

---

## 📞 Suporte

**Versão:** 2.0  
**Data:** Novembro 2025  
**Status:** ✅ Produção

Sistema pronto para uso. Backups em `backups/` (frontend e admin).
- Sistema de complementos implementado
- Upload múltiplo de anexos
- Cálculos automáticos de benefícios
- Exibição padronizada de valores

---

## 🚀 Como Usar Este Workspace

1. ✅ Configure o banco `orionv2_frontend`
2. ✅ Atualize `app/conexao8.php`
3. 🎨 Trabalhe nas melhorias de front-end
4. 🔄 Compare com `orion` (original) para validar mudanças
5. ✅ Teste todas as alterações antes de migrar para produção

---

## 📞 Suporte

Para dúvidas sobre a arquitetura ou funcionalidades implementadas, consulte:
- `MIGRACAO_PHP8.md` - Detalhes da migração
- `MIGRACAO_COMPLETA.md` - Histórico completo
- `.github/copilot-instructions.md` - Guia para agentes de IA

---

**Bom trabalho no front-end! 🎨✨**
