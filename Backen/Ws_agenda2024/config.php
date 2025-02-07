<?php

header('Access-Control-Allow-Credentials:true');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, charset");
header('ContentType:application/json; charset=utf-8');
define('db', 'db_agenda24');
define('host', 'localhost');
define('usuario', 'root');
define('clave', '');

$mysqli = new mysqli(host, usuario, clave, db);