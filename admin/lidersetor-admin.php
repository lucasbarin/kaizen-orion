
<?
$sufixo = "lidersetor";

include ("app/sessao.php");
include ("../app/conexao8.php");
include ("../app/funcoes8.php");

$sql =  sql("SELECT * FROM lidersetor  WHERE status_lidersetor <> 2 ORDER BY id_lidersetor ASC", $con);

include("_top_admin.php"); ?>

            <form action="app/func_lidersetor_apagar.php" method="post" enctype="multipart/form-data" class="form-horizontal" id="formulario">
            <table class="table table-striped table-bordered table-hover" id="sample_1">
              <thead>
                <tr>
                  <th width="7%" class="table-checkbox"> <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes"/>
                  </th>
                  <th width="14%"> Nome </th>
                  <th width="15%">Ação</th>
                </tr>
              </thead>
              <tbody>
              	<?
                while ($dados = mysqli_fetch_assoc($sql)){
				?>
                <tr class="odd gradeX">
                  <td><input type="checkbox" class="checkboxes" value="<? echo $dados['id_lidersetor']; ?>" name="ids_lidersetor[]"/></td>
                  <td> <? echo $dados['nome_lidersetor'] ?> </td>
 
                  <td><a href="lidersetor-editar.php?id_lidersetor=<? echo $dados['id_lidersetor']; ?>" class="btn btn-xs yellow"> Editar <i class="fa fa-edit"></i> </a> <a href="app/func_lidersetor_apagar.php?id_lidersetor=<? echo $dados['id_lidersetor']; ?>" class="btn btn-xs red btconfirm"> <i class="fa fa-times"></i> Excluir </a> <!--<a class="btn btn-xs green bt_adicionar btconfirm"> Adicionar <i class="fa fa-plus"></i> </a>--></td>
                </tr>
                <?
				} // fim while
				?>
              </tbody>
            </table>
            <br class="clearfix">
            <input name="select_del" type="hidden" id="select_del" value="1">
            <button id="btsubmit" type="submit" class="btn red btconfirm2 float-esq">Apagar selecionados</button>
            </form>


<?
include("_bot_admin.php");
?>
