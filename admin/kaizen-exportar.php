<?php
include ("app/sessao.php");
include ("../app/conexao8.php");
include ("../app/funcoes8.php");

$link_menu = 2;

$filtro = '';

if (isset($_GET['clear']) && $_GET['clear'] == 1){ 
	unset($_SESSION['tipo_kaizen'], $_SESSION['status_kaizen'], $_SESSION['destaque_kaizen'], $_SESSION['colaborador_kaizen'], $_SESSION['lider_kaizen'], $_SESSION['unidade_kaizen'], $_SESSION['setor_kaizen'], $_SESSION['data_cadastro_ini'], $_SESSION['data_cadastro_fim']);
	
} else {
	if (!empty($_GET) or !empty($_SESSION['tipo_kaizen']) or !empty($_SESSION['status_kaizen']) or !empty($_SESSION['destaque_kaizen']) or !empty($_SESSION['colaborador_kaizen']) or !empty($_SESSION['lider_kaizen']) or !empty($_SESSION['unidade_kaizen']) or !empty($_SESSION['setor_kaizen']) or !empty($_SESSION['data_cadastro_ini']) or !empty($_SESSION['data_cadastro_fim'])){
	
	if (!empty($_GET)){

		foreach( $_GET as $k => $v ){
			$$k = trata( $v );
		}
	} else {
		foreach( $_SESSION as $k => $v ){
			$$k = trata( $v );
		}
	}
	

	
	
	
	if (isset($tipo_kaizen) && $tipo_kaizen){
		$filtro.= " AND tipo_kaizen = ".intval($tipo_kaizen)." ";
		$_SESSION['tipo_kaizen'] = $tipo_kaizen;
	} else {
		$_SESSION['tipo_kaizen'] = '';
	}
		
		
	if (isset($status_kaizen) && $status_kaizen){		$filtro.= " AND status_kaizen = ".intval($status_kaizen)." ";
		$_SESSION['status_kaizen'] = $status_kaizen;
	} else {
		$_SESSION['status_kaizen'] = '';
	}
	
/*	if ($destaque_kaizen){
		if ($destaque_kaizen == 1){
			$filtro.= " AND destaque_kaizen = 1 ";
		} else {
			$filtro.= " AND destaque_kaizen != 1 ";
		}
		$_SESSION['destaque_kaizen'] = $destaque_kaizen;
	} else {
		$_SESSION['destaque_kaizen'] = '';
	}*/
	
	if (isset($colaborador_kaizen) && $colaborador_kaizen){
		$filtro.= " AND colaborador_kaizen = ".intval($colaborador_kaizen)." ";
		$_SESSION['colaborador_kaizen'] = $colaborador_kaizen;
	} else {
		$_SESSION['colaborador_kaizen'] = '';
	}
	
	
	if (isset($lider_kaizen) && $lider_kaizen){
		$filtro.= " AND admin_kaizen = ".intval($lider_kaizen)." ";
		$_SESSION['lider_kaizen'] = $lider_kaizen;
	} else {
		$_SESSION['lider_kaizen'] = '';
	}
	
	if (isset($unidade_kaizen) && $unidade_kaizen){
		$filtro.= " AND unidade_kaizen = ".intval($unidade_kaizen)." ";
		$_SESSION['unidade_kaizen'] = $unidade_kaizen;
	}	else {
		$_SESSION['unidade_kaizen'] = '';
	}
	
	if (isset($setor_kaizen) && $setor_kaizen){
		$filtro.= " AND setor_kaizen = ".intval($setor_kaizen)." ";
		$_SESSION['setor_kaizen'] = $setor_kaizen;
	} else {
		$_SESSION['setor_kaizen'] = '';
	}
	
	if ((isset($data_cadastro_ini) && $data_cadastro_ini) or (isset($data_cadastro_fim) && $data_cadastro_fim)){
		$data_ini = isset($data_cadastro_ini) ? data_sql($data_cadastro_ini) : '';
		$_SESSION['data_cadastro_ini'] = isset($data_cadastro_ini) ? $data_cadastro_ini : '';
		$data_fim = isset($data_cadastro_fim) ? data_sql($data_cadastro_fim) : '';
		$_SESSION['data_cadastro_fim'] = isset($data_cadastro_fim) ? $data_cadastro_fim : '';
		
		if (isset($data_cadastro_ini) && $data_cadastro_ini && isset($data_cadastro_fim) && $data_cadastro_fim){
			$filtro.= " AND datacadastro_kaizen BETWEEN ('".$data_ini."') AND ('".$data_fim."') ";
			
		} else {
			if (isset($data_cadastro_ini) && $data_cadastro_ini){
				$filtro.= " AND datacadastro_kaizen  >= '".$data_ini."' "; 
			} else {
				$filtro.= " AND datacadastro_kaizen  <= '".$data_fim."' "; 
			}
			
		}	
		
	} else {
		$_SESSION['data_cadastro_ini']	= '';
		$_SESSION['data_cadastro_fim']	= '';
	}
	
	// 
}
	
}



