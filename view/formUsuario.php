<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Usuarios / Floornaturate</title>
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
        ?>

        <!-- Formulario principal -->
        <?php require_once("admin_botones.php"); ?>

        <div class="container justify-content-center text-center mt-5">
            <h1 class="h1 display-3 mb-5">Formulario de registro Usuarios</h1>

            <form method="POST" class="display-6 fs-4">
                <div class="row justify-content-center">
                    <div class="col-auto">
                        DNI:
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" size="10" name="dni" required /><br>
                    </div>

                    <div class="col-auto">
                        Iniciales:
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" size="10" name="iniciales" required /><br>
                    </div>

                    <div class="col-auto">
                        Email:
                    </div>
                    <div class="col-auto">
                        <input type="email" class="form-control" size="20" name="email" required /><br>
                    </div>
                </div>
                
                <div class="row justify-content-center">
                    <div class="col-auto">
                        Contraseña:
                    </div>
                    <div class="col-auto">
                        <input type="password" class="form-control" size="10" name="clave" required /><br>
                    </div>
                    <div class="col-auto">
                        <?php $instancia->pintaPermisos(); ?>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-outline-success" type="submit" name="altaUsuario">Alta</button>
                    </div>
                </div>
            </form>
        </div>

        <?php
            $instancia->altaUsuario();
            require_once("footer.php");
        ?>
    </body>
</html>
<?php ob_end_flush(); ?>