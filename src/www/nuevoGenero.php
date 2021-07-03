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
			# Comprobamos la existencia de parámetros GET.
			# El parámetro 'gen' se habrá introducido a través de un evento javaScript 'prompt'.
			# Los parámetros 'idArt' y 'op' serán utilizados para volver al documento 'articulosEditor.php' con el mismo estado previo a este documento.
            if(!isset($_GET['gen']) || !isset($_GET['idArt']) || !isset($_GET['op'])){
				header('location: articulos.php');
			}else{
				$GEN	= strtoupper($_GET['gen']);
				$GEN 	= strtr($GEN,"áéíóúü","ÁÉÍÓÚÜ");
				$IDART	= $_GET['idArt'];
				$OP		= $_GET['op'];
				
				$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
				
				if(!$mysqli->connect_error){
					$sql = "SELECT * FROM TIPOS WHERE TIPO LIKE '$GEN'";
					if($res=$mysqli->query($sql)){
						if($res->num_rows == 0){
							$sql = "INSERT INTO TIPOS VALUES(NULL,'$GEN');";
							if($res=$mysqli->query($sql)){
								header("location: articulosEditor.php?op=$OP&idArt=$IDART");
							}
						}else{
							header("location: articulosEditor.php?op=$OP&idArt=$IDART");
						}
					}else{
						print "Error en la ejecución del mandato sql";
					}
				}else{
					die("Error al conectar con la base de datos. Error: " . $mysqli->connect_error);
				}
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
