<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Registro / Floornaturate</title>
        <?php require("../controller/head.php"); ?>
    </head>

    <body class="bg-light">
        <?php
            require_once("header.php");
            require_once("menu.php");
            require_once("../controller/classes/Seguridad.php");

            $instancia = new Seguridad();
            


            if ($instancia->comprobarLogin()
                && !$instancia->comprobarCliente($_COOKIE['dni']))
            require_once("admin_botones.php");
        ?>

        <div class="container justify-content-center text-center mt-5">
            <h1 class="h1 display-3 mb-5">Formulario de registro Clientes</h1>

            <form method="POST" class="display-6 fs-4">
                <div class="row justify-content-center">
                    <div class="col-auto">
                        DNI:
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" size="10" name="dni" required /><br>
                    </div>

                    <?php if (!$instancia->comprobarLogin()) { ?>
                        <div class="col-auto">
                            Contraseña:
                        </div>
                        <div class="col-auto">
                            <input type="password" class="form-control" size="10" name="clave" required /><br>
                        </div>
                    <?php } ?>

                    <div class="col-auto">
                        Nombre:
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" size="10" name="nombre" required /><br>
                    </div>
                    <div class="col-auto">
                        Apellidos:
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" size="20" name="apellidos" required /><br>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-auto">
                        Dirección:
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" size="20" name="direccion" required /><br>
                    </div>
                    <div class="col-auto">
                        Teléfono:
                    </div>
                    <div class="col-auto">
                        <input type="tel" class="form-control" size="10" name="telefono" required /><br>
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
                        Fecha de nacimiento:
                    </div>
                    <div class="col-auto">
                        <input type="date" class="form-control" size="10" name="fecha_nac" required /><br>
                    </div>
                </div>
                <div class="row justify-content-center align-items-center">
                    <div class="col-auto">
                        Preferencias:
                    </div>
                    <div class="col-auto">
                        <textarea class="form-control" cols="75" name="preferencias"></textarea>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-outline-success" type="submit" name="registrar">Alta</button>
                    </div>
                </div>
            </form>
        </div>

        <?php
            require_once("footer.php");
            require_once("../controller/classes/Consultas.php");
            $instancia = new Consultas();
            $instancia->altaCliente();
        ?>
    </body>
</html>
<?php ob_end_flush(); ?>