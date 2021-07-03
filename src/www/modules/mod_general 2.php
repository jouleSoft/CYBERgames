<?php
	# GENERAL
    # Marca la sección actual de la barra de navegación---
    $active = "style=\"background-color: whitesmoke; color: red; cursor: default;\"";
	
	#FUNCIONES
	
	function zeroIzquierda($entrada){
		# --Rellena con ceros a la izquierda
		# Usada en: articulos.php
		
		$long = strlen($entrada);
		
		switch($long){
			case 1:
				$salida = "0000" . $entrada;
				break;
			case 2:
				$salida = "000" . $entrada;
				break;
			case 3:
				$salida = "00" . $entrada;
				break;
			case 4:
				$salida = "0" . $entrada;
				break;
			case 5:
				$salida = $entrada;
				break;
		}
		
		return $salida;
	}
?>