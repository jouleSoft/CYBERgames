var buscarCliente = function(plat){
	var dato 	= prompt("Por favor, introduzca el DNI del cliente a añadir");
	var url		= "venta.php?plat=" + plat + "&idCli=" + dato;
	if(dato != ''){
		window.location.assign(url);
	}
}
var cargarRef = function(dato, idCli){
	var url = "venta.php?plat=" + dato + "&idCli=" + idCli;
	window.location.assign(url);
}

var cargarPlat = function(idCli,plat){
	var url		= "venta.php?plat=" + plat + "&idCli=" + idCli;
	if(plat != ''){
		window.location.assign(url);
	}
}

var añadirArt = function(idArt,stock,idCli,plat){
	var url = "ventaCheck.php?plat=" + plat + "&idCli=" + idCli + "&idArt=" + idArt + "&op=add";
	if(plat != '' && idCli != '' && idArt != '' && stock != '0'){
		window.location.assign(url);
	}
}

var quitarArt = function(idArt,idCli,plat){
	var url = "ventaCheck.php?plat=" + plat + "&idCli=" + idCli + "&idArt=" + idArt + "&op=del";
	if(plat != '' && idCli != '' && idArt != ''){
		window.location.assign(url);
	}
}

var finalizarVenta = function(factura,idCli){
	var url = "ventaFin.php?factura=" + factura + "&idCli=" + idCli;
	window.location.assign(url);
}

var comprobarVenta = function(url){
	switch(url){
		case "http://localhost/venta.php?op=ok":
			window.alert("Operación realizada con éxito");
			break;
			
		case "http://localhost/venta.php?op=ko":
			window.alert("Ninguna operación ha sido realizada. Por favor, contacte con el administrador");
			break;
	}
}

var buscarClienteAvanzado = function (){
	var c = confirm('Cliente no encontrado, ¿desea abrir la ventana de búsqueda avanzada?');
	if (c){
		window.location.assign('buscarClientes.php');
	}
}