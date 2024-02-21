<?php
    require_once("Conexion.php");
    
    class Seguridad extends Conexion {
        protected $db;

		function __construct() {
            $conn = new conexion();
            $this->db = $conn->get_ConexionBD();
        }

        function iniciarSesion() {
            if (isset($_POST['login'])) {

                $dni = $_POST['dni'];
                $clave = $_POST['clave'];

                try {
                    if (isset($_POST['tipo']) && $_POST['tipo'] == "Cliente") {
                        $nombre = $_POST['nombre'];

                            $this->__construct();
                            $query = $this->db->prepare(
                                "SELECT nombre, dni, clave FROM clientes
                                WHERE
                                nombre  = '" . $nombre . "' AND
                                dni     = '" . $dni . "' AND
                                clave   = '" . $clave . "'"
                            );
                            $query->execute();
                            $numFilas = $query->rowCount();

                            if ($numFilas < 1)
                                $this->mensaje("Credenciales incorrectas.", "secondary");
                            else {
                                $this->crearCookie("nombre", $nombre);
                                $this->crearCookie("dni", $dni);
                                header("location: index.php");
                            }
                    }
                    else if (isset($_POST['tipo']) && ($_POST['tipo'] == "Staff")) {
                        $iniciales = $_POST['iniciales'];

                            $this->__construct();
                            $query = $this->db->prepare(
                                "SELECT dni, iniciales, clave FROM usuarios
                                WHERE
                                dni         = '" . $dni . "' AND
                                iniciales   = '" . $iniciales . "' AND
                                clave       = '" . $clave . "'"
                            );
                            $query->execute();
                            $numFilas = $query->rowCount();

                            if ($numFilas < 1)
                                $this->mensaje("Credenciales incorrectas.", "secondary");
                            else {
                                $this->crearCookie("dni", $dni);
                                $this->crearCookie("iniciales", $iniciales);

                                header("location: index.php");
                            }
                    }
                } catch (Exception $e) {
                    exit("Error: " . $e->getMessage());
                } finally {
                    $this->db = null;
                }
            }
        }

        function cerrarSesion() {
            if (isset($_POST['logout'])) {
                $this->borrarCookie("dni");
                $this->borrarCookie("iniciales");
                $this->borrarCookie("nombre");
                header("location: index.php");
            }
        }

        function comprobarLogin() {
            // Medida 1: si falta alguna cookie, se manda FALSE
            if ((!isset($_COOKIE['dni'])) && (!isset($_COOKIE['nombre']) || !isset($_COOKIE['iniciales'])))
                return false;

            // Medida 2: comparar valores de las cookies con la BD de ese usuario
            try {
                $this->__construct();

                if (isset($_COOKIE['nombre'])) {
                    $query = $this->db->prepare(
                        "SELECT nombre, dni FROM clientes WHERE
                        nombre   = '" . $_COOKIE['nombre'] . "' AND
                        dni     = '" . $_COOKIE['dni'] . "'");
                }

                else if (isset($_COOKIE['iniciales'])) {
                    $query = $this->db->prepare(
                        "SELECT dni, iniciales FROM usuarios WHERE
                        dni       = '" . $_COOKIE['dni'] . "' AND
                        iniciales   = '" . $_COOKIE['iniciales'] . "'"
                    );
                }

                $query->execute();

                if ($query->rowCount() < 1)
                    return false;
                else  if ($query->rowCount() == 1)
                    return true;
            } catch (Exception $e) {
                exit("Error: " . $e->getMessage());
            } finally {
                $this->db = null;
            }
        }

        function comprobarCliente(string $dni) {
            if ($this->comprobarLogin()) {
                try {
                    $this->__construct();
                    $query = $this->db->prepare(
                        "SELECT dni FROM clientes WHERE dni = '" . $dni . "'"
                    );
                    $query->execute();
                    $numFilas = $query->rowCount();

                    if ($numFilas == 1)
                        return true;
                    else
                        return false;
                }
                catch (Exception $e) {
                    exit("Error: " . $e->getMessage());
                } finally {
                    $this->db = null;
                }
            }
            else
                return false;
        }

        function permisoUsuario(string $dni) {
            if ($this->comprobarLogin() && !$this->comprobarCliente($dni)) {
                try {
                    $this->__construct();
                    $query = $this->db->prepare(
                        "SELECT permiso FROM usuarios WHERE dni = '" . $dni . "'"
                    );
                    $query->execute();
                    return $query->fetch()['permiso'];
                }
                catch (Exception $e) {
                    exit("Error: " . $e->getMessage());
                } finally {
                    $this->db = null;
                }
            }
            else
                return "";
        }
    }
?>