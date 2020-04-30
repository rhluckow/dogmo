<?php
// Check for empty fields

require_once("log.php");
require_once("enviaemail.php");
require_once("conecta.php");
include('phpqrcode/qrlib.php');

logdogmo("Entrou no documento.php");

/*
if(empty($_POST['doc_pessoa'])      ||
   empty($_POST['titulo_documento'])     ||
   empty($_POST['nome_destino'])     ||
   empty($_POST['documento_destino'])   ||
   empty($_POST['email_destino'])     ||
   empty($_POST['fone_destino'])   ||
   empty($_POST['documento'])   ||
   !filter_var($_POST['email_destino'],FILTER_VALIDATE_EMAIL))
   {
   echo "Sem argumentos!";
   return false;
}
*/

$doc_pessoa        = strip_tags(htmlspecialchars($_POST['doc_pessoa']));
$titulo_documento  = strip_tags(htmlspecialchars($_POST['titulo_documento']));
$nome_destino      = strip_tags(htmlspecialchars($_POST['nome_destino']));
$documento_destino = strip_tags(htmlspecialchars($_POST['documento_destino']));
$email_destino     = strip_tags(htmlspecialchars($_POST['email_destino']));
$fone_destino      = strip_tags(htmlspecialchars($_POST['fone_destino']));
$documento         = strip_tags(htmlspecialchars($_POST['documento']));
$hash              = hash('md5', $documento);

logdogmo("doc_pessoa        = ".strip_tags(htmlspecialchars($_POST['doc_pessoa'])));
logdogmo("titulo_documento  = ".strip_tags(htmlspecialchars($_POST['titulo_documento'])));
logdogmo("nome_destino      = ".strip_tags(htmlspecialchars($_POST['nome_destino'])));
logdogmo("documento_destino = ".strip_tags(htmlspecialchars($_POST['documento_destino'])));
logdogmo("email_destino     = ".strip_tags(htmlspecialchars($_POST['email_destino'])));
logdogmo("fone_destino      = ".strip_tags(htmlspecialchars($_POST['fone_destino'])));
logdogmo("documento         = ".strip_tags(htmlspecialchars($_POST['documento'])));

// LOCALIZA PESSOA DONA DO CONTRATO NA BASE DE DADOS

$sql = "SELECT * FROM dogmo_pessoa WHERE documento_pessoa = '".$doc_pessoa."';";
$res = mysql_query($sql) or die ("Não foi possível realizar a pesquisa da pessoa");

if (mysql_num_rows($res)==0) {
	die("Seu CPF/CNPJ não foi localizado. Faça seu cadastro ou corrija o número do CPF/CNPJ.");
}

$linha = mysql_fetch_array($res);

$nome_pessoa = $linha['nome_pessoa'];
$id_pessoa   = $linha['id_pessoa'];

logdogmo("Pessoa encontrada: ".$nome_pessoa);


// PESQUISA DESTINO NO BANCO DE DADOS DE PESSOA E SE NÃO EXISTIR, CADASTRA


$sql = "SELECT id_pessoa FROM dogmo_pessoa WHERE documento_pessoa = '".$documento_destino."';";
$res = mysql_query($sql) or die ("Não foi possível realizar a pesquisa da pessoa");
$linha = mysql_fetch_array($res);

if (mysql_num_rows($res)==0) {

	$sql = "INSERT INTO dogmo_pessoa (nome_pessoa, documento_pessoa, email_pessoa, telefone_pessoa) VALUES ('$nome_destino', '$documento_destino', '$email_destino', '$fone_destino')";
	logdogmo("SQL Destino:".$sql);
	$res = mysql_query($sql) or die ("Não foi possível realizar o cadastro do destino");

	$sql = "SELECT id_pessoa FROM dogmo_pessoa WHERE documento_pessoa = '".$documento_destino."';";
	$res = mysql_query($sql) or die ("Não foi possível realizar a pesquisa da pessoa");
	$linha = mysql_fetch_array($res);
}

logdogmo("SQL Pesquisa pessoa destino:".$sql);

