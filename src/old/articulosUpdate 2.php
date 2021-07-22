<?php
### --Datos de sesión-- ###

# Si la sesión no está iniciada, redirige a index.php,
# si lo está recoge los datos del usuario logado actualmente
session_start();
if(!isset($_SESSION['username'])){
    header('location: index.php');
}else{
    $nombre_usr = $_SESSION['nombre'];
	$ape_usr	= $_SESSION['apellidos'];
}
require('modules/mod_general.php');
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
			# Comprobamos la existencia de parámetros POST y GET.
            if(!isset($_GET['idArt']) || !isset($_POST['titulo']) || !isset($_POST['tipo']) || !isset($_POST['plataforma']) || !isset($_POST['stock']) || !isset($_POST['pcompra']) || !isset($_POST['pventa'])){
				print "Error en parámetro GET";
			}elseif($_POST['plataforma'] == 'PLATAFORMA...' || $_POST['tipo'] == 'GÉNERO...'){ # En caso de que los elementos SELECT tengan un valor inapropiado.
				$IDART = $_GET['idArt'];
				header("location: articulosEditor.php?incomplete=true&op=editar&idArt=$IDART");
			}else{
				
				# Recogida de datos
				
				$IDART		= $_GET['idArt'];
				$TITULO		= strtoupper($_POST['titulo']);		# mayúsculas
				$TIPO		= strtoupper($_POST['tipo']);		# mayúsculas
				$PLATAFORMA = strtoupper($_POST['plataforma']);	# mayúsculas
				$STOCK		= (int)$_POST['stock'];				# valor entero
				$PCOMPRA 	= (float)$_POST['pcompra'];			# valor coma flotante
				$PVENTA 	= (float)$_POST['pventa'];			# valor coma flotante
				
				# Transformamos los caracteres con acentuación en su correspondiente mayúscula
				
				$TITULO		= strtr($TITULO,"áéíóúü","ÁÉÍÓÚÜ");
				$TIPO		= strtr($TIPO,"áéíóúü","ÁÉÍÓÚÜ");
				
				# Decodificamos utf8 para su almacenaje en la base de datos.
				
				$TITULO		= utf8_decode($TITULO);
				$TIPO		= utf8_decode($TIPO);
				
				$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
				if($mysqli->error_connect){
					die("Error al conectar con la base de datos. Error: " . $mysqli->connect_error);
				}else{
					
					$sql = "UPDATE ARTICULOS SET
								NOMBRE='$TITULO',
								TIPO='$TIPO',
								PLATAFORMA = '$PLATAFORMA',
								STOCK=$STOCK,
								PCOMPRA=$PCOMPRA,
								PVENTA=$PVENTA
							WHERE ID=$IDART";
					
					if($res = $mysqli->query($sql)){ # redirigimos a documento 'articulos.php' con parámetro GET que identificará el resultado de operación.
						$url = "articulosVisor.php?idArt=$IDART&op=ok";
					}else{
						$url = "articulosVisor.php?idArt=$IDART&op=ko";
					}
					
					$mysqli->close();
					
				}
				
				header("location: $url");
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
