<?php

require_once("log.php");

echo '<html><body>';

$dest = substr($_SERVER["REQUEST_URI"],1,3);
$param = substr($_SERVER["REQUEST_URI"],4);

if ($dest == "ASS") header("location: https://www.dogmo.app/assina.html?hash=$param");
if ($dest == "CAD") header("location: https://www.dogmo.app/finalizacad.html?hash=$param");

logdogmo("Tentativa de direcionamento inválida: ".$_SERVER["REQUEST_URI"]);

//echo '<meta http-equiv="refresh" content=0;url="https://www.dogmo.app/assina.html?hash='.$param.'"></body></html>';

die("<br><br><br><br><br><br><p align='center'><img src='https://www.dogmo.app/prev/images/logo.png' width=200px/></p><br><br><h2><p align='center'>Erro 404 - página não encontrada</h3></p>");

echo '</body></html>';

?>
