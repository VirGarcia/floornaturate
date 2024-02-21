<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Ficha de producto / Floornaturate</title>
        <?php require("../controller/head.php"); ?>
    </head>

    <body class="bg-light">
        <?php
            require_once("../view/header.php");
            require_once("../view/menu.php");

            require_once("../controller/classes/Stock.php");
            $instancia = new Stock();
        ?>

        <div class="container mt-4">
            <?php
                $instancia->pintaDatosProducto($_GET['id']);
                $instancia->altaProductoCesta();
            ?>
        </div>

        <?php require_once("footer.php"); ?>
    </body>
</html>
<?php ob_end_flush(); ?>