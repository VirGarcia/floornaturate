<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Categorías / Floornaturate</title>
        <?php require("../controller/head.php"); ?>
    </head>

    <body class="bg-light">
        <?php
            require_once("../controller/classes/Consultas.php");
            $instancia = new Consultas();
            $dni = $_COOKIE['dni'];
            
            if ($instancia->permisoUsuario($dni) != "Administrador")
                header("location: index.php");

            require_once("../view/header.php");
            require_once("../view/menu.php");
            $instancia = new Consultas();
        ?>

        <!-- Formulario principal -->
        <?php require_once("admin_botones.php"); ?>

        <div class="container">
            <div class="row justify-content-center mt-5 mb-5">
                <div class="col-auto">
                    <label class="display-6 fs-4">Todas las categorías</label>
                </div>
                <div class="col-auto">
                    <a href="categorias_alta.php">
                        <button type="submit" class="btn btn-secondary">Consultar</button>
                    </a>
                </div>
            </div>

            <div class="row justify-content-center mt-5 mb-5">
                <div class="col-auto">
                    <label class="display-6 fs-4">Nueva categoría</label>
                </div>
                <div class="col-auto">
                    <form method="POST">
                        <div class="row">
                            <div class="col-auto">
                                <input type="text" class="form-control" name="categoria" required/>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-secondary" name="altaCategoria">Alta</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row justify-content-center mt-5 mb-5">
                <div class="col-auto">
                    <label class="display-6 fs-4">Categorías de baja</label>
                </div>
                <div class="col-auto">
                    <a href="categorias_baja.php">
                        <button type="submit" class="btn btn-secondary">Consultar</button>
                    </a>
                </div>
            </div>
        </div>

        <?php
            $instancia->altaCategoria();
            require_once("footer.php");
        ?>
    </body>
</html>
<?php ob_end_flush(); ?>