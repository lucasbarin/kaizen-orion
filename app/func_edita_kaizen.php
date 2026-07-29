<?php
include ("conexao8.php");
include ("funcoes8.php");
include ("sessao2.php");


if ( $_POST['id_kaizen'] && is_numeric($_POST['id_kaizen']) ) {

if ( $_POST ) {

	foreach ( $_POST as $k => $v ) {
		$$k = trata( $v );
	}
    
    
    
    
if (!empty ($id_kaizen) && is_numeric ($id_kaizen)){
	$sql = mysqli_query($con, "SELECT * FROM kaizen
	LEFT JOIN tipo ON tipo.id_tipo = kaizen.tipo_kaizen
	LEFT JOIN categoria ON categoria.id_categoria = kaizen.categoria_kaizen
	LEFT JOIN colaborador ON colaborador.id_colaborador = kaizen.colaborador_kaizen
	LEFT JOIN setor ON setor.id_setor = colaborador.setor_colaborador
	LEFT JOIN unidade ON unidade.id_unidade = colaborador.unidade_colaborador
	WHERE id_kaizen = ".$id_kaizen." LIMIT 1") or die (mysqli_error($con));
	 
	if(mysqli_num_rows($sql) > 0){
		$kaizen = mysqli_fetch_array($sql);
		
	} else {
		volta ("erro", "Registro n�o encontrado!", "kaizen-editar.php");
	} 
	
} else {
	volta ("erro", "Registro n�o encontrado!", "kaizen-editar.php");
}

if ($kaizen['colaborador_kaizen'] <> $_SESSION['usu']){
	volta ("erro", "Este formul�rio pertence a outro usu�rio. Voc� n�o tem permiss�o para acess�-lo!", "kaizen-editar.php");	
}

if($kaizen['status_kaizen'] <> 4){
    volta ("erro", "Apenas um Kaizen devolvido pode ser editado. Este j� foi finalizado.!", "kaizen-editar.php");	
}
    
 /*   
    echo '<pre>';
    var_dump($_POST);
    var_dump($_FILES);
	die();*/
	
	$sql_usu = sql ("SELECT * FROM colaborador WHERE id_colaborador =  ".trata($_SESSION['usu'])." LIMIT 1");
	if (mysqli_num_rows($sql_usu)){
		$usuario = mysqli_fetch_array($sql_usu);
	} else {
		volta( "erro", "Seu usu�rio n�o foi encontrado!", "../kaizen-editar.php" );
	}
	
	// textos completos
	$situacao_atual = limpa_html( $_POST[ 'situacao_atual' ] );
	$texto1 = limpa_html( $_POST[ 'onde' ] );
	$texto2 = limpa_html( $_POST[ 'resultado' ] );
	
	// Processar complemento se houver
	$valor_original = 0;
	$complemento_calculado = 0;
	$tipo_complemento_usado = 0;
	
	if(!empty($complemento_ativo)){
		// Pegar valor informado (campo dinâmico)
		if(!empty($_POST['valor_complemento_' . $complemento_ativo])){
			$valor_bruto = $_POST['valor_complemento_' . $complemento_ativo];
			$valor_original = money_sql($valor_bruto);
			$tipo_complemento_usado = $complemento_ativo;
			
			// v2.1: Não calcular mais - valor é informado diretamente
			$complemento_calculado = 0; // Campo deprecado
		}
	}
	
	$colaborador = trata($_SESSION['usu']);
	
	$data = date("Y-m-d");
		
	if(!empty($colaborador1)){
		if ($colaborador1 == $colaborador2 or $colaborador1 == $colaborador3 or $colaborador1 == $colaborador4 or $colaborador1 == $_SESSION['usu']){
			volta( "erro", "1. Os colaboradores que participaram do projeto n�o podem ser repetidos!", "../kaizen-editar.php" );
		}
	}
	if(!empty($colaborador2)){
		if ($colaborador2 == $colaborador1 or $colaborador2 == $colaborador3 or $colaborador2 == $colaborador4 or $colaborador2 == $_SESSION['usu']){
			volta( "erro", "2. Os colaboradores que participaram do projeto n�o podem ser repetidos!", "../kaizen-editar.php" );
		}
	}

	// Processar exclus�o de anexos
	if(!empty($anexos_excluir)){
		$ids_excluir = explode(',', $anexos_excluir);
		foreach($ids_excluir as $id_anexo){
			$id_anexo = intval(trim($id_anexo));
			if($id_anexo > 0){
				// Buscar nome do arquivo
				$sqlAnexo = sql("SELECT nome_arquivo FROM kaizen_anexos WHERE id_anexo = '".$id_anexo."' AND id_kaizen = '".$id_kaizen."' LIMIT 1", $con);
				if(mysqli_num_rows($sqlAnexo) > 0){
					$anexo = mysqli_fetch_array($sqlAnexo);
					$caminho_arquivo = "../imgs/kaizen/" . $anexo['nome_arquivo'];
					
					// Excluir arquivo f�sico
					if(file_exists($caminho_arquivo)){
						unlink($caminho_arquivo);
					}
					
					// Excluir do banco
					sql("DELETE FROM kaizen_anexos WHERE id_anexo = '".$id_anexo."'", $con);
				}
			}
		}
	}
	
	// Upload de novos anexos
	if(!empty($_FILES['novos_anexos']['name'][0])){
		$upload_result = uploadAnexos($_FILES['novos_anexos'], $id_kaizen, $con);
		// N�o bloqueia se upload falhar, apenas continua
	}


$status = 1; // volta para an�lise
    
$sql = sql(
"
UPDATE `kaizen` SET 
`tipo_kaizen` = '".$tipo."', 
`tipo_complemento_kaizen` = '".$tipo_complemento_usado."', 
`valor_original_kaizen` = '".$valor_original."', 
`complemento_kaizen` = '".$complemento_calculado."',
`situacao_atual` = '".$situacao_atual."',
`destaque_kaizen` = '".$destaque."', 
`onde_kaizen` = '".$texto1."', 
`resultado_kaizen` = '".$texto2."', 
`colaborador1_kaizen` = '".$colaborador1."', 
`colaborador2_kaizen` = '".$colaborador2."', 
`datacadastro_kaizen` = '".$data."', 
`status_kaizen` = '".$status."', 
`outrosdep_kaizen` = '".$outrosdep_kaizen."', 
`qual_kaizen` = '".$qual_kaizen."'
 WHERE `kaizen`.`id_kaizen` = $id_kaizen;
"
);
	

if ($sql){
	
		if ($usuario['gestor_colaborador']){
	
		  /*
		  Envia e-mail para gestor
		  */
			
		  $idkaizen = $id_kaizen;
	
	 	  $sqlGestor = sql("SELECT * FROM colaborador WHERE id_colaborador =  ".trata($usuario['gestor_colaborador'])." LIMIT 1");
		  $gestor = mysqli_fetch_array($sqlGestor);
		  $nome_colaborador = $usuario[ 'nome_colaborador' ];
          $usuario_colaborador = $usuario[ 'usuario_colaborador' ];
          $email_colaborador = $usuario[ 'email_colaborador' ];
		
	      $para = $gestor['email_colaborador'];


          include( "mail_alterado_kaizen.php" );
          
	
	}
	
	
		volta ("ok", "Sua solicita��o foi enviada com sucesso. Aguarde a nova an�lise de seu formul�rio!", "../home.php");
	} else {
		volta ("erro", "Erro enviar sua solicita��o! Tente novamente mais tarde!", "../kaizen-editar.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../kaizen-editar.php");
}
    } else {
	volta ("erro", "Kaizen n�o identificado!", "../kaizen-editar.php");
}
?>