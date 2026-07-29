<?php
ob_start();
session_start();

$erro = 0;
$username_saved = '';
$password_saved = '';
$remember_checked = '';

// Verificar se há cookies salvos
if (isset($_COOKIE['orion_admin_user']) && isset($_COOKIE['orion_admin_pass'])) {
	$username_saved = $_COOKIE['orion_admin_user'];
	$password_saved = base64_decode($_COOKIE['orion_admin_pass']);
	$remember_checked = 'checked';
}

if ($_POST) {
	if (!empty($_POST['name']) && !empty($_POST['password'])) {
		
		include("../app/conexao8.php");
		include("../app/funcoes8.php");
		$sql = mysqli_query($con, "SELECT * FROM login WHERE nome_login = '".trata($_POST['name'])."' LIMIT 1") or die(mysqli_error($con));
		
		if (mysqli_num_rows($sql) > 0) {
			$dados = mysqli_fetch_array($sql);
			$login = $dados['nome_login'];
			$senha = $dados['senha_login'];
			
			if ($login == $_POST['name'] && $senha == $_POST['password']) {
				$_SESSION['administrator'] = "Qy2X5IVDEJ9lPlI3SMl1";
				
				// Processar "Lembrar-me"
				if (isset($_POST['remember']) && $_POST['remember'] == '1') {
					// Salvar cookies por 30 dias
					setcookie('orion_admin_user', $_POST['name'], time() + (30 * 24 * 60 * 60), '/', '', false, true);
					setcookie('orion_admin_pass', base64_encode($_POST['password']), time() + (30 * 24 * 60 * 60), '/', '', false, true);
				} else {
					// Remover cookies se desmarcado
					if (isset($_COOKIE['orion_admin_user'])) {
						setcookie('orion_admin_user', '', time() - 3600, '/', '', false, true);
					}
					if (isset($_COOKIE['orion_admin_pass'])) {
						setcookie('orion_admin_pass', '', time() - 3600, '/', '', false, true);
					}
				}
				
				header("Location: home.php");
				exit();
			} else {
				$erro = 1;
			}
		} else {
			$erro = 1;
		}
	} else {
		$erro = 1;
	}
}

if (isset($_SESSION['administrator']) && $_SESSION['administrator'] == "Qy2X5IVDEJ9lPlI3SMl1") {
	header("Location: home.php");
	exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<title>Login - Painel Administrativo Orion</title>
	
	<meta name="description" content="Sistema Orion Kaizen - Painel Administrativo">
	<meta name="author" content="Orion">
	
	<link rel="shortcut icon" href="favicon.ico">
	
	<!-- Bootstrap 5.3 CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Font Awesome 6 -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	
	<!-- Google Fonts Inter -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	
	<style>
		:root {
			--orion-primary: #009ee3;
			--orion-primary-dark: #007ab8;
			--orion-secondary: #fcd700;
			--orion-text: #2c3e50;
			--orion-text-light: #7f8c8d;
		}
		
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}
		
		body {
			font-family: 'Inter', sans-serif;
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			position: relative;
			overflow: hidden;
		}
		
		body::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
			animation: gridMove 20s linear infinite;
		}
		
		@keyframes gridMove {
			0% { transform: translate(0, 0); }
			100% { transform: translate(100px, 100px); }
		}
		
		.floating-shapes {
			position: absolute;
			width: 100%;
			height: 100%;
			overflow: hidden;
			pointer-events: none;
		}
		
		.shape {
			position: absolute;
			opacity: 0.1;
			animation: float 20s infinite ease-in-out;
		}
		
		.shape:nth-child(1) {
			width: 80px;
			height: 80px;
			background: var(--orion-secondary);
			border-radius: 50%;
			top: 10%;
			left: 10%;
			animation-delay: 0s;
		}
		
		.shape:nth-child(2) {
			width: 120px;
			height: 120px;
			background: var(--orion-primary);
			border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
			top: 70%;
			left: 80%;
			animation-delay: 2s;
		}
		
		.shape:nth-child(3) {
			width: 100px;
			height: 100px;
			background: white;
			border-radius: 50%;
			top: 40%;
			left: 90%;
			animation-delay: 4s;
		}
		
		@keyframes float {
			0%, 100% { transform: translate(0, 0) rotate(0deg); }
			25% { transform: translate(30px, -30px) rotate(90deg); }
			50% { transform: translate(-20px, 20px) rotate(180deg); }
			75% { transform: translate(20px, 30px) rotate(270deg); }
		}
		
		.login-container {
			position: relative;
			z-index: 1;
			width: 100%;
			max-width: 480px;
			padding: 0 1.5rem;
		}
		
		.login-card {
			background: rgba(255, 255, 255, 0.95);
			backdrop-filter: blur(20px);
			border-radius: 1.5rem;
			box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
			padding: 3rem;
			border: 1px solid rgba(255, 255, 255, 0.3);
		}
		
		.logo-container {
			text-align: center;
			margin-bottom: 2rem;
		}
		
		.logo-container img {
			max-width: 180px;
			height: auto;
			margin-bottom: 1rem;
			filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
		}
		
		.logo-container h1 {
			font-size: 1.75rem;
			font-weight: 700;
			color: var(--orion-primary);
			margin-bottom: 0.5rem;
		}
		
		.logo-container p {
			color: var(--orion-text-light);
			font-size: 0.95rem;
		}
		
		.form-group {
			position: relative;
			margin-bottom: 1.5rem;
		}
		
		.form-group label {
			display: block;
			margin-bottom: 0.5rem;
			color: var(--orion-text);
			font-weight: 500;
			font-size: 0.95rem;
		}
		
		.form-group input {
			width: 100%;
			border: 2px solid #e0e0e0;
			border-radius: 0.75rem;
			padding: 0.875rem 1rem 0.875rem 3rem;
			font-size: 1rem;
			transition: all 0.3s ease;
			background: white;
			color: var(--orion-text);
		}
		
		.form-group input::placeholder {
			color: #aaa;
		}
		
		.form-group input:focus {
			border-color: var(--orion-primary);
			box-shadow: 0 0 0 0.2rem rgba(0, 158, 227, 0.15);
			outline: none;
		}
		
		.input-icon {
			position: absolute;
			left: 1rem;
			bottom: 0.875rem;
			color: var(--orion-primary);
			font-size: 1.1rem;
			z-index: 5;
		}
		
		.remember-me {
			display: flex;
			align-items: center;
			gap: 0.5rem;
			margin-bottom: 1.5rem;
			user-select: none;
		}
		
		.remember-me input[type="checkbox"] {
			width: 20px;
			height: 20px;
			cursor: pointer;
			accent-color: var(--orion-primary);
		}
		
		.remember-me label {
			color: var(--orion-text);
			font-size: 0.95rem;
			cursor: pointer;
			margin: 0;
		}
		
		.btn-login {
			width: 100%;
			padding: 1rem;
			background: linear-gradient(135deg, var(--orion-primary) 0%, var(--orion-primary-dark) 100%);
			border: none;
			border-radius: 0.75rem;
			color: white;
			font-weight: 600;
			font-size: 1.05rem;
			cursor: pointer;
			transition: all 0.3s ease;
			box-shadow: 0 4px 15px rgba(0, 158, 227, 0.3);
		}
		
		.btn-login:hover {
			transform: translateY(-2px);
			box-shadow: 0 6px 20px rgba(0, 158, 227, 0.4);
		}
		
		.btn-login:active {
			transform: translateY(0);
		}
		
		.alert {
			border-radius: 0.75rem;
			padding: 1rem;
			margin-bottom: 1.5rem;
			border: none;
			display: flex;
			align-items: center;
			gap: 0.75rem;
		}
		
		.alert-danger {
			background: #fee;
			color: #c33;
		}
		
		.alert i {
			font-size: 1.25rem;
		}
		
		.copyright {
			text-align: center;
			margin-top: 2rem;
			color: white;
			font-size: 0.9rem;
			opacity: 0.8;
		}
		
		@media (max-width: 576px) {
			.login-card {
				padding: 2rem 1.5rem;
			}
			
			.logo-container h1 {
				font-size: 1.5rem;
			}
		}
	</style>
