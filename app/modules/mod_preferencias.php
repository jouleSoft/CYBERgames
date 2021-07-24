<?php
	function passwordCheck($username,$pwOld,$pwNew1,$pwNew2){
		$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
		if(!$mysqli->connect_error){
			$sql	= "SELECT PASS FROM USUARIOS WHERE USERNAME = '$username'";
			if($res = $mysqli->query($sql)){
				$fila 	= $res->fetch_assoc();
				
				$mysqli->close();
			}else{
				$mysqli->close();
				return false;
			}
			
			# COMPROBAR CONTRASEÑA ANTIGUA
			
			if(!password_verify($pwOld,$fila['PASS'])){
				return false;
			}
			
			# COMPROBAR QUE LA NUEVA CONTRASEÑA Y SU VERIFICACIÓN COINCIDEN
			
			if($pwNew1 == $pwNew2 && $pwNew1 != ""){
				return true;
			}else{
				return false;
			}
		}else{
			die("Conexión fallida: " . $mysqli->connect_error);
		}
	}
?>