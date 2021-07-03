<?php
session_start();
if(!isset($_SESSION['username'])){
    header('location: index.php');
}else{
	$username 	= $_SESSION['username'];
    $nombre_usr = $_SESSION['nombre'];
	$ape_usr	= $_SESSION['apellidos'];
	$admin		= $_SESSION['admin'];
}

require('modules/mod_usuarios.php');

if(isset($_GET['idUsr']) || $_POST['username']){
	$IDUSR 		= strtoupper($_GET['idUsr']);
	$USERNAME	= strtoupper($_POST['username']);
}
if(existeUsername($IDUSR)){
	print "existe<br>";
}else{
	print "no existe<br>";
}

if(existeUsername($USERNAME)){
	print "existe";
}else{
	print "no existe";
}
?>