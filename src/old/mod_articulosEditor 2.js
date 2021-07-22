//Función para comprobar que los 'select' tienen valor útil

var comprobarSelect = function (url){
	if(url == "http://localhost/articulosEditor.php?incomplete=true&op=nuevo&idArt="){
		alert("Debe de seleccionar PLATAFORMA y GÉNERO");
	}
}

var valoresEditar = function (genero,plataforma,op){
	if(op == 'editar'){
		document.getElementById("selectorGénero").value = genero;
		document.getElementById("selectorPlataforma").value = plataforma;
	}
}

var añadirPlataforma = function(op,idArt){
	var dato 	= prompt("Por favor, introduzca la plataforma a añadir");
	var url		= "nuevaPlataforma.php?plat=" + dato + "&op=" + op + "&idArt=" + idArt;
	if(dato != ''){
		window.location.assign(url);
	}
}

var añadirGenero = function(op,idArt){
	var dato 	= prompt("Por favor, introduzca el género a añadir");
	var url		= "nuevoGenero.php?gen=" + dato + "&op=" + op + "&idArt=" + idArt;
	if(dato != ''){
		window.location.assign(url);
	}
}