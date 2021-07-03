<?php
	function nuevaFactura(){
		$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
		
		if(!$mysqli->connect_error){
			$sql = "SELECT COUNT(*)+1 FROM FACTURAS";
			if($res = $mysqli->query($sql)){
				if($res->num_rows > 0){
					$fila = $res->fetch_assoc();
					return $fila['COUNT(*)+1'];
				}
			}
		}
	}
	
	function listaPlataformasVenta($plataforma, $cliente){
		$array_plat = [];	# Array contenedor para listado de nombre de plataformas disponiibles
		
		### --Listado de plataformas disponibles-- ###
			
		# Creación de conexión contra la base de datos
		$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
		
		# Evaluación de error de conexión
		if(!$mysqli->connect_error){
			
			# Sistema de etiquetas y evento javaScript para la selección de plataforma, con ello construiremos las
			# filas de cada tabla
			#
			# Recogeremos el valor de la etiqueta con la referencia indicada para pasar dicho valor mediante $_GET
			# de forma reflexiva a la misma página
			
			# Construimos el id: id="referenciaN". Cada fila llevará la suya, por lo que deben de ser únicas. De ahí el contador.
			$contador = 0;
			$ref			= "referencia" . $contador;
			$tagID			= "id=\"$ref\"";
			
			# Evento 'onclick' para usar la función 'cargarRef()'
			$event			= "onclick=\"cargarRef(document.getElementById('$ref').innerText,'$cliente')\"";
			
			# Indicar la plataforma seleccionada. Si la plataforma coincide, formatear con CSS mediante '$p_active'
			$p_active = "style=\"background-color: gray; color: whitesmoke;\"";
			
			# Ejecutamos query y evaluamos. Se agruparán los resultados por plataforma para que no haya duplicados.
			if($res=$mysqli->query("SELECT PLATAFORMA FROM ARTICULOS GROUP BY PLATAFORMA")){
				if($res->num_rows > 0){
					
					$contador++; # Preparamos el contador para iniciar el proceso desde el número 1.
					
					# Obtenemos el resto de resultados de la misma forma que en la anterior inserción.
					while($fila = $res->fetch_assoc()){
						$ref			= "referencia" . $contador;
						$tagID			= "id=\"$ref\"";
						$event			= "onclick=\"cargarRef(document.getElementById('$ref').innerText,'$cliente')\"";
						
						$plat			= $fila['PLATAFORMA'];
						
						if($plataforma == $plat){
							$array_plat[] = "<tr $event><td $p_active ><a $tagID>$plat</a></td></tr>";
						}else{
							$array_plat[] = "<tr $event><td><a $tagID>$plat</a></td></tr>";
						}
						
						$contador++;
					}
				}
				$mysqli->close();	# Cerramos conexión con BD.
			}
		}
		return $array_plat;
	}
	
	function listaArticulosVentas($PLAT,$IDCLI){
		$array_list = [];	# Array contenedor para listado de resultados
		
		### --Listado de artículos en función de la plataforma-- ###
			
		# Creación de conexión contra la base de datos
		$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
		
		# Evaluación de error de conexión
		if(!$mysqli->connect_error){
			
			$sql = "SELECT ID,NOMBRE,PVENTA,STOCK,PLATAFORMA FROM ARTICULOS WHERE PLATAFORMA='$PLAT' ORDER BY NOMBRE ASC";
			
			# Ejecutamos query con evaluación de resultado
			if($res=$mysqli->query($sql)){
				if($res->num_rows > 0){
					
					$idRow = 0;
					
					while($fila = $res->fetch_assoc()){
						$id 	 = zeroIzquierda($fila['ID']);
						$titulo  = utf8_encode($fila['NOMBRE']);
						$precio  = $fila['PVENTA'];
						$stock	 = $fila['STOCK'];
						
						$idTag	 = "id=\"IDART" . $idRow . "\"";
						$stTag	 = "id=\"STOCK" . $idRow . "\"";
						$onclick = "onclick=\"añadirArt(document.getElementById('IDART$idRow').innerText,document.getElementById('STOCK$idRow').innerText,'$IDCLI','$PLAT')\"";
						
						$array_list[] = "<tr id=\"prev_art_row\" $onclick><td id=\"artID\"><a $idTag>$id</a></td><td id=\"artTITULO\"><a>$titulo</a></td><td id=\"artPRECIO\"><a>€ $precio</a></td><td id=\"artSTOCK\"><a $stTag>$stock</a></td></tr>";
						
						$idRow++;
					}
				}
			}
		}
		$mysqli->close(); # Cerramos conexión con base de datos.
		return $array_list;
	}
	
	function ventaIniciada(){
		$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
		
		if(!$mysqli->connect_error){
			
			$sql = "SELECT COUNT(*) FROM VENTAS";
			
			# Ejecutamos query con evaluación de resultado
			if($res=$mysqli->query($sql)){
				if($res->num_rows > 0){
					$fila = $res->fetch_assoc();
					if((int)$fila['COUNT(*)'] > 0){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}
		}
		$mysqli->close(); # Cerramos conexión con base de datos.
		return $array_list;
	}
	
	function añadirArtVentas($PLAT,$IDCLI,$IDART){
		$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
		
		$IDART = (int)$IDART;
		$IDCLI = (string)$IDCLI;
		
		if(!$mysqli->connect_error){
			$sql = "SELECT IDART FROM VENTAS WHERE IDART = $IDART";
			
			# Ejecutamos query con evaluación de resultado
			if($res=$mysqli->query($sql)){
				if($res->num_rows == 1){
					$ExisteArt = true;
				}else{
					$ExisteArt = false;
				}
			}else{
				$mysqli->close();
				return false;
			}
			
			$sql = "UPDATE ARTICULOS SET STOCK = STOCK-1 WHERE ID = $IDART";
			
			if(!$res=$mysqli->query($sql)){
				$mysqli->close();
				return false;
			}
			
			
			if($ExisteArt){
				$sql = "UPDATE VENTAS SET CANTIDAD = CANTIDAD+1 WHERE IDART = $IDART";
			}else{
				$sql = "INSERT INTO VENTAS (IDVENTA,FVENTA,IDCLI,IDART,CANTIDAD,VALOR)
					SELECT NULL,CURDATE(),'$IDCLI',$IDART,1,ARTICULOS.PVENTA
					FROM ARTICULOS
					WHERE ARTICULOS.ID = $IDART";
			}
			
			if($res=$mysqli->query($sql)){
				$mysqli->close();
				return true;
			}else{
				$mysqli->close();
				return false;
			}
			
			
		}else{
			$mysqli->close();
			return false;
		}
	}
	
	function eliminarArtVentas($PLAT,$IDCLI,$IDART){
		$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
		
		$IDART = (int)$IDART;
		$IDCLI = (string)$IDCLI;
		
		if(!$mysqli->connect_error){
			$sql = "SELECT CANTIDAD FROM VENTAS WHERE IDART = $IDART";
			
			# Ejecutamos query con evaluación de resultado
			if($res=$mysqli->query($sql)){
				if($res->num_rows == 1){
					$fila = $res->fetch_assoc();
				}else{
					$mysqli->close();
					return false;
				}
				
				$CANTIDAD = $fila['CANTIDAD'];
				
			}else{
				$mysqli->close();
				return false;
			}
			
			$sql = "UPDATE ARTICULOS SET STOCK = STOCK+1 WHERE ID = $IDART";
			
			if(!$res=$mysqli->query($sql)){
				$mysqli->close();
				return false;
			}
			
			
			if($CANTIDAD > 1){
				$sql = "UPDATE VENTAS SET CANTIDAD = CANTIDAD-1 WHERE IDART = $IDART";
			}else{
				$sql = "DELETE FROM VENTAS WHERE IDART = $IDART";
			}
			
			if($res=$mysqli->query($sql)){
				$mysqli->close();
				return true;
			}else{
				$mysqli->close();
				return false;
			}
			
		}else{
			$mysqli->close();
			return false;
		}
	}
	
	function listaVentas($IDCLI,$PLAT){
		
		### --Listado de artículos en función de la plataforma-- ###
			
		# Creación de conexión contra la base de datos
		$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
		
		# Evaluación de error de conexión
		if(!$mysqli->connect_error){
			
			$sql = "SELECT VENTAS.IDART,ARTICULOS.NOMBRE,VENTAS.CANTIDAD,VENTAS.VALOR,VENTAS.CANTIDAD*VENTAS.VALOR 
				FROM VENTAS,ARTICULOS 
				WHERE VENTAS.IDART = ARTICULOS.ID";
			
			# Ejecutamos query con evaluación de resultado
			if($res=$mysqli->query($sql)){
				if($res->num_rows > 0){
					
					$idRow = 0;
					
					while($fila = $res->fetch_assoc()){
						$IDART 	= zeroIzquierda($fila['IDART']);
						$titulo = utf8_encode($fila['NOMBRE']);
						$cant  	= $fila['CANTIDAD'];
						$valor	= $fila['VALOR'];
						$sub 	= $fila['VENTAS.CANTIDAD*VENTAS.VALOR'];
						
						$idTag	 = "id=\"IDVENTA" . $idRow . "\"";
						$onclick = "onclick=\"quitarArt(document.getElementById('IDVENTA$idRow').innerText,'$IDCLI','$PLAT')\"";
						
						$array_list[] = "<tr id=\"prev_vent_row\" $onclick><td id=\"ventaIDART\"><a $idTag>$IDART</a></td><td id=\"ventaTITULO\"><a>$titulo</a></td><td id=\"ventaCANTIDAD\"><a>$cant</a></td><td id=\"ventaVALOR\"><a>€ $valor</a></td><td id=\"ventaSUB\"><a>€ $sub</a></td></tr>";
						
						$idRow++;
					}
				}
			}
		}
		$mysqli->close(); # Cerramos conexión con base de datos.
		return $array_list;
	}
	
	function valoresSubtotal(){
		$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
		
		if(!$mysqli->connect_error){
			
			$sql = "SELECT COUNT(*) \"NUM_ART\", SUM(VALOR) \"SUBTOTAL\", SUM(VALOR)*0.21 \"IVA\",SUM(VALOR)+(SUM(VALOR)*0.21) \"TOTAL\" FROM VENTAS";
			
			# Ejecutamos query con evaluación de resultado
			if($res=$mysqli->query($sql)){
				if($res->num_rows > 0){
					$fila = $res->fetch_assoc();
				}
			}
		}
		$mysqli->close(); # Cerramos conexión con base de datos.
		return $fila;
	}
	
	function finVenta($FACTURA,$IDCLI){
		$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
		
		if(!$mysqli->connect_error){
			
			$FACTURA 	= (INT)$FACTURA;
			$IDCLI		= (STRING)$IDCLI;
			
			$sql = "INSERT INTO FACTURAS(IDFACT,FECHA,IDCLI,SUBTOTAL,IVA,TOTAL)
				SELECT $FACTURA,CURDATE(),'$IDCLI',SUM(VALOR) \"SUBTOTAL\", SUM(VALOR)*0.21 \"IVA\", SUM(VALOR)+(SUM(VALOR)*0.21) \"TOTAL\"
				FROM VENTAS";
			
			# Ejecutamos query con evaluación de resultado
			if($res=$mysqli->query($sql)){
				
				$sql="DELETE FROM VENTAS";
				
				if($res=$mysqli->query($sql)){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		$mysqli->close(); # Cerramos conexión con base de datos.
	}
?>