</head>
<body>
	<div class="floating-shapes">
		<div class="shape"></div>
		<div class="shape"></div>
		<div class="shape"></div>
	</div>
	
	<div class="login-container">
		<div class="login-card">
			<div class="logo-container">
				<img src="assets/admin/layout/img/logo.png" alt="Orion Kaizen" onerror="this.style.display='none'">
				<h1>ORION KAIZEN</h1>
				<p>Painel Administrativo</p>
			</div>
			
			<?php if ($erro == 1): ?>
			<div class="alert alert-danger" role="alert">
				<i class="fas fa-exclamation-circle"></i>
				<div>
					<strong>Erro!</strong> Login e/ou senha incorreto(s).
				</div>
			</div>
			<?php endif; ?>
			
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<div class="form-group">
					<label for="name">Usuário</label>
					<i class="fas fa-user input-icon"></i>
					<input type="text" 
						   class="form-control" 
						   id="name" 
						   name="name" 
						   placeholder="Digite seu usuário" 
						   value="<?php echo htmlspecialchars($username_saved); ?>"
						   required 
						   autocomplete="off"
						   autofocus>
				</div>
				
				<div class="form-group">
					<label for="password">Senha</label>
					<i class="fas fa-lock input-icon"></i>
					<input type="password" 
						   class="form-control" 
						   id="password" 
						   name="password" 
						   placeholder="Digite sua senha" 
						   value="<?php echo htmlspecialchars($password_saved); ?>"
						   required 
						   autocomplete="off">
				</div>
				
				<div class="remember-me">
					<input type="checkbox" 
						   id="remember" 
						   name="remember" 
						   value="1"
						   <?php echo $remember_checked; ?>>
					<label for="remember">Lembrar-me neste dispositivo</label>
				</div>
				
				<button type="submit" class="btn btn-login">
					<i class="fas fa-sign-in-alt me-2"></i>
					Entrar no Sistema
				</button>
			</form>
		</div>
		
		<div class="copyright">
			&copy; <?php echo date('Y'); ?> Orion Kaizen. Todos os direitos reservados.
		</div>
	</div>
	
	<!-- Bootstrap 5 JS Bundle -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	
	<script>
	// Auto-focus no primeiro campo
	document.addEventListener('DOMContentLoaded', function() {
		document.getElementById('name').focus();
	});
	</script>
</body>
</html>