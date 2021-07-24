var openEditor = function(op,idArt){
	var x;
	
	if(idArt == ""){
		x = "&idArt=";
	}else{
		x = "$idArt=" + idArt;
	}
	var url = "articulosEditor.php?op=" + op + x;
	return window.location.assign(url);
}

var operacionRealizada = function(url){
	switch(url){
		case "http://localhost/articulos.php?op=ok":
			window.alert("Operación realizada con éxito");
			break;
			
		case "http://localhost/articulos.php?op=ko":
			window.alert("Ninguna operación ha sido realizada. Por favor, contacte con el administrador");
			break;
		
		case "http://localhost/articulos.php?op=existe":
			window.alert("Ya existe un artículo con ese nombre en la base de datos");
			break;
	}
}

var buscarArticulo = function(){
	var dato 	= prompt("Por favor, introduzca el artículo a Buscar");
	var url		= "articulos.php?plat=[BUSCAR]&search=" + dato;
	if(dato != ''){
		window.location.assign(url);
	}
}

var abrirVisor = function(idArt){
	var url = "/articulosVisor.php?idArt=" + idArt;
	window.location.assign(url);
}