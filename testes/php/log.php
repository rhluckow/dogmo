<?php
function logdogmo($msg) 
{
	$ip = getenv("REMOTE_ADDR");
	$fp = fopen("log.txt", "a");
	$prg = substr($_SERVER["REQUEST_URI"], strripos($_SERVER["REQUEST_URI"],'/')+1);
	$escreve = fwrite($fp, "[".$prg."] ".date('d/m/y H:m:s')." - ".$ip." - ".$msg.chr(13));
	fclose($fp);
}