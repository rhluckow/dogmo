<?php

$usu_mysql = 'dogmo_db';
$sen_mysql = 'Rhl47484748rhl';
$ser_mysql = 'dogmo_db.mysql.dbaas.com.br';
$ban_mysql = 'dogmo_db';

$co = mysql_connect($ser_mysql,$usu_mysql,$sen_mysql) or logdogmo("N�o foi poss�vel conectar ao servidor");
$db = mysql_select_db($ban_mysql,$co) or logdogmo("N�o foi poss�vel conectar ao banco de dados");



