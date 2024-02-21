<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Stock / Floornaturate</title>
        <?php require("../controller/head.php"); ?>
    </head>

    <body class="bg-light">
        <?php
            require_once("../controller/classes/Administrador.php");
            $instancia = new Administrador();
            $dni = $_COOKIE['dni'];
            
            if (!$instancia->permisoUsuario($dni) == "Administrador")
                header("location: index.php");

            require_once("../view/header.php");
            require_once("../view/menu.php");

            $instancia = new Administrador();
            require_once("../controller/classes/Seguridad.php");

            if ($instancia->comprobarLogin()
                && !$instancia->comprobarCliente($_COOKIE['dni']))
            require_once("admin_botones.php");
        ?>

        <div class="container mt-3">
            <?php
                if (isset($_GET["altaProducto"]))
                    print($instancia->formAltaProducto());
                else if (isset($_GET["modificarProducto"]))
                    print($instancia->formModificarProducto());
            ?>
        </div>
        <?php require_once("footer.php"); ?>
    </body>
</html>
<?php ob_end_flush(); ?>