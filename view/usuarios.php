<?php ob_start(); ?>
<!doctype html>
<html lang="es">
    <head>
        <title>Usuarios / Floornaturate</title>
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
                    <label class="display-6 fs-4">Todos los usuarios</label>
                </div>
                <div class="col-auto">
                    <a href="usuarios_alta.php">
                        <button type="submit" class="btn btn-secondary">Consultar</button>
                    </a>
                </div>
            </div>

            <div class="row justify-content-center mt-5 mb-5">
                <div class="col-auto">
                    <label class="display-6 fs-4">Nuevo usuario</label>
                </div>
                <div class="col-auto">
                    <a href="formUsuario.php">
                        <button type="submit" class="btn btn-secondary">Alta</button>
                    </a>
                </div>
            </div>

            <div class="row justify-content-center mt-5 mb-5">
                <div class="col-auto">
                    <label class="display-6 fs-4">Usuarios de baja</label>
                </div>
                <div class="col-auto">
                    <a href="usuarios_baja.php">
                        <button type="submit" class="btn btn-secondary">Consultar</button>
                    </a>
                </div>
            </div>
        </div>

        <?php require_once("footer.php"); ?>
    </body>
</html>
<?php ob_end_flush(); ?>