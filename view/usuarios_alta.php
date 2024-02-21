<?php ob_start(); ?>
<!doctype html>
<html lang="es">
    <head>
        <title>Usuarios de alta / Floornaturate</title>
        <?php require("../controller/head.php"); ?>
    </head>

    <body class="bg-light">
        <?php
            require_once("../controller/classes/Administrador.php");
            $instancia = new Administrador();
            $dni = $_COOKIE['dni'];
            
            if ($instancia->permisoUsuario($dni) != "Administrador")
                header("location: index.php");

            require_once("../view/header.php");
            require_once("../view/menu.php");
            $instancia = new Administrador();
        ?>

        <!-- Formulario principal -->
        <?php
            require_once("admin_botones.php");

            $instancia->pintaTablaUsuariosAlta();
            $instancia->formModificarUsuario();
            $instancia->modificarUsuario();
            $instancia->bajaLogicaUsuario();

            require_once("footer.php");
        ?>
        
        <script type="text/javascript" src="assets/js/selects.js"></script>
    </body>
</html>
<?php ob_end_flush(); ?>