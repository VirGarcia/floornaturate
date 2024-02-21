<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Administradores / Floornaturate</title>
        <?php require("../controller/head.php"); ?>
    </head>

    <body class="bg-light">
        <?php
            require_once("../controller/classes/Administrador.php");
            $instancia = new Administrador();
            $dni = $_COOKIE['dni'];
            
            if (!$instancia->permisoUsuario($dni) == "Administrador")
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
                    <a href="usuarios.php"><button type="submit" class="btn btn-dark me-3">Usuarios</button></a>
                    <a href="categorias.php"><button type="submit" class="btn btn-dark me-3">Categorías</button></a>
                </div>
            </div>
        </div>

        <!-- Formulario de productos -->
        <?php if (isset($_GET['productos']) && !isset($_POST['todos_productos']) && !isset($_POST['categoria']) && !isset($_POST['productosBaja'])
            && !isset($_POST['baja']) && !isset($_POST['restaurar'])) { ?>
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
                                    <button type="submit" class="btn btn-secondary" name="buscar_categoria">Consultar</button>
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
                <div class="row justify-content-center mt-5 mb-5">
                    <div class="col-auto">
                        <div class="row">
                            <div class="col-auto">
                                <label class="display-6 fs-4">Nuevo producto</label>
                            </div>
                            <div class="col-auto">
                                <a href="formStock.php?altaProducto">
                                    <button type="submit" class="btn btn-secondary">Alta</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center mt-2 mb-5">
                    <div class="col-auto">
                        <form method="POST">
                            <div class="row">
                                <div class="col-auto">
                                    <label class="display-6 fs-4">Productos de baja</label>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-secondary" name="productosBaja">Consultar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
            }

            if (isset($_GET['productos']) && (isset($_POST['todos_productos']) ||  isset($_POST['baja'])))
                $instancia->pintaTablaProductosAlta("");

            if (isset($_POST['buscar_categoria']))
                $instancia->pintaTablaProductosAlta($_POST['categoria']);

            if (isset($_POST['producto']))
                $instancia->formAltaProducto($_POST['producto']);

            if (isset($_GET['modificarProducto']))
                $instancia->formModificarProducto();

            if (isset($_POST['guardarProducto']))
                $instancia->modificarProducto();
            
            if (isset($_GET['productos']) && (isset($_POST['productosBaja']) || isset($_POST['restaurar'])))
                $instancia->pintaTablaProductosBaja("");
        ?>

        <!-- Formulario de proveedores -->
        <?php if (isset($_GET['proveedores']) && !isset($_GET['tablaProveedores']) && !isset($_POST['cif']) && !isset($_POST['proveedoresBaja'])) { ?>
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
                                    <button type="submit" class="btn btn-secondary" name="consultar_cif">Consultar/Modificar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row justify-content-center mt-5 mb-5">
                    <div class="col-auto">
                        <div class="row">
                            <div class="col-auto">
                                <label class="display-6 fs-4">Todos los proveedores</label>
                        </div>
                        <div class="col-auto">
                            <a href="?proveedores&tablaProveedores">
                                <button type="submit" class="btn btn-secondary">Consultar</button>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center mt-5 mb-5">
                    <div class="col-auto">
                        <form  action="formProveedores.php" method="POST">
                            <div class="row">
                                <div class="col-auto">
                                    <label class="display-6 fs-4">Nuevo proveedor</label>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-secondary" name="altaProveedor">Alta</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row justify-content-center mt-5 mb-5">
                    <div class="col-auto">
                        <form method="POST">
                            <div class="row">
                                <div class="col-auto">
                                    <label class="display-6 fs-4">Proveedores de baja</label>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-secondary" name="proveedoresBaja">Consultar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
            }

        

            if (isset($_GET['tablaProveedores']))
                $instancia->pintaTablaProveedoresAlta("");

            if (isset($_GET['proveedores']) && (isset($_POST['proveedoresBaja']) || isset($_POST['restaurar'])))
                $instancia->pintaTablaProveedoresBaja("");

            if (isset($_GET['proveedores']) && (isset($_POST['consultar_cif']) || isset($_POST['baja']))) {
                //if ($instancia->existeCIF($_POST['cif'])) {
                    if ($instancia->proveedorDeBaja($_POST['cif']) && !isset($_POST['baja']))
                        $instancia->pintaTablaProveedoresBaja($_POST['cif']);
                    else
                        $instancia->pintaTablaProveedoresAlta($_POST['cif']);
                //}
            }

            if (isset($_POST['altaProveedor']))
                $instancia->altaProveedor();

            if (isset($_GET['modificarProveedor']))
                $instancia->formModificarProveedor();

            if (isset($_POST['guardarProveedor']))
                $instancia->modificarProveedor();
        ?>

        <!-- Formulario de clientes -->
        <?php if (isset($_GET['clientes']) && !isset($_GET['tablaClientes']) && !isset($_POST['dni']) && !isset($_POST['clientesBaja'])) { ?>
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
                                        <button type="submit" class="btn btn-secondary" name="consultar_dni">Consultar/Modificar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row justify-content-center mt-5 mb-5">
                    <div class="col-auto">
                        <div class="row">
                            <div class="col-auto">
                                <label class="display-6 fs-4">Todos los clientes</label>
                            </div>
                            <div class="col-auto">
                                <a href="?clientes&tablaClientes">
                                    <button type="submit" class="btn btn-secondary">Consultar</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center mt-5 mb-5">
                    <div class="col-auto">
                        <form action="registro.php" method="POST">
                            <div class="row">
                                <div class="col-auto">
                                    <label class="display-6 fs-4">Nuevo cliente</label>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-secondary" name="altaCliente">Alta</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row justify-content-center mt-5 mb-5">
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

            if (isset($_GET['tablaClientes']))
                $instancia->pintaTablaClientesAlta("");

            if (isset($_GET['clientes']) && (isset($_POST['clientesBaja']) || isset($_POST['restaurar'])))
                $instancia->pintaTablaClientesBaja("");

            if (isset($_GET['clientes']) && (isset($_POST['consultar_dni']) || isset($_POST['baja']))) {
                if ($instancia->clienteDeBaja($_POST['dni']) && !isset($_POST['baja']))
                    $instancia->pintaTablaClientesBaja($_POST['dni']);
                else
                    $instancia->pintaTablaClientesAlta($_POST['dni']);
            }

            if (isset($_GET['modificarCliente']))
                $instancia->formModificarCliente();

            if (isset($_POST['guardarCliente']))
                $instancia->modificarCliente();

            if (isset($_POST['altaCliente']))
                $instancia->altaCliente();
        ?>

        <?php require_once("footer.php"); ?>
        <script type="text/javascript" src="assets/js/selects.js"></script>
    </body>
</html>
<?php ob_end_flush(); ?>