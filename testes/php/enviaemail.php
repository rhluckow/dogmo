<?php
require_once("log.php");
require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');

function enviaemail( $destino, $assunto, $texto, $arqanexo )
{
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
	$mailer->Body = $texto;

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
