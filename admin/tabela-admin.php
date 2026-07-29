
<?
$sufixo = "tabela";

include ("app/sessao.php");
include ("../app/conexao8.php");
include ("../app/funcoes8.php");

$sql =  sql("SELECT * FROM tabela ORDER BY id_tabela ASC", $con);

include("_top_admin.php"); ?>

            <form action="app/func_tabela_apagar.php" method="post" enctype="multipart/form-data" class="form-horizontal" id="formulario">
            <table class="table table-striped table-bordered table-hover" id="sample_1">
              <thead>
                <tr>
                  <th width="7%" class="table-checkbox"> <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes"/>
                  </th>
                  <th width="14%"> Nome </th>
                  <th width="14%"> Ordem </th>

                  <th width="15%">Ação</th>
                </tr>
              </thead>
              <tbody>
              	<?
                while ($dados = mysqli_fetch_assoc($sql)){
				?>
                <tr class="odd gradeX">
                  <td><input type="checkbox" class="checkboxes" value="<? echo $dados['id_tabela']; ?>" name="ids_tabela[]"/></td>
                  <td> <? echo $dados['nome_tabela'] ?> </td>
 
                  <td>
				  <? echo str_pad($dados['numero_tabela'], 2, "0", STR_PAD_LEFT) ?>º
				  </td>
                 
                  <td><a href="tabela-editar.php?id_tabela=<? echo $dados['id_tabela']; ?>" class="btn btn-xs yellow"> Editar <i class="fa fa-edit"></i> </a> <a href="app/func_tabela_apagar.php?id_tabela=<? echo $dados['id_tabela']; ?>" class="btn btn-xs red btconfirm"> <i class="fa fa-times"></i> Excluir </a> <!--<a class="btn btn-xs green bt_adicionar btconfirm"> Adicionar <i class="fa fa-plus"></i> </a>--></td>
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
