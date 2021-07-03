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

# Módulos de funciones
# --------------------

require('modules/mod_general.php');
# -- variables importadas --

# $active: valores de estilo css para indicar página actual en la barra de navegación.

# -- funciones importadas --

# zeroIzquierda($entrada[string]): añade ceros a la izquierda de un valor para su representación. 

require('modules/mod_ventas.php');
# -- variables importadas --

# [ninguna]

# -- funciones importadas --

# nuevaFactura(): indica el número de la nueva factura a emitir.
# listaPlataformasVenta($plataforma[string],$cliente[string]): devuelve un array con la lista de plataformas para el documento ventas.php
# listaArticulosVentas($PLAT[string],IDCLI[string]): devuelve un array con la lista de artículos filtrados por plataforma para el documento ventas.php
# ventaIniciada(): en caso de cambiar de documento, si la venta se ha iniciado nos mostrará su estado (ver variable $sistema)
# añadirArtVentas($PLAT[string],$IDCLI[string],$IDART[int]): añade el artículo a tabla VENTAS, si ya existe, +1 al campo CANTIDAD. Los artículos serán restados de ARTÍCULOS.
# eliminarArtVentas($PLAT[string],$IDCLI[string],$IDART[int]): operación inversa a 'añadirArtVentas()'.
# listaVentas($IDCLI[string],$PLAT[string]): muestra el listado de la lista de ventas en función de la plataforma.
# valoresSubtotal(): devuelve un array con los valores de SUBTOTAL para documento venta.php.
# finVenta($FACTURA[string],$IDCLI[string]: añade una nueva factura a tabla FACTURAS y elimina el contenide de tabla VENTAS.

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
			# Si los parámetros no están definidos, redirigimos a documento origen.
			
            if(!isset($_GET['plat']) || !isset($_GET['idCli']) || !isset($_GET['idArt']) || !isset($_GET['op'])){
				header('location: venta.php');
			}else{
				$PLAT	= $_GET['plat']; 	# plataforma
				$IDCLI	= $_GET['idCli']; 	# dni del cliente
				$IDART	= $_GET['idArt'];	# id del artículo
				$OP		= $_GET['op'];		# tipo de operación
				
				# VARIABLE $OP
				# ------------
				
				# Definirá el tipo de operación a realizar: añadir o eliminar venta.
				
				switch($OP){
					case "add":
					
						if(añadirArtVentas($PLAT,$IDCLI,$IDART)){ # añade a la lista de venta en curso. Devuelve true/false.
							header("location: venta.php?plat=$PLAT&idCli=$IDCLI");
						}else{ # En caso de error: avisa y redirecciona.
							print "<script>alert('Fallo al realizar la consulta')</script>";
							header("location: venta.php?plat=$PLAT&idCli=$IDCLI");
						}
					
						break;
					
					case "del":
					
						if(eliminarArtVentas($PLAT,$IDCLI,$IDART)){ # elimina de la lista de venta en curso. Devuelve true/false.
							header("location: venta.php?plat=$PLAT&idCli=$IDCLI");
						}else{ # En caso de error: avisa y redirecciona.
							print "<script>alert('Fallo al realizar la consulta')</script>";
							header("location: venta.php?plat=$PLAT&idCli=$IDCLI");
						}
						
						break;
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
