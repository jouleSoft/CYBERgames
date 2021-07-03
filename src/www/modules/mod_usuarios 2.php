<?php
	function existeUsername($username){
		
		$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
		if(!$mysqli->connect_error){
			$sql	= "SELECT USERNAME FROM USUARIOS WHERE USERNAME = '$username'";
			if($res = $mysqli->query($sql)){
				
				$ROWS = $res->num_rows;
				
				if($ROWS > 0){
					$mysqli->close();
					return true;
				}else{
					$mysqli->close();
					return false;
				}
			}else{
				$mysqli->close();
				return true;
			}
		}else{
			die("Conexión fallida: " . $conn->connect_error);
		}
		$mysqli->close();
	}
?>