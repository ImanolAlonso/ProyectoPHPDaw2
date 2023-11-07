<?php

require_once 'configuracionBD.php';
class ConectarBD
{

    private $direccion = DIRECCION;
    private $baseDeDatos = BASE_DE_DATOS;
    private $usuario = USUARIO;
    private $password = PASSWORD;
    private $charset = CHARSET;
    private $conexion;

    function getConexion()
    {
        try {
            $this->conexion = new PDO(
                'mysql:host=' . $this->direccion .
                ';dbname=' . $this->baseDeDatos . ';charset=' . $this->charset,
                $this->usuario,
                $this->password
            );

            $this->conexion->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

        } catch (PDOException $error) {

            echo "¡ERROR: !" . $error->getMessage();
            die();
        }
        return $this->conexion;
    }

    function cerrarConexion()
    {
        $this->conexion = null;
    }
}

?>