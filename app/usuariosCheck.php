<?php
# Datos de sesión
# ---------------

# Si la sesión no está iniciada, redirige a index.php,
# si lo está recoge los datos del usuario logado actualmente

# En este documento se identifica el tipo de usuario: administrador o estándar.

session_start();
if(!isset($_SESSION['username'])){
    header('location: index.php');
}else{
    $nombre_usr = $_SESSION['nombre'];
	$ape_usr	= $_SESSION['apellidos'];
	$admin		= $_SESSION['admin'];
	
	if($admin == 0){ # Si el usuario no es administrador, redirecciona a index.php.
		header('location: index.php');
	}
}

# Módulos de funciones
# --------------------

require('modules/mod_general.php');
# -- variables importadas --

# $active: valores de estilo css para indicar página actual en la barra de navegación.

# -- funciones importadas --

# zeroIzquierda($entrada[string]): añade ceros a la izquierda de un valor para su representación. 

require('modules/mod_usuarios.php');

# -- variables importadas --

# [ninguna]

# -- funciones importadas --

# existeUsername($username[string]): devuelve true si el username ya está en uso en la tabla USUARIOS.
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
			# --Se recogen datos--
            if(!isset($_POST['username']) || !isset($_POST['nombre']) || !isset($_POST['apellidos']) || !isset($_GET['op'])){
				print "Error en parámetro GET";
				echo "error";
			}else{
				$OP			= $_GET['op'];
				
				if(isset($_GET['idUsr'])){
					$IDUSR 		= strtoupper($_GET['idUsr']);
				}
				
				if(isset($_POST['admin'])){
					$ADMIN = 1;
				}else{
					$ADMIN = 0;
				}
				
				$USERNAME	= strtoupper($_POST['username']);
				$NOMBRE 	= strtoupper(utf8_decode($_POST['nombre']));
				$APELLIDOS 	= strtoupper(utf8_decode($_POST['apellidos']));
				
				
				#OPERACIONES: nuevo usuario / editar usuario.
				
				# Almacenamos la query y el hash de contraseña para el caso de nuevo usuario.
				switch($OP){
					case "nuevo":
						
						$PASS = password_hash($USERNAME,PASSWORD_DEFAULT);
						$sql = "INSERT INTO USUARIOS VALUES (NULL,'$USERNAME','$PASS','$NOMBRE','$APELLIDOS',$ADMIN)";
						
						break;
					
					case "editar":
						
						$sql = "UPDATE USUARIOS SET 
									USERNAME='$USERNAME',
									NOMBRE='$NOMBRE',
									APELLIDOS='$APELLIDOS',
									ADMIN=$ADMIN
								WHERE USERNAME = '$IDUSR'";
						
						break;
				}
				
				# Para evitar el error de integridad: USERNAME único, realizamos la siguiente valoración:
				#	a. Si el usuario existe y la operación es 'nuevo' usuario, entonces no es posible.
				#	b. Si el usuario existe y la operación es 'editar' usuario, y además el usuario nuevo es diferente al antiguo, no es posible.
				#	c. Si no se cumplen los casos a. o b. se procederá con la ejecución del mandato SQL para la actualización o inserción.
				
				if(existeUsername($USERNAME) && $OP == 'nuevo'){
					header('location: admUsuarios.php?op=existe');
				}elseif(existeUsername($USERNAME) && $OP == 'editar' && $USERNAME != $IDUSR){
					header('location: admUsuarios.php?op=existe');
				}else{
					#Conexión con la base de datos
					$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
					if(!$mysqli->connect_error){
						if($res = $mysqli->query($sql)){ # Ejecución de query.
							$mysqli->close();
							header("location: admUsuarios.php?op=ok");
						}else{
							$mysqli->close();
							header("location: admUsuarios.php?op=ko");
						}
					}else{
						die("Error al conectar con la base de datos. Error: " . $mysqli->connect_error);
					}
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
