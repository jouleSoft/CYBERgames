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
            if(!isset($_GET['idCli'])){
				echo "Error";
			}else{
				
				$IDCLI 	= strtoupper($_GET['idCli']);
				$sql	= "DELETE FROM CLIENTES WHERE ID = '$IDCLI'";
				
				$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
				
				# --Evaluación de error de conexión y ejeccución de login--
				if($mysqli->connect_error){
					die("Error al conectar con la base de datos. Error: " . $mysqli->connect_error);
				}else{
					# Ejecutamos query y usammos el valor binario de la operación para evaluar su ejecución. Redireccionaremos en cualquier caso.
					if($res=$mysqli->query($sql)){
						header('location: clientes.php?op=ok');
					}else{
						header('location: http://localhost/clientes.php?op=ko');
					}
				}
				$mysqli->close();
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
