var cancelar = function (){
	window.location.assign("clientes.php");
}

var eliminar = function (idCli){
	var r 		= confirm("¿Seguro que deseas eliminar este cliente?");
	if (r) {
	   window.location.href = 'clientesEliminar.php?idCli=' + idCli;
	}
}

var editar = function (idCli){
	window.location.href = 'clientesEditor.php?op=editar&idCli=' + idCli;
}