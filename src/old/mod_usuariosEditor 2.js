var cancelar = function (param,op){
	var url;
	
	if(op == "editar"){
		url = "usuariosVisor.php?idUsr=" + param;
	}else{
		url = "admUsuarios.php";
	}
	
	window.location.assign(url);
}
