<?php
    require_once("../model/config.php");
    require_once("Utilidades.php");
    
    class Conexion extends Utilidades {
        protected $conexion;

        protected function __construct() {
            $this->conexion = new PDO(
                "mysql:host=".DB_HOST.";dbname=".DB_NAME.";".DB_CHARSET, DB_USER, DB_PASSWORD
            );
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            if (!$this->conexion)
                die("<h2 class='h2 display-2'>Error: " . $this->conexion->errorCode() . "</h2>");
        }

        function get_conexionBD() {
            return $this->conexion;
        }
    }
?>