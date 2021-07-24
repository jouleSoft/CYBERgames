var openEditor = function(op,idUsr){
	var x;
	
	if(idUsr == ""){
		x = "&idUsr=";
	}else{
		x = "&idUsr=" + idUsr;
	}
	var url = "usuariosEditor.php?op=" + op + x;
	return window.location.assign(url);
}

var operacionRealizada = function(url){
	switch(url){
		case "http://localhost/admUsuarios.php?op=ok":
			window.alert("Operación realizada con éxito");
			break;
			
		case "http://localhost/admUsuarios.php?op=ko":
			window.alert("Ninguna operación ha sido realizada. Por favor, contacte con el administrador");
			break;
		
		case "http://localhost/admUsuarios.php?op=existe":
			window.alert("El username introducido ya está siendo utilizado por otro usuario en la base de datos");
			break;
	}
}

var buscarCliente = function(){
	var dato 	= prompt("Por favor, introduzca el nombre, apellidos o username del usuario a buscar:");
	var url		= "admUsuarios.php?search=" + dato;
	if(dato != ''){
		window.location.assign(url);
	}
}

var abrirVisor = function(idUsr){
	var url = "http://localhost/usuariosVisor.php?idUsr=" + idUsr;
	window.location.assign(url);
}