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
			# --Se recogen los datos de login----------------------
            if(isset($_POST['password']) && isset($_POST['user'])){
				$user = strtoupper($_POST['user']);
				$pass = $_POST['password'];
            }
            
			# --Creación de conexión contra la base de datos-------------------------
            $mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
			
			# --Evaluación de error de conexión y ejeccución de login----------------
            if($mysqli->connect_error){
                header('location: index.php?login=connection');
            }else{
				# --Ejecutamos query y usammos el valor binario de la operación para evaluar su ejecución-------------------
                if($res=$mysqli->query("SELECT USERNAME,PASS,NOMBRE,APELLIDOS,ADMIN FROM USUARIOS WHERE USERNAME='$user'")){
					# --Comprobar si hay resultados--
					if($res->num_rows > 0){
						# --Debido a que los nombres de usuario son irrepetibles, el número de filas devueltas deberá de ser 1--
						# --Introducimos resultado en variable $fila--
						$fila = $res->fetch_assoc();
						
						# --Se comprueba coincidencia de credenciales e iniciamos sesión si hay el resultado es verdadero--
						if(password_verify($pass,$fila['PASS'])){
							session_start();
							$_SESSION['username']	= $fila['USERNAME'];
							$_SESSION['nombre']		= utf8_encode($fila['NOMBRE']);
							$_SESSION['apellidos']	= utf8_encode($fila['APELLIDOS']);
							$_SESSION['admin']		= $fila['ADMIN'];
							
							# --Cerramos conexión con base de datos y cargamos página inicial--
							$mysqli->close();
							
							header('location: venta.php');
						}else{ # --En caso de no coincidencia cerramos conexión y redireccionamos a página login.php--
							$mysqli->close();
							header('location: login.php?login=failed');
						}
					}else{ # --Si el número de columnas devueltas no es superior a 0, entonces el usuario no existe--
						$mysqli->close();
						header('location: login.php?login=failed');
					}
				}else{ # --Si la query no se realiza correctamente, devolvemos a la página login.php el valor 'connection'--
					$mysqli->close();
					header('location: login.php?login=connection2');
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
