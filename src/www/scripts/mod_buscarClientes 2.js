var cancelar = function(){
	var url = "http://localhost/venta.php?plat=";
	window.location.assign(url);
}

var operacionRealizada = function(url){
	switch(url){
		case "http://localhost/buscarClientes.php?op=ok":
			window.alert("Operación realizada con éxito");
			break;
			
		case "http://localhost/buscarClientes.php?op=ko":
			window.alert("Ninguna operación ha sido realizada. Por favor, contacte con el administrador");
			break;
		
		case "http://localhost/buscarClientes.php?op=existe":
			window.alert("Ya existe un cliente con ese DNI en la base de datos");
			break;
	}
}

var buscarCliente = function(){
	var dato 	= prompt("Por favor, introduzca el nombre o apellidos del cliente a buscar:");
	var url		= "buscarClientes.php?search=" + dato;
	if(dato != ''){
		window.location.assign(url);
	}
}

var seleccionarCliente = function(idCli){
	var url = "http://localhost/venta.php?plat=&idCli=" + idCli;
	window.location.assign(url);
}