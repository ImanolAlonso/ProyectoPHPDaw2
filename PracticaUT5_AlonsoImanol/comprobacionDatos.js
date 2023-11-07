function comprobarFormulario() {
    //Me guardo los elementos del html
    let marca = document.getElementById("marca");
    let resolucion = document.getElementById("resolucion");
    let precio = document.getElementById("precio");
    let imagen = document.getElementById("imagen");
    
    //Variable que va a devolver la funcion
    let valido = true;
    
    //El string que sirve para comprobar si es un numero tipo double
    const comprobarNumero = /^([0-9])+(\.([0-9])+)?$/;
    
    //Array de todos los campos que quiero comprobar
    let array = [marca, resolucion, precio,imagen];
    
    //Con el foreach hago la comprobacion para todos los campos del array
    array.forEach(campo => {
        comprobarCampo(campo)
    })

    function comprobarCampo(campo) {
        if (campo.value === "" || /^\s+$/.test(campo.value)){
            alert(`El campo ${campo.id} no puede estar vacío`);
            valido = false;
        }
    }
    if (!comprobarNumero.test(precio.value)) {
        alert(`El campo precio tiene que ser un numero`);
        valido = false;
    }
    return valido;
}