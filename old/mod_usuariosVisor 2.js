var cancelar = function (){
	window.location.assign("admUsuarios.php");
}

var eliminar = function (idUsr){
	var r 		= confirm("¿Seguro que deseas eliminar este usuario?");
	if (r) {
	   window.location.href = 'usuariosEliminar.php?idUsr=' + idUsr;
	}
}

var editar = function (idUsr){
	window.location.href = 'usuariosEditor.php?op=editar&idUsr=' + idUsr;
}