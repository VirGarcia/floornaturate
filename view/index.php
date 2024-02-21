<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Floornaturate</title>
        <?php require("../controller/head.php"); ?>
    </head>

    <body class="bg-light">
        <?php
            require_once("header.php");
            require_once("menu.php");
        ?>

        <div class="container justify-content-center">
            <?php
                require_once("../controller/classes/Printer.php");
                $instancia = new Printer();

                $instancia->galeriaIndex();
                
                print("<div>");
                    $instancia->frasesComunicacion();
                print("</div>");
            ?>
        </div>

        <?php require_once("footer.php"); ?>
    </body>
</html>
<?php ob_end_flush(); ?>