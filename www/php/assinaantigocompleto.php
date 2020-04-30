<html>
	<head>
		<!--Título-->
		<title>Assinatura de Documento</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Apenas um teste"/>
			
		<!--OpenType-->
		<meta property="og:locale" content="pt_BR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="" />
		<meta property="og:description" content="" />
		<meta property="og:url" content="" />
		<meta property="og:site_name" content="" />
		
		<!--CSS-->
		<link rel="stylesheet" type="text/css" href="https://www.dogmo.app/prev/php/assina.css"/>
	</head>
<body>
<div id="header">
<img src='https://www.dogmo.app/prev/images/logo.png' width=200px/>
</div>
<div id="content">
<?php

require_once("log.php");
require_once("enviasms.php");
require_once("enviaemail.php");
require_once("conecta.php");
include('phpqrcode/qrlib.php');

$hashdestino = substr($_SERVER['REQUEST_URI'],1);

// PESQUISA EM MINUTA_PARTE PARA VER QUAL O DOCUMENTO

$sql = "SELECT id_minuta, id_pessoa FROM dogmo_minuta_parte WHERE hash_minuta_parte = '".$hashdestino."';";
logdogmo("SQL da pesquisa de documento para assinatura: ".$sql);
$res = mysql_query($sql) or die ("Não foi possível realizar a pesquisa do documento");
if (mysql_num_rows($res)==0) {
	die("DOCUMENTO não encontrado.");
}

$linha = mysql_fetch_array($res);
$id_minuta = $linha['id_minuta'];
$id_pessoa_assina = $linha['id_pessoa'];

// PESQUISA A MINUTA NA TABELA DOGMO_MINUTA

$sql = "SELECT * FROM dogmo_minuta WHERE id_minuta = '".$id_minuta."';";
logdogmo("SQL da pesquisa de documento para assiantura: ".$sql);

$res = mysql_query($sql) or die ("Não foi possível realizar a pesquisa do documento");
if (mysql_num_rows($res)==0) {
	die("Minuta não encontrada.");
}

$linha = mysql_fetch_array($res);
$titulo_minuta = $linha['titulo_minuta'];
$id_pessoa     = $linha['id_pessoa'];
$texto_minuta  = $linha['texto_minuta'];

// LOCALIZANDO DADOS DA PESSOA QUE CADASTROU A MINUTA

$sql = "SELECT * FROM dogmo_pessoa WHERE id_pessoa = '".$id_pessoa."';";
$res = mysql_query($sql) or die ("Não foi possível realizar a pesquisa da pessoa");
$linha = mysql_fetch_array($res);
$nome_pessoa          = $linha['nome_pessoa'];
$documento_pessoa     = $linha['documento_pessoa'];
$endereco_pessoa      = $linha['enderece_pessoa'];
$cep_pessoa           = $linha['cep_pessoa'];
$telefone_pessoa      = $linha['telefone_pessoa'];
$email_pessoa         = $linha['email_pessoa'];
$nascimento_pessoa    = $linha['nascimento_pessoa'];
$estadocivil_pessoa   = $linha['estadocivil_pessoa'];
$profissao_pessoa     = $linha['profissao_pessoa'];
$nacionalidade_pessoa = $linha['nacionalidade_pessoa'];

echo "<br><b><center>Você está aqui para assinar um documento com as seguintes informações:</center></b><br>";
echo "<center><table id='tabela'>";
echo "<tr><td colspan='2'><center><br>Informações da pessoa que enviou o documento:<br></center></td></tr>";

echo "<tr><td align='right'>Nome: </td><td>".$nome_pessoa."</td></tr>";
echo "<tr><td align='right'>CPF ou CNPJ: </td><td>".$documento_pessoa."</td></tr>";
echo "<tr><td align='right'>Endereço: </td><td>".$endereco_pessoa."</td></tr>";
echo "<tr><td align='right'>CEP: </td><td>".$cep_pessoa."</td></tr>";
echo "<tr><td align='right'>E-mail: </td><td>".$email_pessoa."</td></tr>";
echo "<tr><td align='right'>Telefone: </td><td>".$telefone_pessoa."</td></tr>";
echo "<tr><td align='right'>Nascimento: </td><td>".$nascimento_pessoa."</td></tr>";
echo "<tr><td align='right'>Estado Civil: </td><td>".$estadocivil_pessoa."</td></tr>";
echo "<tr><td align='right'>Profissão: </td><td>".$profissao_pessoa."</td></tr>";
echo "<tr><td align='right'>Nacionalidade: </td><td>".$nacionalidade_pessoa."</td></tr></table></center>";
echo "<br>";
echo "<center><table id='tabela'>";
echo "<tr><td><b>Titulo do documento: <span id='dest'>".$titulo_minuta."</b></td></tr>";
echo "<tr><td>Teor do documento: <br>".nl2br($texto_minuta);
echo "</td></tr></table></center>";

// LOCALIZANDO DADOS DA PESSOA QUE ASSINARA A MINUTA

