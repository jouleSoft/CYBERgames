var openEditor = function(op,idCli){
	var x;
	
	if(idCli == ""){
		x = "&idCli=";
	}else{
		x = "&idCli=" + idCli;
	}
	var url = "clientesEditor.php?op=" + op + x;
	return window.location.assign(url);
}

var operacionRealizada = function(url){
	switch(url){
		case "http://localhost/clientes.php?op=ok":
			window.alert("Operación realizada con éxito");
			break;
			
		case "http://localhost/clientes.php?op=ko":
			window.alert("Ninguna operación ha sido realizada. Por favor, contacte con el administrador");
			break;
		
		case "http://localhost/clientes.php?op=existe":
			window.alert("Ya existe un cliente con ese DNI en la base de datos");
			break;
	}
}

var buscarCliente = function(){
	var dato 	= prompt("Por favor, introduzca el nombre o apellidos del cliente a buscar:");
	var url		= "clientes.php?search=" + dato;
	if(dato != ''){
		window.location.assign(url);
	}
}

var abrirVisor = function(idCli){
	var url = "http://localhost/clientesVisor.php?idCli=" + idCli;
	window.location.assign(url);
}