<?php
# Datos de sesión
# ---------------

# Si la sesión no está iniciada, redirige a index.php,
# si lo está recoge los datos del usuario logado actualmente

session_start();
if(!isset($_SESSION['username'])){
    header('location: index.php');
}else{
    $nombre_usr = $_SESSION['nombre'];
	$ape_usr	= $_SESSION['apellidos'];
}

# Módulos de funciones
# --------------------

require('modules/mod_general.php');
# -- variables importadas --

# $active: valores de estilo css para indicar página actual en la barra de navegación.

# -- funciones importadas --

# zeroIzquierda($entrada[string]): añade ceros a la izquierda de un valor para su representación. 

require('modules/mod_articulos.php');
# -- variables importadas --

# [ninguna]

# -- funciones importadas --

# existeArticulo($nombre[string],$plataforma[string]): devuelve true si el artículo existe para una plataforma determinada.
# nuevoID(): genera un nuevo id para añadir un nuevo artículo. El algoritmo rellenará espacios generados al eliminar registros.

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <title>·CYBER·Games·</title>
        <link rel="stylesheet" type="text/css" href="styleSheets/general.css"/>
        <style>            
            #CG{
                margin-top: 120px;
                text-align: center;
            }
            
            #CG p{
                    font-family:sans-serif;
                    font-size: 25px;
            }

            #CG p a{
                color: red;
                font-size: 25px;
                font-family: fantasy;
            }
            
            #nav_hole{
                    margin: 8px 0 0 0;
                    padding: 22px 22px;
                    overflow: hidden;
                    background-color: gray;
            }
        </style>
        <?php
			# Comprobamos la existencia de parámetros POST.
            if(!isset($_POST['titulo']) || !isset($_POST['tipo']) || !isset($_POST['plataforma']) || !isset($_POST['stock']) || !isset($_POST['pcompra']) || !isset($_POST['pventa'])){
				echo "error en parámetro POST";
			}elseif($_POST['plataforma'] == 'PLATAFORMA...' || $_POST['tipo'] == 'GÉNERO...'){ # elemenos SELECT con valor no adecuado.
				header('location: articulosEditor.php?incomplete=true&op=nuevo&idArt='); # redirigimos a 'artículosEditor.php' con parámetros GET para desencadenar evento de JS de aviso.
			}else{
				
				# Recogida de datos
				
				$IDART		= nuevoID();
				$TITULO		= strtoupper($_POST['titulo']); 	# mayúsculas
				$TIPO		= strtoupper($_POST['tipo']); 		# mayúsculas
				$PLATAFORMA = strtoupper($_POST['plataforma']); # mayúsculas
				$STOCK		= (int)$_POST['stock']; 			# valor entero
				$PCOMPRA 	= (float)$_POST['pcompra']; 		# valor coma flotante
				$PVENTA 	= (float)$_POST['pventa']; 			# valor coma flotante
				
				# Transformamos los caracteres con acentuación en su correspondiente mayúscula
				
				$TITULO		= strtr($TITULO,"áéíóúü","ÁÉÍÓÚÜ");
				$TIPO		= strtr($TIPO,"áéíóúü","ÁÉÍÓÚÜ");
				
				# Decodificamos utf8 para su almacenaje en la base de datos.
				
				$TITULO		= utf8_decode($TITULO);
				$TIPO		= utf8_decode($TIPO);
				
				if(existeArticulo($TITULO,$PLATAFORMA)){ # Si el artículo existe redirigimos a documento 'articulos.php' con parámetros GET.
					$url = "articulos.php?op=existe";
				}else{ # Si el artículo no existe lo añadimmos a tabla ARTICULOS.
					$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
					if($mysqli->error_connect){
						die("Error al conectar con la base de datos. Error: " . $mysqli->connect_error);
					}else{
						
						$sql = "INSERT INTO ARTICULOS VALUES($IDART, '$TITULO', '$TIPO', '$PLATAFORMA', $STOCK, $PCOMPRA, $PVENTA)";
						
						if($res = $mysqli->query($sql)){
							$url = "articulos.php?op=ok";
						}else{
							$url = "articulos.php?op=ko";
						}
						
						$mysqli->close();
						
					}
				}
				header("location: $url"); # redirigimos a documento 'articulos.php' con parámetro GET que identificará el resultado de operación.
			}
        ?>
    </head>
    <body>
        <div class="core">
            <div id="nav_hole"><!--Barra de navegación vacía-->
            </div>
            <div id="header"><!--Cabecera-->
                <div id="logo">
                    <p><a>CYBER</a>Games</p>
                </div>
            </div>
            <div id="main" style="padding-top: 25px"><!--Contenido principal de la página-->
                <div id="CG">
                    <p><a>CYBER</a> Games</p>
                </div>
            </div>
            <div id="footer"><!--Pié de página-->
                <p>Powered by Julio Jiménez Delgado. Copyright (C) 2018 - 2019</p>
            </div>
        </div>
    </body>
</html>