$sql = "SELECT * FROM dogmo_pessoa WHERE id_pessoa = '".$id_pessoa_assina."';";
$res = mysql_query($sql) or die ("Não foi possível realizar a pesquisa da pessoa");
$linha = mysql_fetch_array($res);

$nome_pessoa          = $linha['nome_pessoa'];
$documento_pessoa     = $linha['documento_pessoa'];
$endereco_pessoa      = $linha['enderece_pessoa'];
$cep_pessoa           = $linha['cep_pessoa'];
$telefone_pessoa      = $linha['telefone_pessoa'];
$email_pessoa         = $linha['email_pessoa'];
$nascimento_pessoa    = $linha['nascimento_pessoa'];
$estadocivil_pessoa   = $linha['estadocivil_pessoa'];
$profissao_pessoa     = $linha['profissao_pessoa'];
//$nacionalidade_pessoa = if(empty($linha['nacionalidade_pessoa']), 'Brasileiro', $linha['nacionalidade_pessoa']);

echo "<FORM name='cadastro' method='post' action='confirmatoken.php'>";
echo "<INPUT type='hidden' name='hash' value='".$hash."'/>";

echo "<br><center><table id='tabela'>";
echo "<tr><td colspan='2'><b><center>Complete e confira seus dados abaixo:</center></b></td></tr>";
echo "<tr><td align='right'>Nome: </td><td><INPUT type='text' name='nome_pessoa' size='50' maxlength='50' value='$nome_pessoa'/></td></tr>";
echo "<tr><td align='right'>CPF ou CNPJ: </td><td><INPUT type='text' name='documento_pessoa' size='20' maxlength='20' value='$documento_pessoa'/></td></tr>";
echo "<tr><td align='right'>Endereço: </td><td><INPUT type='text' name='endereco_pessoa' size='50' maxlength='50' value='$endereco_pessoa'/></td></tr>";
echo "<tr><td align='right'>CEP: </td><td><INPUT type='text' name='cep_pessoa' size='9' maxlength='9' value='$cep_pessoa'/></td></tr>";
echo "<tr><td align='right'>E-mail: </td><td><INPUT type='text' name='email_pessoa' size='50' maxlength='50' value='$email_pessoa'/></td></tr>";
echo "<tr><td align='right'>Telefone: </td><td><INPUT type='text' name='telefone_pessoa' size='20' maxlength='20' value='$telefone_pessoa'/></td></tr>";
echo "<tr><td align='right'>Nascimento: </td><td><INPUT type='text' name='nascimento_pessoa' size='10' maxlength='10' value='$nascimento_pessoa'/></td></tr>";
echo "<tr><td align='right'>Estado Civil: </td><td>";

$sql = "select * from dogmo_estadocivil order by id_estadocivil";
$res = mysql_query($sql);

while($linha = mysql_fetch_array($res)) {
//	echo $linha['id_estadocivil']." - ".$linha['nome_estadocivil']."<br>";
	echo "<input type='radio' name='estadocivil_pessoa' value='".$linha['id_estadocivil']."'/>".$linha['nome_estadocivil']."<br>";
}

echo "</td></tr>";



echo "<tr><td align='right'>Profissão: </td><td><INPUT type='text' name='profissao_pessoa' size='30' maxlength='30' value='$profissao_pessoa'/></td></tr>";
echo "<tr><td align='right'>Nacionalidade: </td><td><INPUT type='text' name='nacionalidade_pessoa' size='30' maxlength='30' value='$nacionalidade_pessoa'/></td></tr></table></center>";
echo "<tr><td colspan='2'><center><br><input type='checkbox' name='aceite' value='1'>Aceito do documento acima em todos os seus termos</center></td></tr>";
echo "<tr><td colspan='2'><center><br><INPUT type='submit' name='aceitar' value=' Assinar Documento ' class='button'></center></td></tr>";
echo "<br>";


//QRcode::png("https://www.botecodigital.info", $hash.".png");
//echo "<br><img src='prev/php/".$hash.".png'><br>";

//unlink($hash.".png");

// ENVIO DE SMS

// enviasms("47988379622","234567 é o seu código de aprovação do contrato");

// AQUI IRIA A PARTE A CAMERA QUE ESTÁ NO FINAL


echo "</div><div id='footer'>";

echo "Este é o rodapé.";


echo "</div>";

echo "</body></html>";




/* PARTE DA CAMERA


echo "<br><br>Tire uma foto sua clicando no botão e autorizando seu sistema operacional:<br>";

?>		
		</div>
		<div class="area">
			<video autoplay="true" id="webCamera">
			</video>
			<form target="POST">
				<textarea  type="text" id="base_img" name="base_img"></textarea>
				<button type="button" onclick="takeSnapShot()">Tirar foto e salvar</button>
			
			</form>
			<img id="imagemConvertida"/>
			<p id="caminhoImagem" class="caminho-imagem"><a href="" target="_blank"></a></p>
			<!--Scripts-->
			<script src="https://dogmo.app/prev/js/camera.js"></script>
		</div>
<?php


*/