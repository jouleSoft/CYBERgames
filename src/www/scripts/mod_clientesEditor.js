var cancelar = function (){
	window.location.assign("clientes.php");
}

var comprobarCampos = function (url){
	if(url == "http://localhost/clientesEditor.php?incomplete=true&op=nuevo&idCli="){
		alert("Debe de introducir DNI, NOMBRE y APELLIDOS para continuar");
	}
}