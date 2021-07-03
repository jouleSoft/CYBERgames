<?php
	function existeArticulo($nombre,$plataforma){
		$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
		if(!$mysqli->connect_error){
			$sql	= "SELECT NOMBRE FROM ARTICULOS WHERE NOMBRE LIKE '$nombre' AND PLATAFORMA LIKE '$plataforma'";
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
	
	function nuevoID(){
		
		$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
		
		if(!$mysqli->connect_error){
			$sql = "SELECT ID FROM ARTICULOS";
			if($res=$mysqli->query($sql)){
				if($res->num_rows > 0){
					
					$preIDArray = [];
					$preID 		= $res->num_rows + 1;
					$IDMatch	= False;
					
					while($fila	= $res->fetch_assoc()){
						$preIDArray[] = $fila['ID'];
					}
					
					$mysqli->close();
					
					foreach($preIDArray as $i){
						if($preID == $i){$IDMatch = True;}
					}
					
					if($IDMatch){
						
						$preID 		= 0;
						$n_matches	= 0;
						
						while($IDMatch == True){
							foreach($preIDArray as $pID){
								if($preID == $pID){
									$n_matches++;
								}
							}
							if($n_matches == 0){
								$IDMatch = False;
							}else{
								$preID++;
								$n_matches = 0;
							}
						}
					}
					
					return $preID;
				}
			}	
		}
	}
?>