<?php

require_once("log.php");
require_once("enviasms.php");
require_once("enviaemail.php");
require_once("conecta.php");

logdogmo("entrou no carregacad.php");

$url = $_POST['url'];

$hashpessoa = substr( $url, strrpos($url,"=")+1);

// PESQUISA EM MINUTA_PARTE PARA VER QUAL O DOCUMENTO

// seria se fosse alteração: $sql = "SELECT dogmo_pessoa.*, dogmo_estadocivil.nome_estadocivil FROM dogmo_pessoa, dogmo_estadocivil WHERE hash_pessoa = '".$hashpessoa."' and dogmo_estadocivil.id_estadocivil = dogmo_pessoa.id_estadocivil;";

$sql = "SELECT * FROM dogmo_pessoa WHERE hash_pessoa = '".$hashpessoa."';";

logdogmo("Sql do inicio do cadastro: ".$sql);
$res = mysql_query($sql) or die ("Não foi possível realizar a pesquisa da pessoa");
$linha = mysql_fetch_array($res);

$id_pessoa          = $linha['id_pessoa'];


$html = '<BR>
			<div class="panel-heading">
				<input type="text" class="form-control" id="hash_pessoa" value="'.$hashpessoa.'" hidden>
				<input type="text" class="form-control" id="id_pessoa" value="'.$id_pessoa.'" hidden>
			</div>';

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

echo json_encode( array(
	   "html"                 => $html,
		 "nome_pessoa"          => $nome_pessoa,
		 "documento_pessoa"     => $documento_pessoa,
		 "endereco_pessoa"      => $endereco_pessoa,
		 "cep_pessoa"           => $cep_pessoa,
		 "telefone_pessoa"      => $telefone_pessoa,
		 "email_pessoa"         => $email_pessoa,
		 "nascimento_pessoa"    => $nascimento_pessoa,
		 "estadocivil_pessoa"   => $nome_estadocivil,
		 "profissao_pessoa"     => $profissao_pessoa,
		 "nacionalidade_pessoa" => $nacionalidade_pessoa
	 ) );

return;
