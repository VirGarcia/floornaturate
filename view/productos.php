<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Productos / Floornaturate</title>
        <?php require("../controller/head.php"); ?>
    </head>

    <body class="bg-light">
        <?php
            if (!isset($_GET['cat']))
                header("location: index.php");

            require_once("../view/header.php");
            require_once("../view/menu.php");

            $categoria = array("rojo", "negro", "verde", "cafe", "chocolate", "flores");
            $categoriaCompleta = array("Té rojo", "Té negro", "Té verde", "Café",
            "Chocolate", "Flores");

            for ($i = 0; $i < count($categoria); $i++) {
                if ($_GET['cat'] == $categoria[$i])
                    $eleccion = $categoriaCompleta[$i];
            }
        ?>

        <div class="container mt-4">
            <h1 class="h1 display-1 fs-1 text-center"><?php print($eleccion); ?></h1>
            <?php
                require_once("../controller/classes/Stock.php");
                $instancia = new Stock();
                $instancia->pintaProductos($eleccion);
                $instancia->altaProductoCesta();
            ?>
        </div>

        <?php require_once("footer.php"); ?>
    </body>
</html>
<?php ob_end_flush(); ?>