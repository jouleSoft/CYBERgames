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
            if(!isset($_GET['idArt'])){
				print "Error en parámetro GET";
			}else{
				# Eliminamos el artículo de tabla ARTITULOS
				$IDART 	= $_GET['idArt'];
				$sql	= "DELETE FROM ARTICULOS WHERE ID=$IDART";
				
				$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
				
				if($mysqli->connect_error){
					header('location: index.php?login=connection');
				}else{
					if($res=$mysqli->query($sql)){ # redirigimos a documento 'articulos.php' con parámetro GET que identificará el resultado de operación.
						header('location: http://localhost/articulos.php?op=ok');
					}else{
						header('location: http://localhost/articulos.php?op=ko');
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
