<?php

require_once("log.php");
require_once("enviasms.php");
require_once("enviaemail.php");
require_once("conecta.php");
echo "<html><body>";
echo "entrou<br>";

$sql = "SELECT * FROM dogmo_foto_documento";

echo $sql."<br>";

$res = mysql_query($sql) or die ("Não foi possível pesquisar fotos");

while ($linha = mysql_fetch_array($res)) {
   echo "Linha: ".$linha['id_foto_documento']."<br>";
   echo "Hash: ".$linha['hash_foto_documento']."<br>";
   $foto = $linha['foto_foto_documento'];
   echo '<img src="data:image/jpeg;base64,'.$foto.'"><br>';
}
echo "</body></html>";
?>
