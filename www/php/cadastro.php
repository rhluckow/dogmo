<?php
// Check for empty fields

require_once("log.php");
require_once("enviaemail.php");
require_once("conecta.php");
include('phpqrcode/qrlib.php');

logdogmo("Entrou no cadastro.php");

if(empty($_POST['nome_pessoa'])      ||
   empty($_POST['documento_pessoa'])     ||
   empty($_POST['telefone_pessoa'])     ||
   !filter_var($_POST['email_pessoa'],FILTER_VALIDATE_EMAIL))
   {
   echo "Sem argumentos!";
   return false;
}

logdogmo("Antes dos =");

$nome_pessoa = strip_tags(htmlspecialchars($_POST['nome_pessoa']));
$documento_pessoa = strip_tags(htmlspecialchars($_POST['documento_pessoa']));
$email_pessoa = strip_tags(htmlspecialchars($_POST['email_pessoa']));
$telefone_pessoa = strip_tags(htmlspecialchars($_POST['telefone_pessoa']));

$sql = "INSERT INTO dogmo_pessoa (nome_pessoa, documento_pessoa, email_pessoa, telefone_pessoa, ipprecadastro_pessoa, datahoraprecadastro_pessoa) VALUES ('$nome_pessoa', '$documento_pessoa', '$email_pessoa', '$telefone_pessoa', '".getenv("REMOTE_ADDR")."', now())";
logdogmo($sql);
$res = mysql_query($sql) or die ("Não foi possível realizar o cadastro");

$ultimoid = mysql_insert_id();

$sql = "UPDATE dogmo_pessoa SET hash_pessoa = '".hash("md5", $ultimoid)."' where id_pessoa = $ultimoid";
logdogmo("UPDATE SQL hash pessoa: ".$sql);
$res = mysql_query($sql) or die ("Não foi possível realizar o cadastro");

$hashdestino = hash('md5', $ultimoid);

// GERAR QRCODE

$arqqr = $hashdestino.".png";

logdogmo("QRCODE em ".$arqqr);

QRcode::png("https://www.dogmo.app/ASS".$hashdestino, $arqqr);

// ENVIA EMAIL

$texto = "<table id='tabela'><tr><td><b><br>Olá, $nome_destino</b>
			<br>Você fez um cadastro em nosso site. Estamos lhe enviando um link para finalizá-lo.<br>
      Você pode clicar no link, copia-lo e cola-lo no navegador, ou apontar a câmera do seu celular no QR-CODE abaixo.<br>
					<br>
					<a href='https://www.dogmo.app/CAD$hashdestino'>www.dogmo.app/CAD$hashdestino</a><br>
					<BR>
					<img src='cid:imagem'><br>
					Atenciosamente,<br>
					<br>
					Equipe <b>dogmo</b>
		</td></tr></table><br></div>";

logdogmo("enviará email: ".$texto);
enviaemail($email_pessoa, "[CADASTRO DOGMO] ".$nome_pessoa.", finalize seu cadastro no DOGMO", $texto, $arqqr);
logdogmo("enviou email");

unlink($arqqr);

echo "ok";

logdogmo("Enviou e-mail do cadastro de documento");

return true;
?>
