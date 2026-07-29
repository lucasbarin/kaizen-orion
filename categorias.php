<?php
include("app/conexao8.php");
include("app/funcoes8.php");
include("app/sessao.php");

if (!empty($_GET['categoria'])){
	$id_categoria = intval($_GET['categoria']);
	$sqlCat = sql("SELECT * FROM catproduto WHERE id_catproduto = $id_categoria LIMIT 1", $con);
	if (mysqli_num_rows($sqlCat)){
		$filtro = 'WHERE catproduto_produto = '.$id_categoria;
	} else {
		$id_categoria = 0;
	}
} else {
	$id_categoria = 0;
}
?>
<!doctype html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Trocar Pontos - Sistema Orion</title>
	<link rel="shortcut icon" href="favicon.ico"/>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="lib/css/orion-custom.css"/>
	<link rel="stylesheet" href="assets/css/estilos-v2.css"/>
	<script src="lib/js/jquery-1.11.3.min.js"></script>
	<style>
		.product-card {
			background: white;
			border-radius: 1rem;
			overflow: hidden;
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
			transition: all 0.3s ease;
			height: 100%;
			display: flex;
			flex-direction: column;
		}
		.product-card:hover {
			transform: translateY(-8px);
			box-shadow: 0 12px 40px rgba(0, 158, 227, 0.15);
		}
		.product-image {
			width: 100%;
			height: 220px;
			object-fit: cover;
			background: #f8f9fa;
		}
		.product-body {
			padding: 1.5rem;
			flex-grow: 1;
			display: flex;
			flex-direction: column;
		}
		.product-title {
			font-size: 1.1rem;
			font-weight: 600;
			color: var(--orion-primary);
			margin-bottom: 0.5rem;
		}
		.product-desc {
			font-size: 0.9rem;
			color: var(--orion-text-light);
			margin-bottom: 1rem;
			flex-grow: 1;
		}
		.product-points {
			background: linear-gradient(135deg, var(--orion-secondary) 0%, #e0c100 100%);
			color: #2c3e50;
			padding: 0.75rem;
			border-radius: 0.5rem;
			text-align: center;
			font-weight: 700;
			font-size: 1.2rem;
			margin-bottom: 1rem;
		}
		.category-tabs {
			display: flex;
			flex-wrap: wrap;
			gap: 0.5rem;
			margin-bottom: 2rem;
		}
		.category-tab {
			padding: 0.5rem 1.5rem;
			border-radius: 2rem;
			border: 2px solid var(--orion-primary);
			color: var(--orion-primary);
			background: white;
			text-decoration: none;
			font-weight: 500;
			transition: all 0.3s ease;
		}
		.category-tab:hover, .category-tab.active {
			background: var(--orion-primary);
			color: white;
		}
	</style>
</head>
<body class="orion-body-gradient">
	<nav class="navbar navbar-expand-lg navbar-light fixed-top orion-navbar-glass">
		<div class="container">
			<a class="navbar-brand" href="home.php">
				<img src="lib/img/logo-kaizen.png" alt="Kaizen" height="40">
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ms-auto align-items-lg-center">
					<?php if (!empty($_SESSION['usu'])): ?>
						<li class="nav-item d-lg-none"><a class="nav-link" href="home.php"><i class="fas fa-home"></i> Página Inicial</a></li>
						<li class="nav-item d-lg-none"><a class="nav-link" href="minhas-sugestoes.php"><i class="fab fa-wpforms"></i> Meus Kaizens</a></li>
						<li class="nav-item d-lg-none"><a class="nav-link active" href="categorias.php"><i class="fas fa-gift"></i> Trocar Pontos</a></li>
						<li class="nav-item d-lg-none"><a class="nav-link" href="minhas-trocas.php"><i class="fas fa-exchange-alt"></i> Minhas Trocas</a></li>
						<li class="nav-item d-lg-none"><hr class="dropdown-divider"></li>
						<li class="nav-item me-lg-3">
							<span class="nav-link">
								<i class="fas fa-user-circle"></i> 
								<strong><?= $usuario['nome_colaborador'] ?></strong>
								<br class="d-lg-none">
								<span class="badge-pontos ms-2">
									<i class="fas fa-star"></i> 
									<?php 
										$pontos = $usuario['ponto_colaborador'];
										echo ($pontos == 0) ? '0 pontos' : (($pontos == 1) ? '1 ponto' : $pontos . ' pontos');
									?>
								</span>
							</span>
						</li>
						<li class="nav-item">
							<a class="btn btn-outline-primary" href="app/func_logout.php">
								<i class="fas fa-sign-out-alt"></i> Sair
							</a>
						</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</nav>
	
	<div style="height: 80px;"></div>
	
	<main class="container py-5">
		<nav aria-label="breadcrumb" class="mb-4">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="home.php"><i class="fas fa-home"></i> Início</a></li>
				<li class="breadcrumb-item active">Trocar Pontos</li>
			</ol>
		</nav>
		
		<?php resp($_GET['resp'] ?? null, $_GET['msg'] ?? null, $_GET['tipo'] ?? null); ?>
		
		<div class="row mb-4">
			<div class="col-12">
				<h1 class="display-5 fw-bold text-primary mb-2">
					<i class="fas fa-gift"></i> Catálogo de Prêmios
				</h1>
				<p class="text-muted">Resgate seus pontos por produtos e experiências incríveis</p>
			</div>
		</div>
		
		<?php
		$sqlCat = sql("SELECT * FROM catproduto ORDER BY numero_catproduto ASC", $con);
		if (mysqli_num_rows($sqlCat)):
		?>
		<div class="category-tabs">
			<a class="category-tab <?= ($id_categoria == 0) ? 'active' : '' ?>" href="categorias.php">
				<i class="fas fa-th"></i> Todos
			</a>
			<?php while ($cat = mysqli_fetch_assoc($sqlCat)): ?>
			<a class="category-tab <?= ($id_categoria == $cat['id_catproduto']) ? 'active' : '' ?>" href="categorias.php?categoria=<?= $cat['id_catproduto'] ?>">
				<?= $cat['nome_catproduto'] ?>
			</a>
			<?php endwhile; ?>
		</div>
		<?php endif; ?>
		
		<?php
		$sql = sql("SELECT * FROM produto $filtro ORDER BY numero_produto ASC", $con);
		if (mysqli_num_rows($sql)):
		?>
		<div class="row g-4">
			<?php while($dados = mysqli_fetch_assoc($sql)): ?>
			<div class="col-sm-6 col-lg-4 col-xl-3">
				<div class="product-card">
					<img src="imgs/<?= $dados['imagem1_produto'] ?>" alt="<?= $dados['nome_produto'] ?>" class="product-image">
					<div class="product-body">
						<h5 class="product-title"><?= $dados['nome_produto'] ?></h5>
						<p class="product-desc"><?= $dados['texto1_produto'] ?></p>
						<div class="product-points">
							<i class="fas fa-star"></i> <?= $dados['pontos_produto'] ?> pontos
						</div>
						<a class="btn btn-primary w-100 btconfirma" href="app/func_pontos_trocar.php?produto=<?= $dados['id_produto'] ?>">
							<i class="fas fa-exchange-alt"></i> Resgatar
						</a>
					</div>
				</div>
			</div>
			<?php endwhile; ?>
		</div>
		<?php else: ?>
		<div class="text-center py-5">
			<i class="fas fa-box-open" style="font-size: 5rem; color: var(--orion-primary); opacity: 0.3;"></i>
			<h3 class="mt-4">Nenhum produto encontrado</h3>
			<p class="text-muted">Não há produtos disponíveis nesta categoria no momento.</p>
		</div>
		<?php endif; ?>
	</main>
	
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<script>
	$('.btconfirma').on('click', function(e){
		if(!confirm('Confirma a troca de pontos por este produto?')){
			e.preventDefault();
			return false;
		}
	});
	</script>
</body>
</html>
