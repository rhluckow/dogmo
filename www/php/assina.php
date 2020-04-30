<?php

require_once("log.php");
require_once("enviasms.php");
require_once("enviaemail.php");
require_once("conecta.php");

include("phpqrcode/qrlib.php");

$url = $_POST['url'];

$hashdestino = substr( $url, strrpos($url,"/")+1);

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
$texto_minuta  = nl2br($linha['texto_minuta']);

// LOCALIZANDO DADOS DA PESSOA QUE CADASTROU A MINUTA

$sql = "SELECT dogmo_pessoa.*, dogmo_estadocivil.nome_estadocivil FROM dogmo_pessoa, dogmo_estadocivil WHERE id_pessoa = '".$id_pessoa."' and dogmo_estadocivil.id_estadocivil = dogmo_pessoa.id_estadocivil;";

logdogmo("Sql do inicio da assinatura: ".$sql);
$res = mysql_query($sql) or die ("Não foi possível realizar a pesquisa da pessoa");
$linha = mysql_fetch_array($res);
$nome_pessoa          = $linha['nome_pessoa'];
$documento_pessoa     = $linha['documento_pessoa'];
$endereco_pessoa      = $linha['endereco_pessoa'];
$cep_pessoa           = $linha['cep_pessoa'];
$telefone_pessoa      = $linha['telefone_pessoa'];
$email_pessoa         = $linha['email_pessoa'];
$nascimento_pessoa    = $linha['nascimento_pessoa'];
$estadocivil_pessoa   = $linha['nome_estadocivil'];
$profissao_pessoa     = $linha['profissao_pessoa'];
$nacionalidade_pessoa = $linha['nacionalidade_pessoa'];

echo '<BR>
			<div class="panel-heading"><h5>Informações de quem produziu o documento:</h5></div>
	      <table class="table table-bordered">
		      <tr><td class="text-right">Nome: </td><td>'.$nome_pessoa.'</td></tr>
					<tr><td class="text-right">CPF ou CNPJ: </td><td>'.$documento_pessoa.'</td></tr>
					<tr><td class="text-right">Endereço: </td><td>'.$endereco_pessoa.'</td></tr>
					<tr><td class="text-right">CEP: </td><td>'.$cep_pessoa.'</td></tr>
					<tr><td class="text-right">E-mail: </td><td>'.$email_pessoa.'</td></tr>
					<tr><td class="text-right">Telefone: </td><td>'.$telefone_pessoa.'</td></tr>
					<tr><td class="text-right">Nascimento: </td><td>'.date("d/m/Y",$nascimento_pessoa).'</td></tr>
					<tr><td class="text-right">Estado Civil: </td><td>'.$estadocivil_pessoa.'</td></tr>
					<tr><td class="text-right">Profissão: </td><td>'.$profissao_pessoa.'</td></tr>
					<tr><td class="text-right">Nacionalidade: </td><td>'.$nacionalidade_pessoa.'</td></tr>
				</table>
				<table class="table table-bordered">
					<div class="panel-heading"><h5>Informações do documento:</h5></div>
					<tr><td class="text-right">Titulo: </td><td>'.$titulo_minuta.'</td></tr>
					<tr><td class="text-justified" colspan="2">Teor do documento:<br>'.$texto_minuta.'</td></tr>
				</table>
			</div>';

/*

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
*/






return;
