<?php
include("app/sessao.php");
include("../app/conexao8.php");
include("../app/funcoes8.php");
$link_menu = 1;

$id_login = 1;

if (!empty($id_login) && is_numeric($id_login)) {
    $sql = mysqli_query($con, "SELECT * FROM login WHERE id_login = ".$id_login." LIMIT 1") or die(mysqli_error($con));
    
    if (mysqli_num_rows($sql) > 0) {
        $dados = mysqli_fetch_array($sql);
    } else {
        volta("erro", "Login não encontrado!", "home.php");
    } 
} else {
    volta("erro", "Login não encontrado!", "home.php");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<!-- InstanceBegin template="/Templates/admin-bs5.dwt.php" -->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Login e Senha - Painel Administrativo - Sistema Orion</title>
<!-- InstanceEndEditable -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="../lib/css/admin-orion.css" rel="stylesheet">
<!-- InstanceBeginEditable name="head" -->
<style>
.security-badge {
    background: linear-gradient(135deg, #00812e 0%, #006624 100%);
    color: white;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.security-badge i {
    font-size: 2.5rem;
    opacity: 0.9;
}

.security-badge-content h5 {
    margin: 0 0 5px 0;
    font-weight: 600;
}

.security-badge-content p {
    margin: 0;
    font-size: 0.9rem;
    opacity: 0.95;
}

.password-strength-meter {
    height: 5px;
    background: #e0e0e0;
    border-radius: 10px;
    margin-top: 8px;
    overflow: hidden;
}

.password-strength-bar {
    height: 100%;
    width: 0%;
    transition: all 0.3s;
    border-radius: 10px;
}

.password-strength-bar.weak {
    width: 33%;
    background: #dc3545;
}

.password-strength-bar.medium {
    width: 66%;
    background: #ffc107;
}

.password-strength-bar.strong {
    width: 100%;
    background: #00812e;
}

.password-match-indicator {
    font-size: 0.875rem;
    margin-top: 5px;
    display: none;
}

.password-match-indicator.match {
    color: #00812e;
    display: block;
}

.password-match-indicator.no-match {
    color: #dc3545;
    display: block;
}

.form-group-password {
    position: relative;
}

.password-toggle-btn {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: transparent;
    color: #666;
    cursor: pointer;
    z-index: 10;
}

.password-toggle-btn:hover {
    color: var(--orion-primary);
}

.security-tips {
    background: #f8f9fa;
    border-left: 4px solid var(--orion-primary);
    padding: 15px;
    border-radius: 8px;
    margin-top: 20px;
}

.security-tips h6 {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 10px;
    color: #333;
}

.security-tips ul {
    margin: 0;
    padding-left: 20px;
    font-size: 0.85rem;
    color: #666;
}

.security-tips ul li {
    margin-bottom: 5px;
}
</style>
<!-- InstanceEndEditable -->
</head>
<body>
<div id="preloader">
    <div class="loader"></div>
</div>

<div class="admin-wrapper">
    <!-- Header -->
    <header class="admin-header">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        
        <a href="../" class="logo">
            <img src="assets/admin/layout/img/logo.png" alt="Orion Kaizen" onerror="this.style.display='none'">
            <span>ORION KAIZEN</span>
        </a>
        
        <div class="header-right">
            <a href="app/logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Sair</span>
            </a>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="sidebar">
        <?php include("menu.php"); ?>
    </aside>

    <!-- Main Content -->
    <main class="admin-content">
        <!-- InstanceBeginEditable name="content" -->
        <?php resp($_GET['resp'] ?? null, $_GET['msg'] ?? null, $_GET['tipo'] ?? null); ?>
        
        <div class="page-header">
            <div>
                <h1><i class="fas fa-key"></i> Login e Senha</h1>
                <p class="text-muted">Acesso ao painel administrativo</p>
            </div>
        </div>

        <div class="security-badge">
            <i class="fas fa-shield-alt"></i>
            <div class="security-badge-content">
                <h5>Segurança da Conta</h5>
                <p>Mantenha suas credenciais seguras. Use uma senha forte e única para proteger o acesso ao sistema.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h5><i class="fas fa-user-lock"></i> Alterar Credenciais</h5>
                    </div>
                    <div class="admin-card-body">
                        <form action="app/func_login_editar.php" method="post" id="formulario">
                            <input name="id_login" type="hidden" value="<?php echo $dados['id_login']; ?>">
                            <input name="senha_antiga" type="hidden" value="<?php echo $dados['senha_login']; ?>">
                            
                            <div class="mb-4">
                                <label for="nome_login" class="form-label fw-bold">
                                    <i class="fas fa-user text-primary"></i> Login
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       name="nome_login" 
                                       id="nome_login" 
                                       value="<?php echo $dados['nome_login']; ?>" 
                                       maxlength="15" 
                                       required>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> Nome de usuário para acessar o painel
                                </small>
                            </div>

                            <hr class="my-4">

                            <div class="mb-4">
                                <label for="senha_login" class="form-label fw-bold">
                                    <i class="fas fa-lock text-primary"></i> Nova Senha
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="form-group-password">
                                    <input type="password" 
                                           class="form-control form-control-lg" 
                                           name="senha_login" 
                                           id="senha_login" 
                                           maxlength="15">
                                    <button type="button" class="password-toggle-btn" onclick="togglePassword('senha_login')">
                                        <i class="fas fa-eye" id="senha_login_icon"></i>
                                    </button>
                                </div>
                                <div class="password-strength-meter">
                                    <div class="password-strength-bar" id="strength-bar"></div>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-info-circle"></i> Deixe em branco se não quiser alterar a senha
                                </small>
                            </div>

                            <div class="mb-4">
                                <label for="senha_again" class="form-label fw-bold">
                                    <i class="fas fa-check-double text-primary"></i> Repita a Nova Senha
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="form-group-password">
                                    <input type="password" 
                                           class="form-control form-control-lg" 
                                           name="senha_again" 
                                           id="senha_again" 
                                           maxlength="15">
                                    <button type="button" class="password-toggle-btn" onclick="togglePassword('senha_again')">
                                        <i class="fas fa-eye" id="senha_again_icon"></i>
                                    </button>
                                </div>
                                <div class="password-match-indicator" id="match-indicator"></div>
                            </div>

                            <div class="security-tips">
                                <h6><i class="fas fa-lightbulb"></i> Dicas de Segurança</h6>
                                <ul>
                                    <li>Use uma senha com pelo menos 8 caracteres</li>
                                    <li>Combine letras maiúsculas, minúsculas, números e símbolos</li>
                                    <li>Evite informações pessoais óbvias (nome, data de nascimento)</li>
                                    <li>Não compartilhe suas credenciais com ninguém</li>
                                    <li>Altere sua senha periodicamente</li>
                                </ul>
                            </div>
                        </form>
                    </div>
                    <div class="admin-card-footer">
                        <button type="submit" form="formulario" class="btn btn-orion-success btn-lg">
                            <i class="fas fa-save"></i> Salvar Alterações
                        </button>
                        <a href="home.php" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h5><i class="fas fa-info-circle"></i> Informações</h5>
                    </div>
                    <div class="admin-card-body">
                        <div class="mb-3">
                            <strong class="d-block mb-2">
                                <i class="fas fa-user text-primary"></i> Login Atual
                            </strong>
                            <div class="badge bg-light text-dark fs-6">
                                <?php echo $dados['nome_login']; ?>
                            </div>
                        </div>

                        <hr>

                        <div class="alert alert-info mb-0">
                            <i class="fas fa-exclamation-circle"></i>
                            <strong>Atenção:</strong>
                            <p class="mb-0 mt-2 small">
                                Após alterar suas credenciais, você será desconectado e precisará fazer login novamente com os novos dados.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- InstanceEndEditable -->
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../lib/js/admin-base.js"></script>
<!-- InstanceBeginEditable name="scripts" -->
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/localization/messages_pt_BR.min.js"></script>
<script>
// Toggle password visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '_icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Password strength checker
function checkPasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[$@#&!]+/)) strength++;
    
    return strength;
}

// Update strength meter
$('#senha_login').on('input', function() {
    const password = $(this).val();
    const strengthBar = $('#strength-bar');
    
    if (password.length === 0) {
        strengthBar.removeClass('weak medium strong').css('width', '0%');
        return;
    }
    
    const strength = checkPasswordStrength(password);
    
    strengthBar.removeClass('weak medium strong');
    
    if (strength <= 2) {
        strengthBar.addClass('weak');
    } else if (strength <= 4) {
        strengthBar.addClass('medium');
    } else {
        strengthBar.addClass('strong');
    }
});

// Password match indicator
$('#senha_again').on('input', function() {
    const senha = $('#senha_login').val();
    const senhaAgain = $(this).val();
    const indicator = $('#match-indicator');
    
    if (senhaAgain.length === 0) {
        indicator.removeClass('match no-match').hide();
        return;
    }
    
    if (senha === senhaAgain) {
        indicator.removeClass('no-match').addClass('match')
            .html('<i class="fas fa-check-circle"></i> As senhas coincidem');
    } else {
        indicator.removeClass('match').addClass('no-match')
            .html('<i class="fas fa-times-circle"></i> As senhas não coincidem');
    }
});

// Form validation
$(document).ready(function() {
    $('#formulario').validate({
        rules: {
            nome_login: {
                required: true,
                minlength: 3
            },
            senha_login: {
                minlength: 6
            },
            senha_again: {
                equalTo: "#senha_login"
            }
        },
        messages: {
            nome_login: {
                required: "Por favor, informe o login",
                minlength: "O login deve ter pelo menos 3 caracteres"
            },
            senha_login: {
                minlength: "A senha deve ter pelo menos 6 caracteres"
            },
            senha_again: {
                equalTo: "As senhas não coincidem"
            }
        },
        errorElement: 'div',
        errorClass: 'invalid-feedback',
        highlight: function(element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        },
        errorPlacement: function(error, element) {
            if (element.parent('.form-group-password').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            const senha = $('#senha_login').val();
            const senhaAgain = $('#senha_again').val();
            
            // Se preencheu senha, deve confirmar
            if (senha.length > 0 && senha !== senhaAgain) {
                alert('As senhas não coincidem!');
                return false;
            }
            
            // Confirmação antes de salvar
            if (confirm('Deseja salvar as alterações? Se você alterar a senha, precisará fazer login novamente.')) {
                form.submit();
            }
        }
    });
});
</script>
<!-- InstanceEndEditable -->
</body>
</html>
