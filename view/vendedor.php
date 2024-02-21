<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Vendedores / Floornaturate</title>
        <?php require("../controller/head.php"); ?>
    </head>

    <body class="bg-light">
        <?php
            require_once("../controller/classes/Administrador.php");
            $instancia = new Administrador();
            $dni = $_COOKIE['dni'];
            
            if (!$instancia->permisoUsuario($dni) == "Vendedor")
                header("location: index.php");

            require_once("../view/header.php");
            require_once("../view/menu.php");

            $instancia = new Administrador();
        ?>

        <!-- Formulario principal -->
        <div class="container">
            <div class="row justify-content-center mt-5 mb-5">
                <div class="col-auto">
                        <a href="?proveedores"><button type="submit" class="btn btn-dark me-3">Proveedores</button></a>
                        <a href="?clientes"><button type="submit" class="btn btn-dark me-3">Clientes</button></a>
                        <a href="?productos"><button type="submit" class="btn btn-dark me-3">Productos</button></a>
                </div>
            </div>
        </div>

        <!-- Formulario de productos -->
        <?php if (isset($_GET['productos']) && !isset($_POST['categoria']) && !isset($_POST['todos_productos'])) { ?>
            <div class="container">
                <div class="row justify-content-center mt-5 mb-5">
                    <div class="col-auto">
                        <form method="POST">
                            <div class="row">
                                <div class="col-auto">
                                    <label class="display-6 fs-4">Productos por Categoria</label>
                                </div>
                                <div class="col-auto">
                                    <?php $instancia->pintaCategorias(); ?>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-secondary" name="consultar_categoria">Consultar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row justify-content-center mt-5 mb-5">
                    <div class="col-auto">
                        <form method="POST">
                            <div class="row">
                                <div class="col-auto">
                                    <label class="display-6 fs-4">Todos los productos</label>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-secondary" name="todos_productos">Consultar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
            }

            if (isset($_POST['todos_productos']))
                $instancia->pintaTablaProductosAlta("");

            if (isset($_POST['consultar_categoria']))
                $instancia->pintaTablaProductosAlta($_POST['categoria']);
        ?>

        <!-- Formulario de proveedores -->
        <?php if (isset($_GET['proveedores']) && !isset($_POST['consultar_cif']) && !isset($_POST['todos_proveedores'])) { ?>
            <div class="container">
                <div class="row justify-content-center mt-5 mb-5">
                    <div class="col-auto">
                        <form method="POST">
                            <div class="row">
                                <div class="col-auto">
                                    <label class="display-6 fs-4">Proveedores por CIF</label>
                                </div>
                                <div class="col-auto">
                                    <input type="text" class="form-control" size="10" name="cif" required />
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-secondary" name="consultar_cif">Consultar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row justify-content-center mt-5 mb-5">
                    <div class="col-auto">
                        <form method="POST">
                            <div class="row">
                                <div class="col-auto">
                                    <label class="display-6 fs-4">Todos los proveedores</label>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-secondary" name="todos_proveedores">Consultar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
            }

            if (isset($_POST['todos_proveedores']))
                $instancia->pintaTablaProveedoresAlta("");

            if (isset($_POST['consultar_cif']))
                $instancia->pintaTablaProveedoresAlta($_POST['cif']);
        ?>

        <!-- Formulario de clientes -->
        <?php if (isset($_GET['clientes']) && !isset($_POST['buscar_dni']) && !isset($_POST['todos_clientes']) && !isset($_POST['clientesBaja'])) { ?>
            <div class="container">
                <div class="row justify-content-center mt-5 mb-5">
                    <div class="col-auto">
                        <form method="POST">
                            <div class="row">
                                <div class="col-auto">
                                    <label class="display-6 fs-4">Clientes por DNI</label>
                                </div>
                                <div class="col-auto">
                                    <input type="text" class="form-control" size="10" name="dni" required />
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-secondary" name="buscar_dni">Consultar/Modificar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row justify-content-center mt-5 mb-5">
                    <div class="col-auto">
                        <form method="POST">
                            <div class="row">
                                <div class="col-auto">
                                    <label class="display-6 fs-4">Todos los clientes</label>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-secondary" name="todos_clientes">Consultar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row justify-content-center mt-5 mb-5">
                    <div class="col-auto">
                        <div class="row">
                            <div class="col-auto">
                                <label class="display-6 fs-4">Nuevo cliente</label>
                            </div>
                            <div class="col-auto">
                                <a href="registro.php">
                                    <button type="submit" class="btn btn-secondary">Alta</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center mb-5">
                    <div class="col-auto">
                        <form method="POST">
                            <div class="row">
                                <div class="col-auto">
                                    <label class="display-6 fs-4">Clientes de baja</label>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-secondary" name="clientesBaja">Consultar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
            }

            if (isset($_POST['todos_clientes']))
                $instancia->pintaTablaClientesAlta("");

            if (isset($_POST['buscar_dni']))
                $instancia->pintaTablaClientesAlta($_POST['dni']);

            if (isset($_GET['modificarCliente']))
                $instancia->formModificarCliente();

            if (isset($_POST['guardarCliente']))
                $instancia->modificarCliente();

            if (isset($_POST['clientesBaja']) || isset($_POST['restaurar']))
                $instancia->pintaTablaClientesBaja("");
            
            if (isset($_POST['registrar']))
                $instancia->altaCliente();
        ?>

        <?php require_once("footer.php"); ?>
    </body>
</html>
<?php ob_end_flush(); ?>