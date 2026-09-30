<?php
$host = "localhost";
$user = "root";
$pass = "";
$DBname = "tradia";

$conn = new mysqli($host, $user, $pass, $DBname);

if($conn->connect_error){
    die("Sin conexion a la base de datos".$conn->connect_error);
}
?>