//Función para pasar el parámetro 'dato' por $_GET
var cargarRef = function(dato){
	window.location.assign("articulos.php?plat=" + dato);
}

var cancelar = function (){
	window.location.assign("articulos.php");
}