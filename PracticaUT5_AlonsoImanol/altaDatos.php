<?php
//Control de que haya inciado sesion y si ha iniciado sesion controlar si es administrador o no para mostrar la pagina
session_start();
if (!$_SESSION) {
    header("location:login.php");
}
if ($_SESSION["rol"] != 'administrador') {
    header('location:consulta.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <p>Conectado como: <?php echo $_SESSION['usuario'] ?></p><br>
    <!-- Funcion de JS al hacer submit para que si devuelve un false la parte de abajo de isset$_POST['enviar'] no se ejecute -->
    <form action="" method="POST" enctype="multipart/form-data" onsubmit="return comprobarFormulario();">
        <label for="marca">Marca</label>
        <input type="text" name="marca" id="marca"><br><br>
        <label for="resolucion">Resolucion</label>
        <input type="text" name="resolucion" id="resolucion"><br><br>
        <label for="precio">Precio</label>
        <input type="text" name="precio" id="precio"><br><br>
        <label for="tipo">Tipo</label>
        <select name="tipo" id="tipo">
            <option value="TN">TN</option>
            <option value="IPS" selected>IPS</option>
            <option value="OLED">OLED</option>
        </select><br><br>
        <label for="imagen">Foto</label>
        <input type="file" name="imagen" id="imagen"><br><br>
        <input type="submit" name="enviar" id="enviar">&emsp;
        <?php
        /*Enlace para cerrar sesion*/
        echo '<a href="cerrarSesion.php">Cerrar Sesion</a>';
        ?>
    </form>
    <?php
    //Si en la validacion devuelve un true ejecuto la funcion para almacenar los datos en la base de datos
    if (isset($_POST['enviar'])) {
        cargarDatos($_POST);
    }
    ?>
    <!-- Fichero JS para controlar que se hayan introducido todos los datos y de la forma correcta -->
    <script src="comprobacionDatos.js"></script>
</body>

</html>
<?php
function cargarDatos($datos)
{
    if (is_uploaded_file($_FILES['imagen']['tmp_name'])) {
        //Me guardo el contenido en una variable para luego introducirlo en la base de datos y muevo la imagen a mi directorio desde la "nube" en la que esta almacenada
        $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], "./img/" . $_FILES['imagen']['name']);
    }

    try {
        include_once('conexionBD.php');
        $BD = new ConectarBD();
        $conexion = $BD->getConexion();
        $statm = $conexion->prepare('INSERT INTO monitores (marca, resolucion, precio, panel, nom_imagen, imagen) '
            . 'VALUES (:marca, :resolucion, :precio, :panel, :nom_imagen, :imagen)');
        $statm->execute([
            ':marca' => $datos['marca'],
            ':resolucion' => $datos['resolucion'],
            ':precio' => $datos['precio'],
            ':panel' => $datos['tipo'],
            ':nom_imagen' => $_FILES['imagen']['name'],
            ':imagen' => $imagen
        ]);
    } catch (PDOException $ex) {
        print "Error: " . $ex->getMessage();
    }
}
?>