$id_pessoa_destino = $linha['id_pessoa'];


logdogmo("id_pessoa_destino: ".$id_pessoa_destino);
// INSERE MINUTA


$sql = "INSERT INTO dogmo_minuta (titulo_minuta, texto_minuta, datahora_minuta, hash_minuta, id_pessoa) VALUES ('$titulo_documento', '$documento', now(), '$hash', $id_pessoa)";
$res = mysql_query($sql) or die ("Não foi possível realizar o cadastro");

if($res) logdogmo("Cadastro realizado com sucesso - sql: ".$sql);
else logdogmo("Erro no sql - ".$sql );

$sql = "SELECT id_minuta FROM dogmo_minuta WHERE hash_minuta = '".$hash."';";
$res = mysql_query($sql) or die ("Não foi possível realizar a pesquisa da pessoa");
logdogmo("Pesquisa id_minuta: ".$sql);

$linha = mysql_fetch_array($res);
$id_minuta = $linha['id_minuta'];

// INSERE PESSOAS COMO PARTE DA MINUTA

$sql = "INSERT INTO dogmo_minuta_parte (id_tipoparte, id_minuta, id_pessoa) VALUES (1, $id_minuta, $id_pessoa);";
logdogmo("SQL parte minuta: ".$sql);
$res = mysql_query($sql) or die ("Não foi possível realizar o cadastro");

$ultimoid = mysql_insert_id();
$sql = "UPDATE dogmo_minuta_parte SET hash_minuta_parte = '".hash("md5", $ultimoid)."' where id_minuta_parte = $ultimoid";
logdogmo("UPDATE SQL parte minuta: ".$sql);
$res = mysql_query($sql) or die ("Não foi possível realizar o cadastro");

if($res) logdogmo("Parte da minuta cadastrada com sucesso - sql: ".$sql);
else logdogmo("Erro no sql - ".$sql );

$sql = "INSERT INTO dogmo_minuta_parte (id_tipoparte, id_minuta, id_pessoa) VALUES (2, $id_minuta, $id_pessoa_destino);";
logdogmo("SQL parte minuta: ".$sql);

$res = mysql_query($sql) or die ("Não foi possível realizar o cadastro");
if($res) logdogmo("Parte da minuta cadastrada com sucesso - sql: ".$sql);
else logdogmo("Erro no sql - ".$sql );

$ultimoid = mysql_insert_id();
$sql = "UPDATE dogmo_minuta_parte SET hash_minuta_parte = '".hash("md5", $ultimoid)."' where id_minuta_parte = $ultimoid";
logdogmo("UPDATE SQL parte destino na minuta: ".$sql);
$res = mysql_query($sql) or die ("Não foi possível realizar o cadastro");

$hashdestino = hash('md5', $ultimoid);

// GERAR QRCODE

$arqqr = $hashdestino.".png";

logdogmo("QRCODE em ".$arqqr);


QRcode::png("https://www.dogmo.app/ASS".$hashdestino, $arqqr);

// ENVIA EMAIL

$texto = "
			<table id='tabela'><tr><td><b><br>Olá, $nome_destino</b>
			<br><b>$nome_pessoa</b> está lhe encaminhando o documento <b>$titulo_documento</b> para assinatura.<br>
					<br>
					Para isso, basta clicar no link abaixo, ou digitá-lo no seu navegador de internet, ou apontar a câmera do seu celular no QR-CODE abaixo.<br>
					<br>
					<a href='https://www.dogmo.app/ASS$hashdestino'>www.dogmo.app/ASS$hashdestino</a><br>
					<BR>
					<img src='cid:imagem'><br>
					Atenciosamente,<br>
					<br>
					Equipe <b>dogmo</b>
		</td></tr></table><br></div>";

enviaemail($email_destino, "[DOCUMENTO DOGMO] ".$nome_pessoa." lhe enviou um documento para assinar", $texto, $arqqr);

unlink($arqqr);

echo "ok";

logdogmo("Enviou e-mail do cadastro de documento");

return true;
?>
