<?php
require_once("log.php");
require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');

function enviaemail( $destino, $assunto, $texto, $arqanexo )
{
logdogmo("entruo no email");
  $cabec = "

	<head>
	<style type='text/css'>
	body {
	 color: #424242;
	 font-size: 14px;
	 font-family: 'Poppins', sans-serif;
	 line-height: 1.80857;
	}

	b {
	 color: #424242;
	 font-size: 16px;
	 font-style: bold;
	 font-family: 'Poppins', sans-serif;
	 line-height: 1.80857;
	}

	#tabela {
	 width: 95%;
	 color: #424242;
	 font-size: 14px;
	 font-style: bold;
	 font-family: 'Poppins', sans-serif;
	 line-height: 1.80857;
	 border: 1px solid black;
	 background: #FFFFF0;
	 border-spacing: 15px 5px;
	 table-layout: fixed
	}

	#header {
	 width: 97%;
	 margin: 20 auto;
	 background:#ffffff;
	}

	#content {
	 width: 97%;
	 height: auto;
	 margin: 20 auto;
	 background: #fafafa;
	}

	#foot {
	 width: 97%;
	 height: 50px;
	 margin: 20 auto;
	 text-align: center;
	 border: 3px solid red;
	 background: #ffffff;
	}
	</style>
	</head>

			<div id='header'>
					<img src='https://www.dogmo.app/images/logo.png' width=200px/>
			</div>
			<br>
			<div id='content'><br>";

	$mailer = new PHPMailer();
	$mailer->IsSMTP();
	$mailer->SMTPDebug = 1;
	$mailer->isHTML(true);
	$mailer->Charset = 'UTF-8';
	$mailer->Port = 465; //Indica a porta de conexão
	$mailer->Host = 'email-ssl.com.br';//Endereço do Host do SMTP
	$mailer->SMTPSecure = 'ssl';
	$mailer->SMTPAuth = true; //define se haverá ou não autenticação
	$mailer->Username = 'contato@dogmo.app'; //Login de autenticação do SMTP
	$mailer->Password = '$Contato4748$'; //Senha de autenticação do SMTP
	$mailer->FromName = 'Contato dogmo'; //Nome que será exibido
	$mailer->From = 'contato@dogmo.app'; //Obrigatório ser a mesma caixa postal configurada no remetente do SMTP
	$mailer->AddAddress( $destino );
	$mailer->Subject = $assunto;
	$mailer->Body = $cabec.$texto."</div>";

    if ( ! empty($arqanexo) )
		$mailer->AddEmbeddedImage($arqanexo, 'imagem');

	$envio = $mailer->Send();


	if($envio) {
		logdogmo("E-mail enviado com sucesso - Para ".$destino." - assunto: ".$assunto);
		return true;
	}
	else
	{
		logdogmo("E-mail não pode ser enviado - Para ".$destino." - assunto: ".$assunto);
		return false;
	}
}
