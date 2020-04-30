<?php
require_once("log.php");


// Check for empty fields
if(empty($_POST['name'])      ||
   empty($_POST['email'])     ||
   empty($_POST['phone'])     ||
   empty($_POST['message'])   ||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
   echo "No arguments Provided!";
   return false;
   }

 
$name = strip_tags(htmlspecialchars($_POST['name']));
$email_address = strip_tags(htmlspecialchars($_POST['email']));
$phone = strip_tags(htmlspecialchars($_POST['phone']));
$message = strip_tags(htmlspecialchars($_POST['message']));
$email_body = "Você recebeu uma mensagem vinda do site\n\nNome: $name\n\nEmail: $email_address\n\nTelefone: $phone\n\nMensagem:\n$message";

require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');

$mailer = new PHPMailer();
$mailer->IsSMTP();
$mailer->SMTPDebug = 1;
$mailer->Port = 465; //Indica a porta de conexão 
$mailer->Host = 'email-ssl.com.br';//Endereço do Host do SMTP 
$mailer->SMTPSecure = 'ssl';
$mailer->SMTPAuth = true; //define se haverá ou não autenticação
$mailer->Username = 'contato@dogmo.app'; //Login de autenticação do SMTP
$mailer->Password = '$Contato4748$'; //Senha de autenticação do SMTP
$mailer->FromName = 'Contato dogmo'; //Nome que será exibido
$mailer->From = 'contato@dogmo.app'; //Obrigatório ser a mesma caixa postal configurada no remetente do SMTP
$mailer->AddAddress('contato@dogmo.app');
$mailer->AddAddress('rhluckow@gmail.com');
$mailer->Subject = 'Contato enviado do site [dogmo]';
$mailer->Body = $email_body;
$envio = $mailer->Send();

if($envio) logdogmo("e-mail enviado com sucesso - from ".$name." - email: ".$email_address." - telefone: ".$phone);
else logdogmo("A mensagem não pode ser enviada - ".$mailer->ErrorInfo." - from ".$name." - email: ".$email_address." - telefone: ".$phone );

return true;         
?>