<?php
ob_start();
session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

if(!empty ($_POST['id_pg'])){

	foreach( $_POST as $k => $v ){
		$$k = trata( $v );
	}

	// textos completos
	$texto1_pg = limpa_html2($_POST['texto1_pg']);
	$texto2_pg = limpa_html2($_POST['texto2_pg']);
	$texto3_pg = limpa_html2($_POST['texto3_pg']);
	$texto4_pg = limpa_html2($_POST['texto4_pg']);
	$texto5_pg = limpa_html2($_POST['texto5_pg']);
	$texto6_pg = limpa_html2($_POST['texto6_pg']);
	$texto7_pg = limpa_html2($_POST['texto7_pg']);
	$texto8_pg = limpa_html2($_POST['texto8_pg']);
	$texto9_pg = limpa_html2($_POST['texto9_pg']);

	$sql = sql ("UPDATE pg SET nome1_pg = '$nome1_pg', texto1_pg = '$texto1_pg', nome2_pg = '$nome2_pg', texto2_pg = '$texto2_pg' ,nome3_pg = '$nome3_pg', texto3_pg = '$texto3_pg' ,nome4_pg = '$nome4_pg', texto4_pg = '$texto4_pg' , nome5_pg = '$nome5_pg', texto5_pg = '$texto5_pg' ,nome6_pg = '$nome6_pg', texto6_pg = '$texto6_pg', nome7_pg = '$nome7_pg', texto7_pg = '$texto7_pg',nome8_pg = '$nome8_pg', texto8_pg = '$texto8_pg', nome9_pg = '$nome9_pg', texto9_pg = '$texto9_pg' WHERE id_pg = '$id_pg' LIMIT 1");
	
	if ($sql){
		volta ("ok", "Página alterada com sucesso!", "../pg-editar.php");
	} else {
		volta ("erro", "Erro ao alterar página! Tente novamente mais tarde!", "../pg-editar.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../pg-editar.php");
}
ob_end_flush();
?>