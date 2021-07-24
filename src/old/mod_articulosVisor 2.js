var eliminar = function (idArt){
	var r 		= confirm("¿Seguro que deseas eliminar el artículo?");
	if (r) {
	   window.location.href = 'articulosEliminar.php?idArt=' + idArt;
	}
}

var editar = function (idArt){
	window.location.href = 'articulosEditor.php?op=editar&idArt=' + idArt;
}