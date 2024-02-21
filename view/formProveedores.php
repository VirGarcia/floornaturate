<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Proveedores / Floornaturate</title>
        <?php require("../controller/head.php"); ?>
    </head>

    <body class="bg-light">
        <?php
            require_once("header.php");
            require_once("menu.php");
            require_once("admin_botones.php");
        ?>

        <div class="container justify-content-center text-center mt-5">
            <h1 class="h1 display-3 mb-5">Formulario de registro Proveedores</h1>

            <form method="POST" class="display-6 fs-4">
                <div class="row justify-content-center">
                    <div class="col-auto">
                        CIF:
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" size="10" name="cif" required /><br>
                    </div>
                    <div class="col-auto">
                        Nombre:
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" size="10" name="nombre" required /><br>
                    </div>
                    <div class="col-auto">
                        Dirección:
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" size="10" name="direccion" required /><br>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-auto">
                        Teléfono:
                    </div>
                    <div class="col-auto">
                        <input type="tel" class="form-control" size="20" name="telefono" required /><br>
                    </div>
                    <div class="col-auto">
                        Email:
                    </div>
                    <div class="col-auto">
                        <input type="email" class="form-control" size="20" name="email" required /><br>
                    </div>
                </div>
                <div class="col-auto">
                        <button class="btn btn-success" type="submit" name="alta">Alta</button>
                    </div>
                </div>
            </form>
        </div>

        <?php
            require_once("footer.php");
            require_once("../controller/classes/Consultas.php");
            $instancia = new Consultas();
            $instancia->altaProveedor();
        ?>
    </body>
</html>
<?php ob_end_flush(); ?>