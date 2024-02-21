<?php
    require_once("Seguridad.php");

    class Consultas extends Seguridad {

        protected $db;

		function __construct() {
            $conn = new conexion();
            $this->db = $conn->get_ConexionBD();
        }


        // Clientes

        function listarClientesAlta($dni = false) {
            try {
                $this->__construct();

                if ($dni) {
                    $query = $this->db->prepare(
                        "SELECT id_cliente, dni, nombre, apellidos, direccion, fecha_alta, clave,
                        telefono, email, fecha_nac, preferencias FROM clientes
                        WHERE dni = '" . $dni . "' AND fecha_baja IS NULL"
                    );
                }

                else {
                    $query = $this->db->prepare(
                        "SELECT id_cliente, dni, nombre, apellidos, direccion, fecha_alta,
                        telefono, email, fecha_nac, preferencias FROM clientes WHERE fecha_baja IS NULL"
                    );
                }

                $query->execute();

                if ($dni)
                    return $query->fetch();
                else
                    return $query->fetchAll();
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }
        }

        function listarClientesBaja($dni = false) {
            try {
                $this->__construct();

                if ($dni) {
                    $query = $this->db->prepare(
                        "SELECT dni, nombre, apellidos, direccion, fecha_baja,
                        telefono, email, fecha_nac, preferencias FROM clientes
                        WHERE dni = '" . $dni . "' AND fecha_baja IS NOT NULL"
                    );
                }

                else {
                    $query = $this->db->prepare(
                        "SELECT dni, nombre, apellidos, direccion, fecha_baja,
                        telefono, email, fecha_nac, preferencias FROM clientes WHERE fecha_baja IS NOT NULL"
                    );
                }

                $query->execute();

                if ($dni)
                    return $query->fetch();
                else
                    return $query->fetchAll();
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }
        }

        function listarNumeroClientesAlta() {
            try {
                $this->__construct();
                $query = $this->db->prepare("SELECT COUNT(id_cliente) FROM clientes WHERE fecha_baja IS NULL");
				$query->execute();
                return $query->fetch()[0];
            } catch (Exception $e) {
                exit("Error: " . $e->getMessage());
            } finally {
                $this->db = null;
            }
        }

        function listarNumeroClientesBaja() {
            try {
                $this->__construct();
                $query = $this->db->prepare("SELECT COUNT(id_cliente) FROM clientes WHERE fecha_baja IS NOT NULL");
				$query->execute();
                return $query->fetch()[0];
            } catch (Exception $e) {
                exit("Error: " . $e->getMessage());
            } finally {
                $this->db = null;
            }
        }

        function clienteDeBaja(string $dni) {
            try {
                $this->__construct();
                $query = $this->db->prepare(
                    "SELECT fecha_baja FROM clientes WHERE dni = '" . $dni . "'"
                );
                $query->execute();

                if (!$query->fetch())
                    return;
                else if ($query->fetch() == null)
                    return;
                else if ($query->fetch()['fecha_baja'] == null)
                    return false;
                else
                    return true;
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }
        }

        function existeDNI(string $dni) {
            try {
                $this->__construct();
                $query = $this->db->prepare(
                    "SELECT COUNT(dni) FROM clientes WHERE dni = '" . $dni . "'"
                );
                $query->execute();
                return $query->fetch()[0];
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }
        }

        function altaCliente() {
            if (isset($_POST['registrar'])) {

                if ($this->comprobarFechaFutura($_POST['fecha_nac']))
                    return $this->mensaje("Fecha de nacimiento errónea.", "secondary");
                else if ($this->GET_Edad($_POST['fecha_nac']) < 18)
                    return $this->mensaje("No se pueden registrar menores de edad.", "secondary");
                

                $dni = strtoupper($_POST['dni']);

                if (!$this->comprobarLogin())
                    $clave = $_POST['clave'];
                else if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador" ||
                        $this->permisoUsuario($_COOKIE['dni']) == "Vendedor")
                    $clave = $dni;
            
                $nombre = $_POST['nombre'];
                $apellidos = $_POST['apellidos'];
                $direccion = $_POST['direccion'];
                $telefono = $_POST['telefono'];
                $email = $_POST['email'];
                $fecha_nac = $_POST['fecha_nac'];
                $preferencias = $_POST['preferencias'];

                if (!$this->existeDNI($dni)) {

                    try {
                        $this->__construct();
                        $query = $this->db->prepare(
                            "INSERT INTO clientes (dni, clave, nombre, apellidos,
                            direccion, telefono, email, fecha_nac, preferencias)
                            VALUES ('".$dni."', '".$clave."', '".$nombre."',
                            '".$apellidos."', '".$direccion."', '".$telefono."',
                            '".$email."', '".$fecha_nac."', '".$preferencias."')"
                        );
                        $query->execute();

                        if ($this->comprobarLogin() && ($this->permisoUsuario($_COOKIE['dni']) == "Administrador" ||
                            $this->permisoUsuario($_COOKIE['dni']) == "Vendedor")) {
                                $mensaje = "
                                    ¡Gracias por registrarte!<br>
                                    Para acceder a tu panel de control por primera vez, tendrás que introducir nombre, DNI y contraseña.<br> 
                                    Tu contraseña inicial será tu DNI, a continuación, deberás cambiarla.
                                ";
                                if ($this->mandarEmail("Floornaturate", "lola@lolaylaura.com", $email, "Registro en Lola&Laura", $mensaje)) {
                                    $this->mensaje("Cliente registrado correctamente.
                                    <br>Su contraseña es el DNI.<br>Recibirá un email con las instrucciones.", "success");
                                }
                        }
                        else
                            $this->mensaje("Registro completado.<br>Ya puedes iniciar sesión.", "success");
                    } catch (Exception $e) {
                        switch ($e->getCode()) {
                            case 23000:
                                $serror= $e->getMessage();
                                $eerror= explode(" ", $serror);
        
                                if (in_array("1062",$eerror))
                                    $this->mensaje("El <b>email</b> ya existe.", "secondary");
                                else
                                    $this->mensaje("Error desconocido.", "secondary");
                        default:
                            return $e->getMessage();
                        }
                    } finally {
                        $this->db = null;
                    }
                }

                else
                    $this->mensaje("El cliente ya está registrado.", "secondary");
            }
        }

        function modificarCliente() {
            if (isset($_POST['guardarCliente'])) {
                    $dni            = strtoupper($_POST['dni']);
                    $nombre         = $_POST['nombre'];
                    $apellidos      = $_POST['apellidos'];
                    $direccion      = $_POST['direccion'];
                    $telefono       = $_POST['telefono'];
                    $email          = $_POST['email'];
                    $fecha_nac      = $_POST['fecha_nac'];
                    $preferencias   = $_POST['preferencias'];
                    $id_cliente     = $_POST['id_cliente'];

                    // Distinguir entre si es el cliente modificando sus datos en su panel de control
                    // o es un admin/vendedor desde su zona privada
                    if (isset($_POST['panel']))
                        $clave = $_POST['clave'];
                    else
                        $clave = "";

                    try {
                        $this->__construct();

                        if (empty($clave)) {
                            $query = $this->db->prepare(
                                "UPDATE clientes SET
                                dni             = '".$dni."',
                                nombre          = '".$nombre."',
                                apellidos       = '".$apellidos."',
                                direccion       = '".$direccion."',
                                telefono        = '".$telefono."',
                                email           = '".$email."',
                                fecha_nac       = '".$fecha_nac."',
                                preferencias    = '".$preferencias."'
                                WHERE id_cliente = '".$id_cliente."'"
                            );
                            $query->execute();
                        }
                        else {
                            $query = $this->db->prepare(
                                "UPDATE clientes SET
                                nombre          = '".$nombre."',
                                apellidos       = '".$apellidos."',
                                direccion       = '".$direccion."',
                                telefono        = '".$telefono."',
                                email           = '".$email."',
                                fecha_nac       = '".$fecha_nac."',
                                preferencias    = '".$preferencias."',
                                clave           = '".$clave."'
                                WHERE dni = '".$dni."'"
                            );
                            $query->execute();
                        }
                        $this->mensaje("Datos modificados correctamente.<br>
                        Accede de nuevo para ver los cambios.", "success");
                    } catch (Exception $e) {
                        switch ($e->getCode()) {
                            case 23000:
                                $serror= $e->getMessage();
                                $eerror= explode(" ", $serror);
        
                                if (in_array("1062",$eerror))
                                    $this->mensaje("Ya existe un cliente registrado con ese DNI.", "secondary");
                                else
                                    $this->mensaje("Error desconocido.", "secondary");
                        default:
                            return $e->getMessage();
                        }
                    } finally {
                        $this->db = null;
                    }
            }
        }

        function bajaLogicaCliente() {
            if (isset($_POST['baja'])) {
                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                    try {
                        $this->__construct();
                        $query = $this->db->prepare(
                            "UPDATE clientes
                            SET fecha_baja = '".date("Y-m-d H:i:s")."'
                            WHERE dni = '" . $_POST['dni'] . "'"
                        );
                        $query->execute();
                        $this->mensaje("Baja efectuada con éxito.
                        <br>Accede de nuevo para ver los cambios.", "success");
                    } catch (Exception $e) {
                        exit("Error: " . $e->GetMessage());
                    } finally {
                        $this->db = null;
                    }
                }
                else
                    $this->mensaje("Permiso denegado.", "secondary");
            }
        }

        function restaurarCliente() {
            if (isset($_POST['restaurar'])) {
                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador" ||
                    $this->permisoUsuario($_COOKIE['dni']) == "Vendedor") {
                    try {
                        $this->__construct();
                        $query = $this->db->prepare(
                            "UPDATE clientes
                            SET fecha_baja = NULL
                            WHERE dni = '" . $_POST['dni'] . "'"
                        );
                        $query->execute();
                        $this->mensaje("Cliente restaurado con éxito.
                        <br>Accede de nuevo para ver los cambios.", "success");
                    } catch (Exception $e) {
                        exit("Error: " . $e->GetMessage());
                    } finally {
                        $this->db = null;
                    }
                }
                else
                    $this->mensaje("Permiso denegado.", "secondary");
            }
        }

        /*
        // Esta función ELIMINA el cliente de la BD
        function bajaCliente() {
            if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {

                try {
                    $this->__construct();
                    $query = $this->db->prepare(
                        "DELETE FROM clientes WHERE dni = '" . $_GET['bajaCliente'] . "'"
                    );
                    $query->execute();
                    header("location: administrador.php");
                } catch (Exception $e) {
                    exit("Error: " . $e->GetMessage());
                } finally {
                    $this->db = null;
                }
            }
            else
                $this->mensaje("Permiso denegado.", "secondary");
        }
        */


        // Usuarios

        function listarUsuarios(bool $baja) {
            try {
                $this->__construct();

                if (!$baja) {
                    $query = $this->db->prepare(
                        "SELECT id_usuario, dni, iniciales, email, permiso, fecha_alta FROM usuarios WHERE fecha_baja IS NULL"
                    );
                }
                else {
                    $query = $this->db->prepare(
                        "SELECT dni, iniciales, email, permiso, fecha_alta FROM usuarios WHERE fecha_baja IS NOT NULL"
                    );
                }

                $query->execute();
                return $query->fetchAll();
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }
        }

        function listarUsuarioAlta(string $dni) {
            try {
                $this->__construct();

                $query = $this->db->prepare(
                    "SELECT id_usuario, dni, iniciales, email, permiso, clave FROM usuarios
                    WHERE dni = '".$dni."' AND fecha_baja IS NULL"
                );

                $query->execute();
                return $query->fetch();
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }
        }

        function listarNumeroUsuarios(bool $baja) {
            try {
                $this->__construct();

                if (!$baja)
                    $query = $this->db->prepare("SELECT COUNT(id_usuario) FROM usuarios WHERE fecha_baja IS NULL");
                else
                    $query = $this->db->prepare("SELECT COUNT(id_usuario) FROM usuarios WHERE fecha_baja IS NOT NULL");

				$query->execute();
                return $query->fetch()[0];
            } catch (Exception $e) {
                exit("Error: " . $e->getMessage());
            } finally {
                $this->db = null;
            }
        }

        function usuarioDeBaja(string $dni) {
            try {
                $this->__construct();
                $query = $this->db->prepare(
                    "SELECT fecha_baja FROM usuarios WHERE dni = '" . $dni . "'"
                );
                $query->execute();

                if ($query->fetch()[0] == null)
                    return false;
                else
                    return true;
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }
        }

        function existeDNIusuario(string $dni) {
            try {
                $this->__construct();
                $query = $this->db->prepare(
                    "SELECT COUNT(dni) FROM usuarios WHERE dni = '" . $dni . "'"
                );
                $query->execute();
                return $query->fetch()[0];
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }
        }

        function altaUsuario() {
            if (isset($_POST['altaUsuario'])) {
                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {

                    $dni        = strtoupper($_POST['dni']);
                    $iniciales  = $_POST['iniciales'];
                    $email      = $_POST['email'];
                    $clave      = $_POST['clave'];
                    $permiso    = $_POST['permiso'];

                    if (!$this->existeDNIusuario($dni)) {
                        try {
                            $this->__construct();
                            $query = $this->db->prepare(
                                "INSERT INTO usuarios (dni, iniciales, email, clave, permiso)
                                VALUES ('".$dni."', '".$iniciales."', '".$email."',
                                '".$clave."', '".$permiso."')"
                            );
                            $query->execute();
                            $this->mensaje("Usuario registrado correctamente.
                            <br>Accede de nuevo para ver los cambios.", "success");
                        } catch (Exception $e) {
                            switch ($e->getCode()) {
                                case 23000:
                                    $serror= $e->getMessage();
                                    $eerror= explode(" ", $serror);
            
                                    if (in_array("1062",$eerror))
                                        $this->mensaje("Ya existe un usuario registrado con ese email.", "secondary");
                                    else
                                        $this->mensaje("Error desconocido.", "secondary");
                            default:
                                return $e->getMessage();
                            }
                        } finally {
                            $this->db = null;
                        }
                    }

                    else
                        $this->mensaje("El usuario ya está registrado.", "secondary");
                }
            }
        }

        function modificarUsuario() {
            if (isset($_POST['guardarUsuario'])) {
                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador" ||
                    $this->permisoUsuario($_COOKIE['dni']) == "Vendedor") {
                    $dni        = strtoupper($_POST['dni']);
                    $iniciales  = $_POST['iniciales'];
                    $email      = $_POST['email'];
                    $id_usuario = $_POST['id_usuario'];

                    if (isset($_POST['panel'])) {
                        $permiso    = "";
                        $clave      = $_POST['clave'];
                    }
                    else {
                        $permiso    = $_POST['permiso'];
                        $clave      = "";
                    }

                    try {
                        $this->__construct();
                        if (empty($clave)) {
                            $query = $this->db->prepare(
                                "UPDATE usuarios SET
                                dni         = '".$dni."',
                                iniciales   = '".$iniciales."',
                                email       = '".$email."',
                                permiso     = '".$permiso."'
                                WHERE id_usuario = '".$id_usuario."'"
                            );
                        }
                        else {
                            $query = $this->db->prepare(
                                "UPDATE usuarios SET
                                dni         = '".$dni."',
                                iniciales   = '".$iniciales."',
                                email       = '".$email."',
                                clave       = '".$clave."'
                                WHERE id_usuario = '".$id_usuario."'"
                            );
                        }
                        $query->execute();
                        $this->mensaje("Datos modificados correctamente.
                        <br>Accede de nuevo para ver los cambios.", "success");
                    } catch (Exception $e) {
                        switch ($e->getCode()) {
                            case 23000:
                                $serror= $e->getMessage();
                                $eerror= explode(" ", $serror);
        
                                if (in_array("1062",$eerror))
                                    $this->mensaje("Ya existe un usuario registrado con ese DNI.", "secondary");
                                else
                                    $this->mensaje("Error desconocido.", "secondary");
                        default:
                            return $e->getMessage();
                        }
                    } finally {
                        $this->db = null;
                    }
                }
            }
        }

        function bajaLogicaUsuario() {
            if (isset($_POST['bajaUsuario'])) {
                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                    try {
                        $this->__construct();
                        $query = $this->db->prepare(
                            "UPDATE usuarios
                            SET fecha_baja = '".date("Y-m-d H:i:s")."'
                            WHERE dni = '" . $_POST['dni'] . "'"
                        );
                        $query->execute();
                        $this->mensaje("Baja efectuada con éxito.
                        <br>Accede de nuevo para ver los cambios.", "success");
                    } catch (Exception $e) {
                        exit("Error: " . $e->GetMessage());
                    } finally {
                        $this->db = null;
                    }
                }
                else
                    $this->mensaje("Permiso denegado.", "secondary");
            }
        }

        function restaurarUsuario() {
            if (isset($_POST['restaurarUsuario'])) {
                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                    try {
                        $this->__construct();
                        $query = $this->db->prepare(
                            "UPDATE usuarios
                            SET fecha_baja = NULL
                            WHERE dni = '" . $_POST['dni'] . "'"
                        );
                        $query->execute();
                        $this->mensaje("Usuario restaurado con éxito.<br>
                        Accede de nuevo para ver los cambios.", "success");
                    } catch (Exception $e) {
                        exit("Error: " . $e->GetMessage());
                    } finally {
                        $this->db = null;
                    }
                }
                else
                    $this->mensaje("Permiso denegado.", "secondary");
            }
        }

        function nombreLogeado() {
            try {
                $this->__construct();

                $query = $this->db->prepare("SELECT nombre FROM clientes WHERE dni = '".$_COOKIE['dni']."'");
                $query->execute();
                
                if ($query->rowCount() == 1)
                    return $query->fetch()['nombre'];

                else {
                    $query = $this->db->prepare("SELECT iniciales FROM usuarios WHERE dni = '".$_COOKIE['dni']."'");
                    $query->execute();
                    return $query->fetch()['iniciales'];
                }
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }
        }


        // Proveedores

        function listarNombresProveedores() {
            try {
                $this->__construct();
                $query = $this->db->prepare("SELECT nombre FROM proveedores WHERE fecha_baja IS null");
                $query->execute();
                return $query->fetchAll();
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }
        }

        function id_proveedor(string $nombre) {
            try {
                $this->__construct();
                $query = $this->db->prepare("SELECT id_proveedor FROM proveedores WHERE nombre = '".$nombre."'");
                $query->execute();
                return $query->fetch()[0];
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }
        }

        function listarProveedoresAlta($cif = false) {
            try {
                $this->__construct();

                if (!$cif) {
                    $query = $this->db->prepare("SELECT id_proveedor, cif, nombre, direccion, telefono, email, fecha_alta
                    FROM proveedores WHERE fecha_baja IS NULL");
                }
                else {
                    $query = $this->db->prepare("SELECT id_proveedor, cif, nombre, direccion, telefono, email, fecha_alta
                    FROM proveedores WHERE cif = '" . $cif . "' AND fecha_baja IS NULL");
                }

                $query->execute();

                if (!$cif)
                    return $query->fetchAll();
                else
                    return $query->fetch();
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }
        }

        function listarProveedoresBaja($cif = false) {
            try {
                $this->__construct();

                if (!$cif) {
                    $query = $this->db->prepare("SELECT cif, nombre, direccion, telefono, email, fecha_baja
                    FROM proveedores WHERE fecha_baja IS NOT NULL");
                }
                else {
                    $query = $this->db->prepare("SELECT cif, nombre, direccion, telefono, email, fecha_baja
                    FROM proveedores WHERE cif = '" . $cif . "' AND fecha_baja IS NOT NULL");
                }

                $query->execute();

                if (!$cif)
                    return $query->fetchAll();
                else
                    return $query->fetch();
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }
        }

        function listarNumeroProveedoresAlta() {
            try {
                $this->__construct();
                $query = $this->db->prepare("SELECT COUNT(id_proveedor) FROM proveedores WHERE fecha_baja IS NULL");
				$query->execute();
                return $query->fetch()[0];
            } catch (Exception $e) {
                exit("Error: " . $e->getMessage());
            } finally {
                $this->db = null;
            }
        }
        
        function listarNumeroProveedoresBaja() {
            try {
                $this->__construct();
                $query = $this->db->prepare("SELECT COUNT(id_proveedor) FROM proveedores WHERE fecha_baja IS NOT NULL");
				$query->execute();
                return $query->fetch()[0];
            } catch (Exception $e) {
                exit("Error: " . $e->getMessage());
            } finally {
                $this->db = null;
            }
        }

        function existeCIF(string $cif) {
            try {
                $this->__construct();
                $query = $this->db->prepare(
                    "SELECT COUNT(cif) FROM proveedores WHERE cif = '" . $cif . "'"
                );
                $query->execute();
                return $query->fetch()[0];
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }

        }

        function proveedorDeBaja(string $cif) {
            try {
                $this->__construct();
                $query = $this->db->prepare(
                    "SELECT COUNT(fecha_baja) FROM proveedores WHERE cif = '" . $cif . "'"
                );
                $query->execute();
                return $query->fetch()['COUNT(fecha_baja)'];
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }
        }

        function altaProveedor() {
            if (isset($_POST['alta'])) {

                $cif        = strtoupper($_POST['cif']);
                $nombre     = $_POST['nombre'];
                $direccion  = $_POST['direccion'];
                $telefono   = $_POST['telefono'];
                $email      = $_POST['email'];

                if (!$this->existeCIF($cif)) {
                    try {
                        $this->__construct();
                        $query = $this->db->prepare(
                            "INSERT INTO proveedores (cif, nombre, direccion, telefono, email)
                            VALUES ('".$cif."', '".$nombre."', '".$direccion."', '".$telefono."', '".$email."')"
                        );
                        $query->execute();
                        $this->mensaje("Proveedor registrado correctamente.
                        <br>Accede de nuevo para ver los cambios.", "success");
                    } catch (Exception $e) {
                        exit("Error: " . $e->GetMessage());
                    } finally {
                        $this->db = null;
                    }
                }

                else
                    $this->mensaje("El proveedor ya está registrado.", "secondary");
            }
        }

        function modificarProveedor() {
            $nombre     = $_POST['nombre'];
            $direccion  = $_POST['direccion'];
            $telefono   = $_POST['telefono'];
            $email      = $_POST['email'];
            $cif        = strtoupper($_POST['cif']);
            $id_proveedor = $_POST['id_proveedor'];

            try {
                $this->__construct();
                $query = $this->db->prepare(
                    "UPDATE proveedores SET
                    cif             = '".$cif."',
                    nombre          = '".$nombre."',
                    direccion       = '".$direccion."',
                    telefono        = '".$telefono."',
                    email           = '".$email."'
                    WHERE id_proveedor = '".$id_proveedor."'"
                );
                $query->execute();
                $this->mensaje("Proveedor modificado correctamente.
                <br>Accede de nuevo para ver los cambios.", "success");
            } catch (Exception $e) {
                exit("Error: " . $e->getMessage());
            } finally {
                $this->db = null;
            }
        }

        function bajaLogicaProveedor() {
            if (isset($_POST['baja'])) {
                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                    try {
                        $this->__construct();
                        $query = $this->db->prepare(
                            "UPDATE proveedores
                            SET fecha_baja = '".date("Y-m-d H:i:s")."'
                            WHERE cif = '" . $_POST['cif'] . "'"
                        );
                        $query->execute();
                        $this->mensaje("Baja efectuada con éxito.
                        <br>Accede de nuevo para ver los cambios.", "success");
                    } catch (Exception $e) {
                        exit("Error: " . $e->GetMessage());
                    } finally {
                        $this->db = null;
                    }
                }
                else
                    $this->mensaje("Permiso denegado.", "secondary");
            }
        }

        function restaurarProveedor() {
            if (isset($_POST['restaurar'])) {
                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                    try {
                        $this->__construct();
                        $query = $this->db->prepare(
                            "UPDATE proveedores
                            SET fecha_baja = NULL
                            WHERE cif = '" . $_POST['cif'] . "'"
                        );
                        $query->execute();
                        $this->mensaje("Proveedor restaurado con éxito.
                        <br>Accede de nuevo para ver los cambios.", "success");
                    } catch (Exception $e) {
                        exit("Error: " . $e->GetMessage());
                    } finally {
                        $this->db = null;
                    }
                }
                else
                    $this->mensaje("Permiso denegado.", "secondary");
                }
        }

        /*
        function bajaProveedor() {
            if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {

            }
            else
                $this->mensaje("Permiso denegado.", "secondary");
        }
        */


        // Productos

        function listarDatosProducto(int $id_producto) {
            if (!$id_producto)
                return $this->mensaje("ID de producto de desconocido.", "secondary");

            try {
                $this->__construct();
                $query = $this->db->prepare(
                    "SELECT id_producto, nombre, descripcion, coste, pvp, categoria
                    FROM productos WHERE id_producto = '" . $id_producto . "'"
                );
                $query->execute();
                return $query->fetch();
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }
        }

        function listarStockAlta($categoria = false) {
                try {
                    $this->__construct();

                    if (!$categoria) {
                        $query = $this->db->prepare(
                            "SELECT id_producto, nombre, descripcion, coste, pvp, categoria, fecha_alta FROM productos WHERE fecha_baja IS null"
                        );
                    }
                    else {
                        $query = $this->db->prepare(
                            "SELECT id_producto, nombre, descripcion, coste, pvp, categoria, fecha_alta FROM productos WHERE fecha_baja IS null
                            AND categoria = '" . $categoria . "'"
                        );
                    }
                    $query->execute();
                    return $query->fetchAll();
                } catch (Exception $e) {
                    exit("Error: " . $e->GetMessage());
                } finally {
                    $this->db = null;
                }
        }

        function listarStockBaja($categoria = false) {
            if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                try {
                    $this->__construct();

                    if (!$categoria) {
                        $query = $this->db->prepare(
                            "SELECT id_producto, nombre, descripcion, coste, pvp, categoria, fecha_baja FROM productos WHERE fecha_baja IS NOT null"
                        );
                    }
                    else {
                        $query = $this->db->prepare(
                            "SELECT id_producto, nombre, descripcion, coste, pvp, categoria, fecha_baja FROM productos WHERE fecha_baja IS NOT null
                            AND categoria = '" . $categoria . "'"
                        );
                    }
                    $query->execute();
                    return $query->fetchAll();
                } catch (Exception $e) {
                    exit("Error: " . $e->GetMessage());
                } finally {
                    $this->db = null;
                }
            }
        }

        function listarNumeroProductosAlta($categoria = false) {
            try {
                $this->__construct();

                if (!$categoria)
                    $query = $this->db->prepare("SELECT COUNT(id_producto) FROM productos WHERE fecha_baja IS NULL");
                else {
                    $query = $this->db->prepare("SELECT COUNT(id_producto) FROM productos
                    WHERE categoria = '" . $categoria . "' AND fecha_baja IS NULL");
                }

				$query->execute();
                return $query->fetch()[0];
            } catch (Exception $e) {
                exit("Error: " . $e->getMessage());
            } finally {
                $this->db = null;
            }
        }

        function listarNumeroProductosBaja($categoria = false) {
            try {
                $this->__construct();

                if (!$categoria)
                    $query = $this->db->prepare("SELECT COUNT(id_producto) FROM productos WHERE fecha_baja IS NOT NULL");
                else {
                    $query = $this->db->prepare("SELECT COUNT(id_producto) FROM productos
                    WHERE categoria = '" . $categoria . "' AND fecha_baja IS NOT NULL");
                }

				$query->execute();
                return $query->fetch()[0];
            } catch (Exception $e) {
                exit("Error: " . $e->getMessage());
            } finally {
                $this->db = null;
            }
        }

        function productoDeBaja(int $id_producto) {
            try {
                $this->__construct();
                $query = $this->db->prepare(
                    "SELECT fecha_baja FROM productos WHERE id_producto = '" . $id_producto . "'"
                );
                $query->execute();

                if ($query->fetch()[0] == null)
                    return false;
                else
                    return true;
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }
        }

        function existeProducto(string $nombre) {
            try {
                $this->__construct();
                $query = $this->db->prepare(
                    "SELECT COUNT(nombre) FROM productos WHERE nombre = '" . $nombre . "'"
                );
                $query->execute();
                return $query->fetch()[0];
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }

        }

        function altaProducto() {
            if (isset($_POST['altaProducto'])) {
                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                    $nombre         = $_POST['nombre'];
                    if (!$this->existeProducto($nombre)){

                        $descripcion    = $_POST['descripcion'];
                        $coste          = $_POST['coste'];
                        $pvp            = $_POST['pvp'];
                        $categoria      = $_POST['categoria'];
                        $id_proveedor   = $this->id_proveedor($_POST['proveedor']);
                        $id_categoria   = $this->id_categoria($_POST['categoria']);

                        try {
                            $this->__construct();
                            $query = $this->db->prepare(
                                "INSERT INTO productos (nombre, descripcion, coste, pvp, categoria, id_proveedor, id_categoria)
                                VALUES 
                                ('". $nombre . "', '". $descripcion . "', '". $coste . "',
                                '". $pvp . "', '". $categoria . "', '".$id_proveedor."', '".$id_categoria."');"
                            );
                            $query->execute();
                            $this->mensaje("Producto añadido correctamente.
                            <br>Accede de nuevo para ver los cambios.", "success");
                        } catch (Exception $e) {
                            switch ($e->getCode()) {
                                case 23000:
                                    $serror= $e->getMessage();
                                    $eerror= explode(" ", $serror);
            
                                    if (in_array("1062",$eerror))
                                        $this->mensaje("Ya existe un producto registrado con ese nombre.", "secondary");
                                    else
                                        $this->mensaje("Error desconocido.", "secondary");
                            default:
                                return $e->getMessage();
                            }
                        } finally {
                            $this->db = null;
                        }
                    }
                    else
                        $this->mensaje("Este producto ya está dado de alta.", "secondary");
                }
                else
                    $this->mensaje("Permiso denegado.", "secondary");
            }
        }

        function modificarProducto() {
            if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                try {
                    $this->__construct();
                    $query = $this->db->prepare(
                        "UPDATE productos
                        SET nombre  = '". $_POST['nombre'] . "',
                        descripcion = '". $_POST['descripcion'] . "',
                        coste       = '". $_POST['coste'] . "',
                        pvp         = '". $_POST['pvp'] . "',
                        categoria   = '". $_POST['categoria'] . "'
                        WHERE id_producto = '" . $_POST['id_producto'] . "'"
                    );
                    $query->execute();
                    $this->mensaje("Producto modificado correctamente.
                    <br>Accede de nuevo para ver los cambios.", "success");
                } catch (Exception $e) {
                    switch ($e->getCode()) {
                        case 23000:
                            $serror= $e->getMessage();
                            $eerror= explode(" ", $serror);
    
                            if (in_array("1062",$eerror))
                                $this->mensaje("Ya existe un producto registrado con ese nombre.", "secondary");
                            else
                                $this->mensaje("Error desconocido.", "secondary");
                    default:
                        return $e->getMessage();
                    }
                } finally {
                    $this->db = null;
                }
            }
            else
                $this->mensaje("Permiso denegado.", "secondary");
        }

        function bajaLogicaProducto() {
            if (isset($_POST['baja'])) {
                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                    try {
                        $this->__construct();
                        $query = $this->db->prepare(
                            "UPDATE productos
                            SET fecha_baja = '".date("Y-m-d H:i:s")."'
                            WHERE id_producto = '" . $_POST['id_producto'] . "'"
                        );
                        $query->execute();
                        $this->mensaje("Baja efectuada con éxito.
                        <br>Accede de nuevo para ver los cambios.", "success");
                    } catch (Exception $e) {
                        exit("Error: " . $e->GetMessage());
                    } finally {
                        $this->db = null;
                    }
                }
                else
                    $this->mensaje("Permiso denegado.", "secondary");
            }
        }

        function restaurarProducto() {
            if (isset($_POST['restaurar'])) {
                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                    try {
                        $this->__construct();
                        $query = $this->db->prepare(
                            "UPDATE productos
                            SET fecha_baja = NULL
                            WHERE id_producto = '" . $_POST['id_producto'] . "'"
                        );
                        $query->execute();
                        $this->mensaje("Producto restaurado con éxito.
                        <br>Accede de nuevo para ver los cambios.", "success");
                    } catch (Exception $e) {
                        exit("Error: " . $e->GetMessage());
                    } finally {
                        $this->db = null;
                    }
                }
                else
                    $this->mensaje("Permiso denegado.", "secondary");
            }
        }


        /*
        function bajaProducto(int $id_producto) {
            if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                try {
                    $this->__construct();
                    $query = $this->db->prepare(
                        "DELETE FROM productos WHERE id_producto = '" . $id_producto . "'"
                    );
                    $query->execute();
                } catch (Exception $e) {
                    exit("Error: " . $e->GetMessage());
                } finally {
                    $this->db = null;
                }
            }
            else
                $this->mensaje("Permiso denegado.", "secondary");
        }
        */


        // Categorías

        function listarNumeroCategorias(bool $baja) {
            try {
                $this->__construct();

                if (!$baja) {
                    $query = $this->db->prepare("SELECT COUNT(categoria)
                    FROM categorias WHERE fecha_baja IS NULL");
                }
                else {
                    $query = $this->db->prepare("SELECT COUNT(categoria)
                    FROM categorias WHERE fecha_baja IS NOT NULL");
                }

				$query->execute();
                return $query->fetch()[0];
            } catch (Exception $e) {
                exit("Error: " . $e->getMessage());
            } finally {
                $this->db = null;
            }
        }

        function listarCategorias(bool $baja) {
            try {
                $this->__construct();

                if (!$baja) {
                    $query = $this->db->prepare(
                        "SELECT id_categoria, categoria, fecha_alta
                        FROM categorias WHERE fecha_baja IS NULL"
                    );
                }
                else {
                    $query = $this->db->prepare(
                        "SELECT id_categoria, categoria, fecha_baja
                        FROM categorias WHERE fecha_baja IS NOT NULL"
                    );
                }

				$query->execute();
                return $query->fetchAll();
            } catch (Exception $e) {
                exit("Error: " . $e->getMessage());
            } finally {
                $this->db = null;
            }
        }

        function id_categoria(string $categoria) {
            try {
                $this->__construct();
                $query = $this->db->prepare("SELECT id_categoria FROM categorias WHERE categoria = '".$categoria."'");
                $query->execute();
                return $query->fetch()[0];
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }
        }

        function nombre_categoria(int $id_categoria) {
            try {
                $this->__construct();
                $query = $this->db->prepare("SELECT categoria FROM categorias WHERE id_categoria = '".$id_categoria."'");
                $query->execute();
                return $query->fetch()[0];
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }
        }

        function existeCategoria(string $categoria) {
            try {
                $this->__construct();
                $query = $this->db->prepare(
                    "SELECT COUNT(categoria) FROM categorias WHERE categoria = '" . $categoria . "'"
                );
                $query->execute();
                return $query->fetch()[0];
            } catch (Exception $e) {
                exit("Error: " . $e->GetMessage());
            } finally {
                $this->db = null;
            }
        }

        function altaCategoria() {
        
            if (isset($_POST['altaCategoria'])) {
                $categoria = $_POST['categoria'];

                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                    if ($this->existeCategoria($categoria) == 0) {
                        try {
                            $this->__construct();
                            $query = $this->db->prepare(
                                "INSERT INTO categorias (categoria)
                                VALUES ('". $_POST['categoria'] . "');"
                            );
                            $query->execute();
                            $this->mensaje("Categoría añadida correctamente.
                            <br>Accede de nuevo para ver los cambios.", "success");
                        } catch (Exception $e) {
                            switch ($e->getCode()) {
                                case 23000:
                                    $serror= $e->getMessage();
                                    $eerror= explode(" ", $serror);
            
                                    if (in_array("1062",$eerror))
                                        $this->mensaje("Ya existe una categoría registrada con ese nombre.", "secondary");
                                    else
                                        $this->mensaje("Error desconocido.", "secondary");
                            default:
                                return $e->getMessage();
                            }
                        } finally {
                            $this->db = null;
                        }
                    }

                    else 
                        $this->mensaje("La categoría ya está creada.", "secondary");

                }
                else
                    $this->mensaje("Permiso denegado.", "secondary");
            }
        }

        function modificarCategoria() {
            if (isset($_POST['guardarCategoria'])) {
                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                    try {
                        $this->__construct();
                        $query = $this->db->prepare(
                            "UPDATE categorias
                            SET categoria   = '". $_POST['categoria'] . "'
                            WHERE id_categoria = '" . $_POST['id_categoria'] . "'"
                        );
                        $query->execute();
                        $this->mensaje("Categoría modificada correctamente.
                        <br>Accede de nuevo para ver los cambios.", "success");
                    } catch (Exception $e) {
                        switch ($e->getCode()) {
                            case 23000:
                                $serror= $e->getMessage();
                                $eerror= explode(" ", $serror);
        
                                if (in_array("1062",$eerror))
                                    $this->mensaje("Ya existe una categoría registrada con ese nombre.", "secondary");
                                else
                                    $this->mensaje("Error desconocido.", "secondary");
                        default:
                            return $e->getMessage();
                        }
                    } finally {
                        $this->db = null;
                    }
                }
                else
                    $this->mensaje("Permiso denegado.", "secondary");
            }
        }

        function bajaLogicaCategoria() {
            if (isset($_POST['bajaCategoria'])) {
                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                    try {
                        $this->__construct();
                        $query = $this->db->prepare(
                            "UPDATE categorias
                            SET fecha_baja = '".date("Y-m-d H:i:s")."'
                            WHERE id_categoria = '" . $_POST['id_categoria'] . "'"
                        );
                        $query->execute();
                        $this->mensaje("Baja efectuada con éxito.
                        <br>Accede de nuevo para ver los cambios.", "success");
                    } catch (Exception $e) {
                        exit("Error: " . $e->GetMessage());
                    } finally {
                        $this->db = null;
                    }
                }
                else
                    $this->mensaje("Permiso denegado.", "secondary");
            }
        }

        function restaurarCategoria() {
            if (isset($_POST['restaurarCategoria'])) {
                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                    try {
                        $this->__construct();
                        $query = $this->db->prepare(
                            "UPDATE categorias
                            SET fecha_baja = NULL
                            WHERE id_categoria = '" . $_POST['id_categoria'] . "'"
                        );
                        $query->execute();
                        $this->mensaje("Categoría restaurada con éxito.
                        <br>Accede de nuevo para ver los cambios.", "success");
                    } catch (Exception $e) {
                        exit("Error: " . $e->GetMessage());
                    } finally {
                        $this->db = null;
                    }
                }
                else
                    $this->mensaje("Permiso denegado.", "secondary");
            }
        }



        // Cesta

        function listarProductosCesta() {
            if ($this->comprobarCliente($_COOKIE['dni'])) {

                $id_cliente = $this->listarClientesAlta($_COOKIE['dni'])['id_cliente'];

                try {
                    $this->__construct();
                    $query = $this->db->prepare(
                        "SELECT c.id_producto, id_cliente, cantidad, categoria, nombre, pvp FROM cesta c
                        INNER JOIN productos p ON (c.id_producto = p.id_producto)
                        WHERE id_cliente = '".$id_cliente."'"
                    );
                    $query->execute();
                    return $query->fetchAll();
                } catch (Exception $e) {
                    exit("Error: " . $e->GetMessage());
                } finally {
                    $this->db = null;
                }
            }
        }

        function comprobarExistenteCesta(int $id_producto) {
            if ($this->comprobarCliente($_COOKIE['dni'])) {

                $id_cliente = $this->listarClientesAlta($_COOKIE['dni'])['id_cliente'];

                try {
                    $this->__construct();
                    $query = $this->db->prepare(
                        "SELECT id_producto, id_cliente FROM cesta
                        WHERE id_producto = '".$id_producto."' AND id_cliente = '".$id_cliente."'"
                    );
                    $query->execute();
                    return $query->rowCount();
                } catch (Exception $e) {
                    exit("Error: " . $e->GetMessage());
                } finally {
                    $this->db = null;
                }
            }
        }

        function contarProductosCesta() {
            if ($this->comprobarCliente($_COOKIE['dni'])) {

                $id_cliente = $this->listarClientesAlta($_COOKIE['dni'])['id_cliente'];

                try {
                    $this->__construct();
                    $query = $this->db->prepare(
                        "SELECT  COUNT(id_cliente) FROM cesta
                        WHERE id_cliente = '".$id_cliente."'"
                    );
                    $query->execute();
                    return $query->fetch()[0];
                } catch (Exception $e) {
                    exit("Error: " . $e->GetMessage());
                } finally {
                    $this->db = null;
                }
            }
        }

        function altaProductoCesta() {
            if (isset($_POST['altaCesta'])) {
                if ($this->comprobarCliente($_COOKIE['dni'])) {

                    $id_cliente     = $this->listarClientesAlta($_COOKIE['dni'])['id_cliente'];
                    $id_producto    = $_POST['id_producto'];
                    $cantidad       = $_POST['cantidad'];

                    try {
                        $this->__construct();
                        $query = $this->db->prepare(
                            "INSERT INTO cesta (id_cliente, id_producto, cantidad)
                            VALUES ('".$id_cliente."', '".$id_producto."', '".$cantidad."')"
                        );
                        $query->execute();
                        $this->mensaje("Producto añadido a la cesta.
                        <br>Actualiza tu cesta para ver los cambios.", "success");
                    } catch (Exception $e) {
                        switch ($e->getCode()) {
                            case 23000:
                                $serror= $e->getMessage();
                                $eerror= explode(" ", $serror);
        
                                if (in_array("1062",$eerror))
                                    $this->mensaje("Este producto ya está en tu cesta.", "secondary");
                                else
                                    $this->mensaje("Error desconocido.", "secondary");
                        default:
                            return $e->getMessage();
                        }

                    } finally {
                        $this->db = null;
                    }
                }
            }
        }

        function modificarProductoCesta() {
            if (isset($_POST['modificarProductoCesta'])) {
                if ($this->comprobarCliente($_COOKIE['dni'])) {

                    $id_cliente     = $this->listarClientesAlta($_COOKIE['dni'])['id_cliente'];
                    $id_producto    = $_POST['id_producto'];
                    $cantidad       = $_POST['cantidad'];

                    try {
                        $this->__construct();
                        $query = $this->db->prepare(
                            "UPDATE cesta
                            SET cantidad = '".$cantidad."'
                            WHERE id_cliente = '".$id_cliente."'
                            AND id_producto = '".$id_producto."'"
                        );
                        $query->execute();
                        $this->mensaje("Cantidad modificada correctamente.
                        <br>Actualiza tu cesta para ver los cambios.", "success");
                    } catch (Exception $e) {
                        exit("Error: " . $e->GetMessage());
                    } finally {
                        $this->db = null;
                    }
                }
            }
        }

        function bajaProductoCesta() {
            if (isset($_POST['bajaProductoCesta'])) {
                if ($this->comprobarCliente($_COOKIE['dni'])) {

                    $id_cliente     = $this->listarClientesAlta($_COOKIE['dni'])['id_cliente'];
                    $id_producto    = $_POST['id_producto'];

                    try {
                        $this->__construct();
                        $query = $this->db->prepare(
                            "DELETE FROM cesta
                            WHERE id_cliente = '".$id_cliente."'
                            AND id_producto = '".$id_producto."'"
                        );
                        $query->execute();
                        $this->mensaje("Producto eliminado de la cesta.
                        <br>Actualiza tu cesta para ver los cambios.", "success");
                    } catch (Exception $e) {
                        exit("Error: " . $e->GetMessage());
                    } finally {
                        $this->db = null;
                    }
                }
            }
        }

        function vaciarCesta() {
            if (isset($_POST['vaciarCesta'])) {
                if ($this->comprobarCliente($_COOKIE['dni'])) {

                    $id_cliente     = $this->listarClientesAlta($_COOKIE['dni'])['id_cliente'];

                    try {
                        $this->__construct();
                        $query = $this->db->prepare(
                            "DELETE FROM cesta
                            WHERE id_cliente = '".$id_cliente."'"
                        );
                        $query->execute();

                        if (stripos($_SERVER['REQUEST_URI'], "cesta.php") !== false) {
                            $this->mensaje("Cesta vaciada correctamente.
                            <br>Actualiza tu cesta para ver los cambios.", "success");
                        }
                    } catch (Exception $e) {
                        exit("Error: " . $e->GetMessage());
                    } finally {
                        $this->db = null;
                    }
                }
            }
        }
    }
?>