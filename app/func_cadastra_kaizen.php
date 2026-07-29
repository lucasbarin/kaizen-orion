<?php
include ("conexao8.php");
include ("funcoes8.php");
include ("sessao2.php");


if ( $_POST ) {

	foreach ( $_POST as $k => $v ) {
		$$k = trata( $v );
	}

	// Garantir variáveis opcionais para evitar inconsistências no INSERT
	$colaborador1 = isset($colaborador1) ? intval($colaborador1) : 0;
	$colaborador2 = isset($colaborador2) ? intval($colaborador2) : 0;
	$outrosdep_kaizen = isset($outrosdep_kaizen) ? intval($outrosdep_kaizen) : 0;
	$qual_kaizen = isset($qual_kaizen) ? $qual_kaizen : '';
	$tipo = isset($tipo) ? intval($tipo) : 0;
	
	// Buscar dados do usuário
	$sql_usu = sql ("SELECT * FROM colaborador WHERE id_colaborador = ".trata($_SESSION['usu'])." LIMIT 1", $con);
	if (mysqli_num_rows($sql_usu)){
		$usuario = mysqli_fetch_array($sql_usu);
	} else {
		volta( "erro", "Seu usuário não foi encontrado!", "../novo-kaizen.php" );
	}

	// Validar cadastro básico do colaborador logado (causa comum de erro seletivo)
	$setor_usuario = intval($usuario['setor_colaborador']);
	$unidade_usuario = intval($usuario['unidade_colaborador']);
	if ($setor_usuario <= 0 || $unidade_usuario <= 0) {
		volta("erro", "Seu cadastro está incompleto (setor/unidade). Contate o administrador.", "../novo-kaizen.php");
	}

	$sql_setor = sql("SELECT id_setor FROM setor WHERE id_setor = ".$setor_usuario." LIMIT 1", $con);
	$sql_unidade = sql("SELECT id_unidade FROM unidade WHERE id_unidade = ".$unidade_usuario." LIMIT 1", $con);
	if (!mysqli_num_rows($sql_setor) || !mysqli_num_rows($sql_unidade)) {
		volta("erro", "Seu cadastro está inconsistente (setor/unidade inválido). Contate o administrador.", "../novo-kaizen.php");
	}
	
	// Textos completos
	$situacao_atual = limpa_html( $_POST[ 'situacao_atual' ] );
	$texto1 = limpa_html( $_POST[ 'onde' ] );
	$texto2 = limpa_html( $_POST[ 'resultado' ] );
	$add_tipo_texto = !empty($_POST['add_tipo_texto']) ? trata($_POST['add_tipo_texto']) : '';
	
	$colaborador = trata($_SESSION['usu']);
	$data = date("Y-m-d");
	
	// Validar colaboradores não duplicados
	if(!empty($colaborador1)){
		if ($colaborador1 == $colaborador2 or $colaborador1 == $_SESSION['usu']){
			volta( "erro", "Os colaboradores que participaram do projeto não podem ser repetidos!", "../novo-kaizen.php" );
		}
	}
	if(!empty($colaborador2)){
		if ($colaborador2 == $colaborador1 or $colaborador2 == $_SESSION['usu']){
			volta( "erro", "Os colaboradores que participaram do projeto não podem ser repetidos!", "../novo-kaizen.php" );
		}
	}
	
	// Validar tipo selecionado
	if(empty($tipo)){
		volta( "erro", "Selecione o tipo de benefício!", "../novo-kaizen.php" );
	}
	
	// Buscar dados do tipo para saber qual complemento usa
	$sql_tipo = sql("SELECT * FROM tipo WHERE id_tipo = ".intval($tipo)." LIMIT 1", $con);
	if (!mysqli_num_rows($sql_tipo)) {
		volta("erro", "Tipo de benefício inválido. Recarregue a página e tente novamente.", "../novo-kaizen.php");
	}
	$tipo_data = mysqli_fetch_array($sql_tipo);
	$tipo_complemento_id = $tipo_data['complemento_tipo']; // 0=nenhum, 1, 2 ou 3
	
	// Processar valor do complemento baseado no campo ativo
	$valor_complemento = 0;
	$complemento_ativo = !empty($_POST['complemento_ativo']) ? intval($_POST['complemento_ativo']) : 0;
	
	// Se tem complemento ativo, buscar o valor do campo correspondente
	if($complemento_ativo > 0){
		$campo_nome = 'valor_complemento_' . $complemento_ativo;
		if(!empty($_POST[$campo_nome])){
			$valor_complemento = $_POST[$campo_nome];
			
			// Se veio como formato money (1.234,56), converter para float
			$valor_complemento = str_replace('.', '', $valor_complemento); // Remove milhares
			$valor_complemento = str_replace(',', '.', $valor_complemento); // Troca v�rgula por ponto
			$valor_complemento = floatval($valor_complemento);
		}
	}
	
	// Guardar valor original informado pelo colaborador
	$valor_original_kaizen = $valor_complemento;
	
	// v2.1: Não calcular mais - valor é informado diretamente
	$complemento_kaizen = 0; // Campo deprecado
	
	// Validação: se tipo exige complemento, verificar se foi informado
	if($tipo_complemento_id > 0 && $valor_complemento <= 0){
		volta("erro", "Informe o valor/horas do benefício para esta categoria!", "../novo-kaizen.php");
	}

	// Escapar textos para query e normalizar tipos
	$situacao_atual_sql = mysqli_real_escape_string($con, $situacao_atual);
	$texto1_sql = mysqli_real_escape_string($con, $texto1);
	$texto2_sql = mysqli_real_escape_string($con, $texto2);
	$add_tipo_texto_sql = mysqli_real_escape_string($con, $add_tipo_texto);
	$qual_kaizen_sql = mysqli_real_escape_string($con, $qual_kaizen);
	$valor_original_kaizen = floatval($valor_original_kaizen);
	$tipo_complemento_id = intval($tipo_complemento_id);
	$complemento_kaizen = floatval($complemento_kaizen);
	$colaborador = intval($colaborador);
	
	// Inserir kaizen no banco sem die() para evitar tela branca e permitir retorno amigável
	$query_insert =
	"
	INSERT INTO `kaizen` (
	`id_kaizen`, 
	`tipo_kaizen`,
	`add_tipo_texto`,
	`complemento_kaizen`,
	`valor_original_kaizen`,
	`tipo_complemento_kaizen`,
	`situacao_atual`,
	`onde_kaizen`, 
	`resultado_kaizen`, 
	`colaborador_kaizen`, 
	`colaborador1_kaizen`, 
	`colaborador2_kaizen`, 
	`datacadastro_kaizen`, 
	`status_kaizen`, 
	`unidade_kaizen`, 
	`setor_kaizen`, 
	`outrosdep_kaizen`, 
	`qual_kaizen`,
	`categoria_kaizen`,
	`tempo_kaizen`,
	`custo_kaizen`,
	`reducao_kaizen`,
	`destaque_kaizen`,
	`imagem1_kaizen`,
	`imagem2_kaizen`,
	`colaborador3_kaizen`,
	`colaborador4_kaizen`,
	`lider_kaizen`,
	`admin_kaizen`,
	`pontos_kaizen`,
	`dataconclusao_kaizen`,
	`incidente_kaizen`,
	`obs_kaizen`)

	VALUES (
	NULL, 
	'".$tipo."',
	'".$add_tipo_texto_sql."',
	'".$complemento_kaizen."',
	'".$valor_original_kaizen."',
	'".$tipo_complemento_id."',
	'".$situacao_atual_sql."',
	'".$texto1_sql."', 
	'".$texto2_sql."', 
	'".$colaborador."', 
	'".$colaborador1."', 
	'".$colaborador2."', 
	'".$data."', 
	'1',
	'".$unidade_usuario."', 
	'".$setor_usuario."', 
	'".$outrosdep_kaizen."', 
	'".$qual_kaizen_sql."',
	'0',
	'0',
	'0',
	'0',
	'0',
	'',
	'',
	'0',
	'0',
	'0',
	'0',
	'0',
	'0000-00-00',
	'0',
	'');
	";

	$sql = mysqli_query($con, $query_insert);
	if (!$sql) {
		error_log("Erro cadastro Kaizen | usuario=".$colaborador." | tipo=".$tipo." | setor=".$setor_usuario." | unidade=".$unidade_usuario." | erro=".mysqli_error($con));
		volta("erro", "Não foi possível concluir o cadastro agora. Contate o administrador com seu usuário para validação de cadastro.", "../novo-kaizen.php");
	}
	
	$idkaizen = mysqli_insert_id($con);

	if ($sql){
		
		// Upload de anexos múltiplos
		if(!empty($_FILES['anexos']['name'][0])){
			$result_upload = uploadAnexos($_FILES['anexos'], $idkaizen, $con);
			
			if(!$result_upload['success']){
				// Se upload falhou, avisar mas não bloquear cadastro
				// (kaizen já foi criado)
			}
		}
		
		// Enviar e-mail para gestor
		if ($usuario['gestor_colaborador']){
			
			$sqlGestor = sql("SELECT * FROM colaborador WHERE id_colaborador = ".trata($usuario['gestor_colaborador'])." LIMIT 1", $con);
			if(mysqli_num_rows($sqlGestor)){
				$gestor = mysqli_fetch_array($sqlGestor);
				$nome_colaborador = $usuario[ 'nome_colaborador' ];
				$usuario_colaborador = $usuario[ 'usuario_colaborador' ];
				$email_colaborador = $usuario[ 'email_colaborador' ];
				
				$para = $gestor['email_colaborador'];
				
				include( "mail_novo_kaizen.php" );
			}
		}
		
		volta ("ok", "Sua solicitação foi enviada com sucesso. Aguarde a análise de seu formulário!", "../home.php");
	} else {
		volta ("erro", "Erro ao enviar sua solicitação! Tente novamente mais tarde!", "../novo-kaizen.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../novo-kaizen.php");
}
?>