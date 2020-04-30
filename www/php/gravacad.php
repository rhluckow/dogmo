<?php

require_once("log.php");
require_once("enviasms.php");
require_once("enviaemail.php");
require_once("conecta.php");
include("phpqrcode/qrlib.php");

logdogmo("entrou no gravacad.php");

$nome_pessoa          = $_POST['nome_pessoa'];
$check_pessoa         = $_POST['check_documento'];
$id_pessoa            = $_POST['id_pessoa'];
$telefone_pessoa      = $_POST['telefone_pessoa'];
$documento_pessoa     = $_POST['documento_pessoa'];
$email_pessoa         = $_POST['email_pessoa'];
$endereco_pessoa      = $_POST['endereco_pessoa'];
$cep_pessoa           = $_POST['cep_pessoa'];
$nascimento_pessoa    = $_POST['nascimento_pessoa'];
$estadocivil_pessoa   = $_POST['estadocivil_pessoa'];
$profissao_pessoa     = $_POST['profissao_pessoa'];
$nacionalidade_pessoa = $_POST['nacionalidade_pessoa'];
$hash_pessoa          = $_POST['hash_pessoa'];

echo "GRAVOU: ".$nome_pessoa."<br>";

$sql = "UPDATE dogmo_pessoa SET
               datahoraultcadastro_pessoa=now(),
               ipultcadastro_pessoa='".getenv("REMOTE_ADDR")."',
               nome_pessoa='".$nome_pessoa."',
               endereco_pessoa='".$endereco_pessoa."',
               cep_pessoa='".$cep_pessoa."',
               documento_pessoa='".$documento_pessoa."',
               nascimento_pessoa='".$nascimento_pessoa."',
               email_pessoa='".$email_pessoa."',
               telefone_pessoa='".$telefone_pessoa."',
               id_estadocivil='".$estadocivil_pessoa."',
               profissao_pessoa='".$profissao_pessoa."',
               nacionalidade_pessoa='".$nacionalidade_pessoa."'
               WHERE  id_pessoa=".$id_pessoa;

logdogmo("Sql update minuta_parte: $sql");

$res = mysql_query($sql) or die ("Não foi possível gravar assinatura da minuta");

$arquivo = '../snaps/'.$hash_pessoa.'.jpg';
$tamanho = filesize($arquivo);
//$foto = addslashes(fread(fopen($arquivo, "rb"), $tamanho));

$foto = base64_encode(fread(fopen($arquivo, "rb"), $tamanho));

//$foto = file_get_contents($arquivo);

$sql = "DELETE FROM dogmo_foto_documento WHERE hash_foto_documento = '$hash_pessoa';";
logdogmo("Sql exclusão foto anterior: $sql");
$res = mysql_query($sql) or die ("Não foi possível apagar foto no banco de dados");

$sql = "INSERT INTO dogmo_foto_documento (hash_foto_documento, foto_foto_documento) values ('$hash_pessoa', '$foto')";

//logdogmo("Sql inclusão foto: $sql");

$res = mysql_query($sql) or die ("Não foi possível gravar foto no banco de dados");

unlink($arquivo);

logdogmo("terminou gravassina.php");

return true;

/*

$check_documento      = $_POST['check_documento'];
$id_minuta_parte      = $_POST['id_minuta_parte'];
$nome_pessoa          = $_POST['nome_pessoa'];
$telefone_pessoa      = $_POST['telefone_pessoa'];
$documento_pessoa     = $_POST['documento_pessoa'];
$email_pessoa         = $_POST['email_pessoa'];
$endereco_pessoa      = $_POST['endereco_pessoa'];
$cep_pessoa           = $_POST['cep_pessoa'];
$nascimento_pessoa    = $_POST['nascimento_pessoa'];
$estadocivil_pessoa   = $_POST['estadocivil_pessoa'];
$profissao_pessoa     = $_POST['profissao_pessoa'];
$nacionalidade_pessoa = $_POST['nacionalidade_pessoa'];
*/
/*
echo "GRAVOU: ".$nome_pessoa."<br>";
echo "telefone: ".$telefone_pessoa."<br>";
echo "check: ".$check_documento."<br>";
echo "id_minuta_parte: ".$id_minuta_parte."<br>";

return

/*
$sql = "UPDATE dogmo_minuta_parte SET datahoraassinatura_minuta_parte=now(), endereco_minuta_parte=$endereco_pessoa, cep_minuta_parte=$cep_pessoa, email_minuta_parte=$email_pessoa, telefone_minuta_parte=telefone_pessoa WHERE  id_minuta_parte=$id_minuta_parte;"

echo "<br>Sql: ".$sql."<br>";
echo "GRAVOU: ".$nome_pessoa."<br>";
echo "telefone: ".$telefone_pessoa."<br>";

$res = mysql_query($sql) or die ("Não foi possível gravar assinatura da minuta");

logdogmo("terminou gravassina.php");

return;
*/
