<?php
//Control de que se ha loggeado para poder visualizar esta pagina
session_start();
if (!$_SESSION) {
    header("location:login.php");
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
    <p>Conectado como: <?php echo $_SESSION['usuario'] ?></p>

    <table border="1px">
        <tr>
            <td style="font-weight:bold">Marca</td>
            <td style="font-weight:bold">Resolucion</td>
            <td style="font-weight:bold">Precio</td>
            <td style="font-weight:bold">Imagen</td>
            <td style="font-weight:bold">Imagen BLOB</td>
        </tr>
        <?php
        //Llamo a la funcion para que me devuelva todas las filas de la base de datos con todas sus columnas
        $monitores = obtenerDatos();
        foreach ($monitores as $monitor) {
            echo
            //['nombre de la columna con el que esta guardado en la base de datos']
                '<tr>' . '<td>' . $monitor['marca'] . '</td>' .
                '<td>' . $monitor['resolucion'] . '</td>' .
                '<td>' . $monitor['precio'] . '</td>' .
                '<td>'. '<img width="90px" height="90px" src="./img/' . $monitor['nom_imagen'] . '">' .'</td>'.
                '<td>' .'<img width="90px" height="90px" src="data:image/jpeg;base64,' . base64_encode($monitor['imagen']) . '"></td>'.
                //Enlaces con rutas amigables
                '<td>' . '<a href="ms/' . $monitor['id'] . '">Detalles</a>' . '</td>'.
                '<td>' . '<a href="visualizar/' . $monitor['nom_imagen'] . '">Visualizar</a>' . '</td></tr>';
        }
        ?>
    </table>
    <br>
    <?php 
        //Enlace para cerrar sesion
        echo "<a href='cerrarSesion.php'>Cerrar Sesion</a>";
    ?>
    &emsp;
    <?php
    //Control de si es admin para mostrar el boton para que pueda acceder a la pagina donde se dan de alta los nuevos monitores
    if($_SESSION['rol'] == 'administrador'){
        echo '<a href="altaDatos.php">Dar de alta un monitor</a>';
    }
    ?>
</body>

</html>


<?php
function obtenerDatos()
{
    try {
        include_once('conexionBD.php');
        $BD = new ConectarBD();
        $conexion = $BD->getConexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $statm = $conexion->prepare('SELECT * FROM monitores');
        $statm->execute();
        $statm->setFetchMode(PDO::FETCH_ASSOC);
        //Devuelve todas las filas y columnas de la base de datos
        return $statm->fetchAll();
    } catch (PDOException $ex) {
        print "Error: " . $ex->getMessage();
    }
}
?>