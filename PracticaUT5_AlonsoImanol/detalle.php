<?php
//Control de si no ha iniciado sesion que no pueda acceder
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
    <table border="1">
        <?php
        //Funcion para obtener la fila con sus columnas pero esta vez solo devuelve una unica fila por eso no hay forEach
        $monitor = obtenerDatos();
        echo
        //['nombre de la columna con el que esta guardado en la base de datos']
            '<tr> <th>Marca</th> <td>' . $monitor['marca'] . '</td></tr>' .
            '<tr> <th>Resolucion</th> <td>' . $monitor['resolucion'] . '</td></tr>' .
            '<tr> <th>Precio</th> <td>' . $monitor['precio'] . '</td></tr>' .
            '<tr> <th>Panel</th> <td>' . $monitor['panel'] . '</td></tr>' .
            //Cambio del src = ./img por .././img porque se añade como otro directorio al hacer la url amigable aunque visualmente no lo hay
            '<tr><th>Imagen</th> <td>'. '<img width="150px" height="100px" src=".././img/' . $monitor['nom_imagen'] . '">' . '</td></tr>' .
            '<tr> <th>Imagen BLOB</th> <td>' . '<img width="150px" height="100px" src="data:image/jpeg;base64,' . base64_encode($monitor['imagen']) . '"></td></tr>';
        ?>
    </table>
    <?php
    //Enlace para cerrar sesion
    echo '<a href="../cerrarSesion.php">Cerrar Sesion</a>';
    ?>
</body>

</html>
<?php

function obtenerDatos()
{
    //Cojo el id que viene por via get desde la pagina de consulta para buscar en la base de datos ese id y sacar toda la informacion de ese monitor
    $id = $_GET["id"];
    try {
        include_once('conexionBD.php');
        $BD = new ConectarBD();
        $conexion = $BD->getConexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $statm = $conexion->prepare('SELECT * FROM monitores WHERE id=:id');
        $statm->execute([':id' => $id]);
        $statm->setFetchMode(PDO::FETCH_ASSOC);
        if($statm->rowCount() != 0) {
        //Devuelve una unica fila por eso uso solamente fetch() y no fetchAll como en la pagina de consulta.php
        return $statm->fetch();
        } else {
            echo'Producto no encontrado';
        }
    } catch (PDOException $ex) {
        print "Error: " . $ex->getMessage();
    }
}
?>