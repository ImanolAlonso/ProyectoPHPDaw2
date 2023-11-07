<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="" method="post">
    Usuario:
    <br>
    <input type="text" name="usuario" />
    <br>
    Contraseña:
    <br>
    <input type="password" name="contraseña">
    <br><br>
    <input type="submit" name="enviar" value="Enviar" />
</form>
</body>
</html>
<?php
if ($_POST) {
    //Inicio de sesion para poder guardar valores
    session_start();
    try {
        include_once('conexionBD.php');
        $BD = new ConectarBD();
        $conexion = $BD->getConexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $statm = $conexion->prepare('SELECT * FROM usuarios_registrados WHERE nombre_usuario=:usuario AND passwrd=:pass');
        //Pasar el texto de password a sha1 que es en el formato que estan guardadas las contraseñas en la base de datos
        $statm->execute([':usuario' => $_POST['usuario'], ':pass' => sha1($_POST['contraseña'])]);
        $statm->setFetchMode(PDO::FETCH_ASSOC);
        $contador = $statm->rowCount();
        if ($contador == 0) {
            echo "Error Usuario/Contraseña incorrectos";
        } else {
            //Una vez validado me guardo los datos que voy a usar luego en una sesion y le redirijo a la pagina donde se muestran todos los monitores en este caso consulta.php
            $_SESSION['rol'] = $statm->fetch(PDO::FETCH_ASSOC)['rol'];
            $_SESSION['usuario'] = $_POST['usuario'];
            header('location:consulta.php');
        }
    } catch (PDOException $ex) {
        print "Error: " . $ex->getMessage();
    }
}
?>