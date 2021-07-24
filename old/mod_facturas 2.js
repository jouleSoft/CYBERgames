var buscarFactura = function(){
	var dato 	= prompt("Por favor, introduzca el DNI del cliente para mostrar sus facturas:");
	var url		= "facturas.php?search=" + dato;
	if(dato != ''){
		window.location.assign(url);
	}
}

var abrirVisor = function(idFact){
	var url = "facturasVisor.php?idFact=" + idFact;
	window.location.assign(url);
}