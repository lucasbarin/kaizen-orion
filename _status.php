<?php
// Garantir que $status está definida
$status = $status ?? 1;

if ( $status == 2 ) {
  ?>
<div class="alert alert-success"> <i class="fas fa-check-circle"></i> Implementado </div>
<?
} elseif ( $status == 3 ) {
    ?>
<div class="alert alert-danger"> <i class="fas fa-times-circle"></i> Reprovado </div>
<?


} elseif ( $status == 4 ) {
    ?>
<div class="alert alert-info"> <i class="fas fa-times-circle"></i> Devolvido </div>
<?


} elseif ( $status == 5 ) {
    ?>
<div class="alert alert-info"> <i class="fas fa-times-circle"></i> N.A. </div>
<?


} else {
  ?>
<div class="alert alert-warning"> <i class="fas fa-exclamation-circle"></i> Aguardando análise </div>
<?
}
?>
