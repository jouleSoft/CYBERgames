<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <title>·CYBER·Games·</title>
        <link rel="stylesheet" type="text/css" href="styleSheets/general.css"/>
		<link rel="stylesheet" type="text/css" href="styleSheets/login.css"/>
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
                <form method="post" action="loginCheck.php">
                    <div class="fila_login">
						<div class="columna_label">
							<label for="user" style="margin-right: 42px">Usuario</label>
						</div>
						<div class="columna_input">
							<input type="text" id="user" name="user" maxlength="15" autofocus required />
						</div>
                    </div>
                    <div class="fila_login">
						<div class="columna_label">
							<label for="password" style="margin-right: 15px">Contraseña</label>
						</div>
						<div class="columna_input">
							<input type="password" id="password" name="password" required />
						</div>
                    </div>
                    <input type="submit" id="submit" value="Log in" />
                </form>
                <div id="login_failed"><!--Resultado de login fallido-->
                    <?php
						# Comprobamos la existencia de parámetros GET.
						# En caso de intento fallido de login o problemas de conexión, se mostrará abajo de formulario en rojo.
                        if(isset($_GET['login'])){
                            
                            $login = $_GET['login'];
                            
                            switch($login){
								case "failed":
									print "<a>Usuario y/o contraseña incorrectos</a>";
									break;
								case "connection":
									print "<a>Conexión fallida. Póngase en contacto con su administrador [ERR_01]</a>";
									break;
								case "connection2":
									print "<a>Conexión fallida. Póngase en contacto con su administrador [ERR_02]</a>";
									break;
                            }
                        }   
                    ?>
                </div>
            </div>
            <div id="footer">
                <p>Powered by Julio Jiménez Delgado. Copyright (C) 2018 - 2019</p>
            </div>
        </div>
    </body>
</html>
