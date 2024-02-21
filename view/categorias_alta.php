<?php ob_start(); ?>
<!doctype html>
<html lang="es">
    <head>
        <title>Categorías de alta /  Floornaturate</title>
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

            require_once("admin_botones.php");

            $instancia->pintaTablaCategoriasAlta();
            $instancia->formModificarCategoria();
            $instancia->modificarCategoria();
            $instancia->bajaLogicaCategoria();
        ?>

        <?php require_once("footer.php"); ?>
    </body>
</html>
<?php ob_end_flush(); ?>