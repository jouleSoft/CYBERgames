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

require('modules/mod_clientes.php');
# -- variables importadas --

# [ninguna]

# -- funciones importadas --

# existeCliente($dni[string]): devuelve true si el cliente existe. False, en caso contrario.

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
            if(!isset($_GET['idCli']) || !isset($_POST['dni']) || !isset($_POST['nombre']) || !isset($_POST['apellidos']) || !isset($_POST['telefono1']) || !isset($_POST['telefono2']) || !isset($_POST['email']) || !isset($_POST['direccion']) || !isset($_POST['cpostal'])){
				print "Error en parámetro GET/POST";
			}else{
				
				# Recogida de datos
				# Conversión en mayúsculas para los campos de cadena.
				# Nos aseguramos que los campos con caracteres numéricos sean cadena.
				
				$IDCLI		= strtoupper($_GET['idCli']);
				$DNI		= strtoupper($_POST['dni']);
				$NOMBRE		= strtoupper((string)$_POST['nombre']);
				$APELLIDOS	= strtoupper((string)$_POST['apellidos']);
				$TELEFONO1 	= (string)$_POST['telefono1'];
				$TELEFONO2	= (string)$_POST['telefono2'];
				$EMAIL 		= strtoupper((string)$_POST['email']);
				$DIRECCION 	= strtoupper((string)$_POST['direccion']);
				$CPOSTAL	= (int)$_POST['cpostal'];
				
				# Transformamos los caracteres con acentuación en su correspondiente mayúscula
				
				$NOMBRE		= strtr($NOMBRE,"áéíóúü","ÁÉÍÓÚÜ");
				$APELLIDOS	= strtr($APELLIDOS,"áéíóúü","ÁÉÍÓÚÜ");
				$DIRECCION 	= strtr($DIRECCION,"áéíóúü","ÁÉÍÓÚÜ");
				
				# Decodificamos utf8 para su almacenaje en la base de datos.
				
				$NOMBRE 	= utf8_decode($NOMBRE);
				$APELLIDOS	= utf8_decode($APELLIDOS);
				$DIRECCION 	= utf8_decode($DIRECCION);
				
				# Creamos conexión con la base de datos.
				$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
				
				if(existeCliente($DNI) && $IDCLI != $DNI){
					$url = "clientes.php?op=existe"; # Si el cliente existe, redirigir a 'clientes.php' con parámetros para evento javaScript.
				}else{
					if($mysqli->error_connect){
						die("Error al conectar con la base de datos. Error: " . $mysqli->connect_error);
					}else{
						
						$sql = "UPDATE CLIENTES SET
									ID = '$DNI',
									NOMBRE = '$NOMBRE',
									APELLIDOS = '$APELLIDOS',
									TELEFONO1 = '$TELEFONO1',
									TELEFONO2 = '$TELEFONO2',
									EMAIL = '$EMAIL',
									DIRECCION = '$DIRECCION',
									CPOSTAL = $CPOSTAL
								WHERE ID LIKE '$IDCLI'";
						
						if($res = $mysqli->query($sql)){ # Actualizamos registro. Redireccionamos con parámetros para 'clientesVisor.php'
							$url = "clientesVisor.php?idCli=$DNI&op=ok";
						}else{
							$url = "clientesVisor.php?idCli=$IDCLI&op=ko";
						}
						
						$mysqli->close();
						
					}
					
					header("location: $url");
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
