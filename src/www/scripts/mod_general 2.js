var logout = function(){
    var r = confirm("¿Seguro que deseas cerrar sesión?");
    if (r) {
       window.location.href = 'logout.php';
    }
}