<?php

@session_start();

unset ($_SESSION['log'], $_SESSION['usu']);

echo '<meta HTTP-EQUIV="Refresh" CONTENT="0; URL=../index.php">';

die;

?>

