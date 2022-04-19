<?php 
// Estableciendo conexion usando XAMPP
$conexion = mysqli_connect("localhost","root","1234","manometro");
if(!$conexion){
    die("error al conectar la base de datos" . mysqli_connect_error());
}

/*
// Estableciendo conexion con el hosting
$conexion = mysqli_connect("localhost","id18017707_root","*pM3%RqQ#S@LUbkd","id18017707_manometro");
if(!$conexion){
    die("error al conectar la base de datos" . mysqli_connect_error());
}
*/

?>