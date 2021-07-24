<?php
	function existeCliente($dni){
		$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
		if(!$mysqli->connect_error){
			$sql	= "SELECT NOMBRE FROM CLIENTES WHERE ID LIKE '$dni'";
			if($res = $mysqli->query($sql)){
				$rows = $res->num_rows;
				if($rows >= 1){
					return true;
				}else{
					return false;
				}
			}
		}else{
			die("Conexión fallida: " . $conn->connect_error);
		}
	}
?>