$sql = sql("SELECT kaizen.*, tipo.*, 
comp.nome_comp, comp.tipo_entrada,
colaborador.nome_colaborador, 
setor.nome_setor, 
unidade.nome_unidade,
admin.nome_colaborador AS lider_nome
FROM kaizen
LEFT JOIN tipo ON tipo.id_tipo = kaizen.tipo_kaizen
LEFT JOIN comp ON comp.id_comp = tipo.complemento_tipo
LEFT JOIN colaborador ON colaborador.id_colaborador = kaizen.colaborador_kaizen
LEFT JOIN setor ON setor.id_setor = kaizen.setor_kaizen
LEFT JOIN unidade ON unidade.id_unidade = kaizen.unidade_kaizen
LEFT JOIN colaborador AS admin ON admin.id_colaborador = kaizen.admin_kaizen
WHERE id_kaizen > 0
".$filtro."
ORDER BY datacadastro_kaizen DESC ");


// Nome do Arquivo do Excel que será gerado
$arquivo = 'export-kaizen-'.date('Y-m-d').'.xls';

// Criamos uma tabela HTML com o formato da planilha para excel
$tabela = '<table border="1">
 <tr>
	<td><b>ID</b></td>
	<td><b>Data de Cadastro</b></td>
	<td><b>Unidade</b></td>
	<td><b>Setor</b></td>
	<td><b>Autor Principal</b></td>
	<td><b>Colaborador 1</b></td>
	<td><b>Colaborador 2</b></td>
	<td><b>Tipo/Categoria</b></td>
	<td><b>Descrição - Onde</b></td>
	<td><b>Resultado Obtido</b></td>
	<td><b>Status</b></td>
	<td><b>Líder Responsável</b></td>
	<td><b>Observações da Análise</b></td>
	<td><b>Pontos Concedidos</b></td>
	<td><b>Benefício em R$</b></td>
	<td><b>Horas Economizadas</b></td>
	<td><b>Incidentes Evitados</b></td>
</tr>
 
 ';


while ($dados = mysqli_fetch_assoc($sql)){
	$data = date("d/m/Y", strtotime($dados['datacadastro_kaizen']));
    
   
 if (!empty($dados['colaborador1_kaizen'])){
	 
	 $sqlColab1 = sql("SELECT * FROM colaborador WHERE id_colaborador = ".$dados['colaborador1_kaizen']);
	 if (mysqli_num_rows($sqlColab1)){
		 $colab1 = mysqli_fetch_array($sqlColab1);
		 $colaborador1 = $colab1['nome_colaborador'];
	 } else {
		 $colaborador1 = "";
	 } 
	 
 } else {
	 $colaborador1 = "";
 }   
	
 if (!empty($dados['colaborador2_kaizen'])){
	 
	 $sqlColab2 = sql("SELECT * FROM colaborador WHERE id_colaborador = ".$dados['colaborador2_kaizen']);
	 if (mysqli_num_rows($sqlColab2)){
		 $colab2 = mysqli_fetch_array($sqlColab2);
		 $colaborador2 = $colab2['nome_colaborador'];
	 } else {
		 $colaborador2 = "";
	 }
	 
 } else {
	 $colaborador2 = "";
 }
	
	
	
if (isset($dados['status_kaizen']) && $dados['status_kaizen'] == 2 ) {
    $status = 'Implementado';
} elseif ( $dados['status_kaizen'] == 3 ) {
   $status = 'Reprovado';
} elseif ( $dados['status_kaizen'] == 4 ) {
   $status = 'Já Existente';
} elseif ( $dados['status_kaizen'] == 5 ) {
    $status = 'N.A.';
} else {
  $status = 'Aguardando análise';
}

    

    
$tabela.= "<tr>";
$tabela.= "<td>".($dados['id_kaizen'] ?? '')."</td>";
$tabela.= "<td>".$data."</td>";
$tabela.= "<td>".($dados['nome_unidade'] ?? '-')."</td>";
$tabela.= "<td>".($dados['nome_setor'] ?? '-')."</td>";
$tabela.= "<td>".($dados['nome_colaborador'] ?? '')."</td>";
$tabela.= "<td>".$colaborador1."</td>";
$tabela.= "<td>".$colaborador2."</td>";
$tipo_label = $dados['nome_tipo'] ?? '';
if(!empty($dados['nome_comp'])){ $tipo_label .= ' - '.$dados['nome_comp']; }
$tabela.= "<td>".$tipo_label."</td>";
$tabela.= "<td>".($dados['onde_kaizen'] ?? '')."</td>";
$tabela.= "<td>".($dados['resultado_kaizen'] ?? '')."</td>";
$tabela.= "<td>".$status."</td>";
$tabela.= "<td>".($dados['lider_nome'] ?? '-')."</td>";
$tabela.= "<td>".($dados['observacoes_kaizen'] ?? '')."</td>";
$tabela.= "<td>".($dados['ponto_kaizen'] ?? '0')."</td>";

// v2.1: Exibir valores baseados em tipo_complemento_kaizen
// Tipo 1 e 2 = R$ (vai na coluna "red. Custo anual")
// Tipo 3 = Horas (vai na coluna "red. Horas trab. anual")
$valor_beneficio = "";
$tempo_beneficio = "";

if (!empty($dados['valor_original_kaizen']) && $dados['valor_original_kaizen'] > 0 && !empty($dados['tipo_complemento_kaizen'])) {
    if ($dados['tipo_complemento_kaizen'] == 1 || $dados['tipo_complemento_kaizen'] == 2) {
        // Tipos 1 e 2: Exibir complemento_kaizen (valor em R$ calculado)
        if (!empty($dados['complemento_kaizen']) && $dados['complemento_kaizen'] > 0) {
            $valor_beneficio = "R$ ".number_format($dados['complemento_kaizen'], 2, ",", ".");
        }
    } elseif ($dados['tipo_complemento_kaizen'] == 3) {
        // Tipo 3: Exibir valor_original_kaizen (horas)
        $tempo_beneficio = number_format($dados['valor_original_kaizen'], 0, ',', '.')." horas";
    }
} else {
    // Fallback para kaizens antigos (campos legados)
    if (!empty($dados['custo_kaizen']) && $dados['custo_kaizen'] > 0) {
        $valor_beneficio = "R$ ".number_format($dados['custo_kaizen'], 2, ",", ".");
    }
    if (!empty($dados['tempo_kaizen'])) {
        $tempo_beneficio = $dados['tempo_kaizen'];
    }
}

$tabela.= "<td>".$valor_beneficio."</td>";
$tabela.= "<td>".$tempo_beneficio."</td>";

$tabela.= "<td>".($dados['incidente_kaizen'] ?? '')."</td>";
    
    
$tabela.= "</tr>";
	
	
		
				} // fim while
				

$tabela .= '</table>';

// Força o Download do Arquivo Gerado
// Converter para Windows-1252 (ISO-8859-1) que é o encoding nativo do Excel
$tabela = mb_convert_encoding($tabela, 'Windows-1252', 'UTF-8');

header ('Cache-Control: no-cache, must-revalidate');
header ('Pragma: no-cache');
header ('Content-Type: application/vnd.ms-excel; charset=Windows-1252');
header ("Content-Disposition: attachment; filename=\"{$arquivo}\"");

echo $tabela;
?>