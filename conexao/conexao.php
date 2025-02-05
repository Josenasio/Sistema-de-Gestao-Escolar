<?php
$host = 'localhost'; // ou o endereço do seu servidor
$db = 'escola_db_pro'; // nome do seu banco de dados
$user = 'root'; // seu usuário do banco de dados
$pass = ''; // sua senha do banco de dados

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Conexao falhou: " . $mysqli->connect_error);
}
?>


