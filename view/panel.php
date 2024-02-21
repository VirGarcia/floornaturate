<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Panel de control / Floornaturate</title>
        <?php require("../controller/head.php"); ?>
    </head>

    <body class="bg-light">
        <?php
            require_once("header.php");
            require_once("menu.php");
        ?>

        <div class="container justify-content-center text-center mt-5">
            <?php
                require_once("../controller/classes/Administrador.php");
                $instancia = new Administrador();

                if (!$instancia->comprobarLogin())
                    header("location: index.php");

                else {
                    if ($instancia->comprobarCliente($_COOKIE['dni'])) {
                        $instancia->formCliente();
                        $instancia->modificarCliente();
                    }
                    else {
                        $instancia->formUsuario();
                        $instancia->modificarUsuario();
                    }
                }
            ?>
        </div>

        <?php require_once("footer.php"); ?>
    </body>
</html>
<?php ob_end_flush(); ?>