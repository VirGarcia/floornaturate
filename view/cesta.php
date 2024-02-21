<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Floornaturate</title>
        <?php require("../controller/head.php"); ?>
    </head>

    <body class="bg-light">
        <?php
            require_once("../controller/classes/Seguridad.php");
            $instancia = new Seguridad();
            
            if (!$instancia->comprobarLogin() || !$instancia->comprobarCliente($_COOKIE['dni']))
                header("location: index.php");

            require_once("../view/header.php");
            require_once("../view/menu.php");

            require_once("../controller/classes/Cesta.php");
            $instancia = new Cesta();

            $instancia->pintaCesta();
            $instancia->modificarProductoCesta();
            $instancia->bajaProductoCesta();
            $instancia->vaciarCesta();
        ?>

        <?php require_once("footer.php"); ?>
    </body>
</html>
<?php ob_end_flush(); ?>