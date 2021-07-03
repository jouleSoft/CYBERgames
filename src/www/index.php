<?php
# Datos de sesión
# ---------------

# Si la sesión no está iniciada redirige a login.php,
# si lo está, a venta.php.

session_start();

if(isset($_SESSION['username'])){
    header('location: venta.php');
}else{
    header('location: login.php');
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
