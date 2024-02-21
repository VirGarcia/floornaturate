<?php
    require_once("Consultas.php");

    class Administrador extends Consultas {


        //Proveedores

        function pintaTablaProveedoresAlta(string $cif) {
            if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador" ||
                $this->permisoUsuario($_COOKIE['dni']) == "Vendedor") {
                
                if (empty($cif))
                    $datos = $this->listarProveedoresAlta();

                else
                    $datos = $this->listarProveedoresAlta($cif);

                if (!$datos) {
                    if (!$this->listarNumeroProveedoresAlta())
                        return $this->mensaje("No existen proveedores de alta.", "secondary");
                    if (!$this->existeCIF($cif))
                        return $this->mensaje("Este proveedor no existe.", "secondary");
                    else
                        return;
                }
    
                if (!isset($_GET['modificarProveedor'])) {
                    print("
                        <div class='container'>");

                            if (empty($cif))
                                print("<h1 class='h1 display-6 fs-2 fw-bold text-center'>Proveedores de alta</h1>");
                            else {
                                print("
                                    <h1 class='h1 display-6 fs-2 fw-bold text-center'>
                                        Datos del proveedor con CIF: ".$datos['cif']."
                                    </h1>
                                ");
                            }

                            print("
                            <table class='table table-bordered border-dark table-striped display-6 fs-5 mt-4'>
                                <tr>
                                    <th>CIF</th>
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Fecha de alta</th>");

                                    if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador")
                                        print("<th>Acción</th>");
                                    
                                    print("
                                </tr>");

                                if (empty($cif)) {
                                    for ($i = 0; $i < $this->listarNumeroProveedoresAlta(); $i++) {
                                        print("
                                            <tr>
                                                <td>".$datos[$i]['cif']."</td>
                                                <td>".$datos[$i]['nombre']."</td>
                                                <td>".$datos[$i]['direccion']."</td>
                                                <td>".$datos[$i]['telefono']."</td>
                                                <td>".$datos[$i]['email']."</td>
                                                <td>".$this->cambiarFormatoFecha($datos[$i]['fecha_alta'])."</td>");

                                                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                                                    print("
                                                        <td>
                                                            <a href='?modificarProveedor=".$datos[$i]['cif']."'>
                                                                <button class='btn btn-warning'>
                                                                    <img src='../pictures/editar.png' class='boton' />
                                                                </button>
                                                            </a>

                                                            <form method='POST'>
                                                                <input type='hidden' value='".$datos[$i]['cif']."' name='cif' />
                                                                <button class='btn btn-danger' name='baja' onclick=\"return confirm('¿Seguro que quieres dar de baja a este proveedor?')\">
                                                                    <img src='../pictures/x.png' class='boton' />
                                                                </button>
                                                            </form>
                                                        </td>
                                                    ");
                                                }
                                                print("
                                            </tr>
                                        ");
                                    }
                                }
                                else {
                                    print("
                                        <tr>
                                            <td>".$datos['cif']."</td>
                                            <td>".$datos['nombre']."</td>
                                            <td>".$datos['direccion']."</td>
                                            <td>".$datos['telefono']."</td>
                                            <td>".$datos['email']."</td>
                                            <td>".$this->cambiarFormatoFecha($datos['fecha_alta'])."</td>");

                                            if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                                                print("
                                                    <td>
                                                        <a href='?modificarProveedor=".$datos['cif']."'>
                                                            <button class='btn btn-warning'>
                                                                <img src='../pictures/editar.png' class='boton' />
                                                            </button>
                                                        </a>

                                                        <form method='POST'>
                                                            <input type='hidden' value='".$datos['cif']."' name='cif' />
                                                            <button class='btn btn-danger' name='baja' onclick=\"return confirm('¿Seguro que quieres dar de baja a este proveedor?')\">
                                                                <img src='../pictures/x.png' class='boton' />
                                                            </button>
                                                        </form>
                                                    </td>
                                                ");
                                            }

                                            print("
                                        </tr>
                                    ");
                                }
                                print("
                            </table>
                        </div>
                    ");
                    $this->bajaLogicaProveedor();
                }
            }
        }

        function pintaTablaProveedoresBaja(string $cif) {
            if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador" ||
                $this->permisoUsuario($_COOKIE['dni']) == "Vendedor") {
                
                if (empty($cif))
                    $datos = $this->listarProveedoresBaja();
                    
                else
                    $datos = $this->listarProveedoresBaja($cif);
                
                if (!$datos) {
                    if (!$this->listarNumeroProveedoresBaja())
                        return $this->mensaje("No existen proveedores de baja.", "secondary");
                    if (!$this->existeCIF($cif))
                        return $this->mensaje("Este proveedor no existe.", "secondary");
                    else
                        return;
                }
    
                    print("
                    <h1 class='h1 display-6 fs-2 fw-bold text-center'>Proveedores de baja</h1>
                        <div class='container'>

                            <table class='table table-bordered border-dark table-striped display-6 fs-5 mt-3'>
                                <tr>
                                    <th>CIF</th>
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Fecha de baja</th>");

                                    if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador")
                                        print("<th>Acción</th>");
                                    
                                    print("
                                </tr>");

                                if (empty($cif)) {
                                    for ($i = 0; $i < $this->listarNumeroProveedoresBaja(); $i++) {
                                        print("
                                            <tr>
                                                <td>".$datos[$i]['cif']."</td>
                                                <td>".$datos[$i]['nombre']."</td>
                                                <td>".$datos[$i]['direccion']."</td>
                                                <td>".$datos[$i]['telefono']."</td>
                                                <td>".$datos[$i]['email']."</td>
                                                <td>".$this->cambiarFormatoFecha($datos[$i]['fecha_baja'])."</td>");

                                                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                                                    print("
                                                        <td>
                                                            <form method='POST'>
                                                                <input type='hidden' value='".$datos[$i]['cif']."' name='cif' />
                                                                <button class='btn btn-success' name='restaurar' onclick=\"return confirm('¿Seguro que quieres restaurar este proveedor?')\">
                                                                    <img src='../pictures/restaurar.png' class='boton' />
                                                                </button>
                                                            </form>
                                                        </td>
                                                    ");
                                                }
                                                print("
                                            </tr>
                                        ");
                                    }
                                }
                                else {
                                    print("
                                        <tr>
                                            <td>".$datos['cif']."</td>
                                            <td>".$datos['nombre']."</td>
                                            <td>".$datos['direccion']."</td>
                                            <td>".$datos['telefono']."</td>
                                            <td>".$datos['email']."</td>
                                            <td>".$this->cambiarFormatoFecha($datos['fecha_baja'])."</td>");

                                            if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                                                print("
                                                    <td>
                                                        <form method='POST'>
                                                            <input type='hidden' value='".$datos['cif']."' name='cif' />
                                                            <button class='btn btn-success' name='restaurar' onclick=\"return confirm('¿Seguro que quieres restaurar este proveedor?')\">
                                                                <img src='../pictures/restaurar.png' class='boton' />
                                                            </button>
                                                        </form>
                                                    </td>
                                                ");
                                            }

                                            print("
                                        </tr>
                                    ");
                                }
                                print("
                            </table>
                        </div>
                    ");
                    $this->restaurarProveedor();
            }
        }

        function pintaProveedores() {
            $proveedores = $this->listarNombresProveedores();

            print("<select class='form-select' name='proveedor'>");

            for ($i = 0; $i < $this->listarNumeroProveedoresAlta(); $i++) {
                    print("<option>".$proveedores[$i][0]."</option>");
            }
            print("</select>");
        }

        function formModificarProveedor() {
            if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {

                $datos = $this->listarProveedoresAlta($_GET['modificarProveedor']);
                
                print("
                    <div class='container'>
                        <form method='POST' action='?proveedores&tablaProveedores'>
                            <table class='table table-bordered border-dark table-striped display-6 fs-5 mt-5'>
                                <tr>
                                    <th>CIF</th>
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Acción</th>
                                </tr>

                                <tr>
                                    <td>
                                        <input type='hidden' value='".$datos['id_proveedor']."' name='id_proveedor' />
                                        <input type='text' value='".$datos['cif']."' name='cif' class='form-control' />
                                    </td>
                                    <td>
                                        <input type='text' value='".$datos['nombre']."' name='nombre'
                                        class='form-control' />
                                    </td>
                                    <td>
                                        <input type='text' value='".$datos['direccion']."' name='direccion'
                                        class='form-control' />
                                    </td>
                                    <td>
                                        <input type='tel' value='".$datos['telefono']."' name='telefono'
                                        class='form-control' size='11' />
                                    </td>
                                    <td>
                                        <input type='email' value='".$datos['email']."' name='email'
                                        class='form-control' />
                                    </td>
                                    <td>
                                        <button type='submit' class='btn btn-outline-success' name='guardarProveedor'>
                                            Guardar
                                        </button>
                                        <a href='administrador.php?proveedores&tablaProveedores'>
                                            <input type='button' class='btn btn-outline-danger' value='Cancelar' />
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                ");
            }
        }



        // Productos

        function pintaTablaProductosAlta(string $categoria) {
            if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador" ||
                $this->permisoUsuario($_COOKIE['dni']) == "Vendedor") {

                if (empty($categoria))
                    
                    $datos = $this->listarStockAlta();

                else
                    $datos = $this->listarStockAlta($categoria);

                if (!$datos) {
                    if (!$this->listarNumeroProductosAlta())
                        return $this->mensaje("No existen productos de alta.", "secondary");
                    else
                        return;
                }
                if (!isset($_GET['modificarProducto'])) {
                    print("
                        <div class='container'>
                            <table class='table table-bordered border-dark table-striped display-6 fs-5 mt-4'>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>");
                    
                                    if ($this->permisoUsuario($_COOKIE['dni']) == 'Administrador')
                                        print("<th>Coste</th>");
                    
                                    print("
                                        <th>PVP</th>
                                        <th>Categoría</th>
                                        <th>Fecha de alta</th>
                                    ");
                                
                                    if ($this->permisoUsuario($_COOKIE['dni']) == 'Administrador')
                                        print("<th>Accion</th>");
                    
                                    print("
                                </tr>");

                                    if (empty($categoria)) {
                                        print("<h1 class='h1 display-6 fs-2 fw-bold text-center'>Productos de alta</h1>");
                                        for ($i = 0; $i < $this->listarNumeroProductosAlta(); $i++) {
                                            print("
                                                <tr>
                                                    <td>".$datos[$i]['nombre']."</td>
                                                    <td>".$datos[$i]['descripcion']."</td>");

                                                    if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador")
                                                        print("<td>".$datos[$i]['coste']."</td>");
                                                        
                                                    print("
                                                    <td>".$datos[$i]['pvp']." €</td>
                                                    <td>".$datos[$i]['categoria']."</td>
                                                    <td>".$this->cambiarFormatoFecha($datos[$i]['fecha_alta'])."</td>");

                                                    if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                                                        print("
                                                            <td>
                                                                <a href='?modificarProducto=".$datos[$i]['id_producto']."'>
                                                                    <button class='btn btn-warning'>
                                                                        <img src='../pictures/editar.png' class='boton' />
                                                                    </button>
                                                                </a>

                                                                <form method='POST'>
                                                                    <input type='hidden' value='".$datos[$i]['id_producto']."' name='id_producto' />
                                                                    <button class='btn btn-danger' name='baja' onclick=\"return confirm('¿Seguro que quieres dar de baja este producto?')\">
                                                                        <img src='../pictures/x.png' class='boton' />
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        ");
                                                    }

                                                    print("
                                                </tr>
                                            ");
                                        }
                                    }
                                    else {
                                        print("<h1 class='h1 display-6 fs-2 fw-bold text-center'>
                                                Productos categoría: ".$categoria."
                                            </h1>");
                                        for ($i = 0; $i < $this->listarNumeroProductosAlta($categoria); $i++) {
                                            print("
                                                    <tr>
                                                    <td>".$datos[$i]['nombre']."</td>
                                                    <td>".$datos[$i]['descripcion']."</td>");

                                                    if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador")
                                                        print("<td>".$datos[$i]['coste']."</td>");
                                                        
                                                    print("
                                                    <td>".$datos[$i]['pvp']." €</td>
                                                    <td>".$datos[$i]['categoria']."</td>
                                                    <td>".$this->cambiarFormatoFecha($datos[$i]['fecha_alta'])."</td>");

                                                    if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                                                        print("
                                                            <td>
                                                                <a href='?modificarProducto=".$datos[$i]['id_producto']."'>
                                                                    <button class='btn btn-warning'>
                                                                        <img src='../pictures/editar.png' class='boton' />
                                                                    </button>
                                                                </a>

                                                                <form method='POST'>
                                                                    <input type='hidden' value='".$datos[$i]['id_producto']."' name='id_producto' />
                                                                    <button class='btn btn-danger' name='baja' onclick=\"return confirm('¿Seguro que quieres dar de baja este producto?')\">
                                                                        <img src='../pictures/x.png' class='boton' />
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        ");
                                                    }

                                                    print("
                                                </tr>
                                            ");
                                        }
                                    }
                                
                                print("
                            </table>
                        </div>
                    ");
                    $this->bajaLogicaProducto();
                }
            }
        }

        function pintaTablaProductosBaja(string $categoria) {
            if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {

                if (empty($categoria))
                    $datos = $this->listarStockBaja();

                else
                    $datos = $this->listarStockBaja($categoria);

                if (!$datos) {
                    if (!$this->listarNumeroProductosBaja())
                        return $this->mensaje("No existen productos de baja.", "secondary");
                    else
                        return;
                }

                if (!isset($_GET['modificarProducto'])) {
                    print("
                    <h1 class='h1 display-6 fs-2 fw-bold text-center'>Productos de baja</h1>
                        <div class='container'>
                            <table class='table table-bordered border-dark table-striped display-6 fs-5 mt-4'>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>   
                                    <th>Coste</th>
                                    <th>PVP</th>
                                    <th>Categoría</th>
                                    <th>Fecha de baja</th>
                                    <th>Acción</th>
                                </tr>");

                                if (empty($categoria)) {
                                    for ($i = 0; $i < $this->listarNumeroProductosBaja(); $i++) {
                                        print("
                                            <tr>
                                                <td>".$datos[$i]['nombre']."</td>
                                                <td>".$datos[$i]['descripcion']."</td>");

                                                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador")
                                                    print("<td>".$datos[$i]['coste']."</td>");
                                                    
                                                print("
                                                <td>".$datos[$i]['pvp']." €</td>
                                                <td>".$datos[$i]['categoria']."</td>
                                                <td>".$this->cambiarFormatoFecha($datos[$i]['fecha_baja'])."</td>");

                                                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                                                    print("
                                                        <td>
                                                            <form method='POST'>
                                                                <input type='hidden' value='".$datos[$i]['id_producto']."' name='id_producto' />
                                                                <button class='btn btn-success' name='restaurar' onclick=\"return confirm('¿Seguro que quieres restaurar este producto?')\">
                                                                    <img src='../pictures/restaurar.png' class='boton' />
                                                                </button>
                                                            </form>
                                                        </td>
                                                    ");
                                                }

                                                print("
                                        </tr>
                                        ");
                                    }
                                }
                                else {
                                    for ($i = 0; $i < $this->listarNumeroProductosBaja($categoria); $i++) {
                                        print("
                                            <tr>
                                                <td>".$datos[$i]['nombre']."</td>
                                                <td>".$datos[$i]['descripcion']."</td>");

                                                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador")
                                                    print("<td>".$datos[$i]['coste']."</td>");
                                                    
                                                print("
                                                <td>".$datos[$i]['pvp']." €</td>
                                                <td>".$datos[$i]['categoria']."</td>
                                                <td>".$this->cambiarFormatoFecha($datos[$i]['fecha_baja'])."</td>");

                                                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                                                    print("
                                                        <td>
                                                            <form method='POST'>
                                                                <input type='hidden' value='".$datos[$i]['id_producto']."' name='id_producto' />
                                                                <button class='btn btn-success' name='restaurar' onclick=\"return confirm('¿Seguro que quieres restaurar este producto?')\">
                                                                    <img src='../pictures/restaurar.png' class='boton' />
                                                                </button>
                                                            </form>
                                                        </td>
                                                    ");
                                                }

                                                print("
                                            </tr>
                                        ");
                                    }
                                }
                                print("
                            </table>
                        </div>
                    ");
                    $this->restaurarProducto();
                }
            }
        }

        function formAltaProducto() {
            print("
                <div class='container justify-content-center text-center mt-5'>
                    <h1 class='h1 display-3 mb-5'>Formulario de registro Productos</h1>
                        <form method='POST' class='display-6 fs-4'>
                            <div class='row justify-content-center'>
                                <div class='col-auto'>
                                    Nombre:
                                </div>
                                <div class='col-auto'>
                                    <input type='text' class='form-control' size='70' name='nombre' required /><br>
                                </div>
                            </div>
                            <div class='row justify-content-center align-items-center mb-4'>
                                <div class='col-auto'>
                                    Descripción:
                                </div>
                                <div class='col-auto'>
                                    <textarea class='form-control' cols='80' rows='5' name='descripcion' required></textarea>
                                </div>
                            </div>
                            <div class='row justify-content-center'>
                                <div class='col-auto'>
                                    Coste:
                                </div>
                                <div class='col-auto'>
                                    <input type='number' step='any' lang='es' class='form-control' name='coste' required /><br>
                                </div>
                                <div class='col-auto'>
                                    PVP:
                                </div>
                                <div class='col-auto'>
                                    <input type='number' step='any' lang='es' class='form-control' name='pvp' required /><br>
                                </div>
                            </div>
                            <div class='row justify-content-center'>
                                <div class='col-auto'>
                                    Categoría:
                                </div>
                                <div class='col-auto'>");
                                    $this->pintaCategorias();
                                    print("
                                </div>
                                <div class='col-auto'>
                                    Proveedor:
                                </div>
                                <div class='col-auto'>");
                                    $this->pintaProveedores();
                                    print("
                                </div>
                                <div class='col-auto'>
                                    <button type='submit' class='btn btn-outline-success' name='altaProducto'>Alta</button>
                                </div>
                            </div>
                        </form>
                </div>
            ");

            $this->altaProducto();
        }

        function formModificarProducto() {
            if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {

                    $datos = $this->listarDatosProducto($_GET['modificarProducto']);
                    
                    print("
                        <div class='container'>
                            <form method='POST' action='?productos.php'>
                                <table class='table table-bordered border-dark table-striped display-6 fs-5 mt-5'>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Coste</th>
                                        <th>PVP</th>
                                        <th>Categoría</th>
                                        <th>Acción</th>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input type='hidden' value='".$_GET['modificarProducto']."' name='id_producto' />
                                            <input type='text' value='".$datos['nombre']."' name='nombre'
                                            class='form-control' />
                                        </td>
                                        <td>
                                            <input type='text' value='".$datos['descripcion']."' name='descripcion'
                                            class='form-control' />
                                        </td>
                                        <td>
                                            <input type='number' step='any' lang='es' value='".$datos['coste']."' name='coste'
                                            class='form-control' />
                                        </td>
                                        <td>
                                            <input type='number' step='any' lang='es' value='".$datos['pvp']."' name='pvp'
                                            class='form-control' size='11' />
                                        </td>
                                        <td>");

                                        $this->pintaCategorias($datos['categoria']);
                                        
                                        print("
                                        </td>
                                        <td>
                                            <button type='submit' class='btn btn-outline-success' name='guardarProducto'>
                                                Guardar
                                            </button>
                                            <a href='administrador.php?productos.php'>
                                                <input type='button' class='btn btn-outline-danger' value='Cancelar' />
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    ");
            }
        }


        // Categorías

        function pintaTablaCategoriasAlta() {
            if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                if (!isset($_POST['modificarCategoria'])) {
                    $datos = $this->listarCategorias(false);
                    ?>

                    <div class="container">
                        <table class="table table-bordered border-dark table-striped display-6 fs-4 mt-4">
                            <tr>
                                <th>Categoría</th>
                                <th>Fecha de alta</th>
                                <th>Acciones</th>
                            </tr>

                            <?php
                                print("<h1 class='h1 display-6 fs-2 fw-bold text-center'>Categorías de alta</h1>");
                                for ($i = 0; $i < $this->listarNumeroCategorias(false); $i++) {
                                    print("
                                    
                                        <tr>
                                            <td>".$datos[$i]['categoria']."</td>
                                            <td>".$this->CambiarFormatoFecha($datos[$i]['fecha_alta'])."</td>
                                            <td>
                                                <form method='POST'>
                                                    <input type='hidden' name='id_categoria' value='".$datos[$i]['id_categoria']."' />
                                                    <button class='btn btn-warning' name='modificarCategoria'>
                                                        <img src='../pictures/editar.png' class='boton' />
                                                    </button>
                                                    <button class='btn btn-danger' name='bajaCategoria'
                                                    onclick=\"return confirm('¿Seguro que quieres dar de baja esta categoría?')\">
                                                        <img src='../pictures/x.png' class='boton' />
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    ");
                                }
                            ?>
                        </table>
                    </div>

                    <?php
                }
            }
            else
                $this->mensaje("Permiso denegado.", "secondary");
        }

        function pintaTablaCategoriasBaja() {
            if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                    $datos = $this->listarCategorias(true);

                    if (!$datos)
                        return $this->mensaje("No existen categorías de baja.", "secondary");
    
                    ?>

                    <div class="container">
                    <h1 class='h1 display-6 fs-2 fw-bold text-center'>Categorías de baja</h1>
                        <table class="table table-bordered border-dark table-striped display-6 fs-4 mt-3">
                            <tr>
                                <th>Categoría</th>
                                <th>Fecha de baja</th>
                                <th>Acciones</th>
                            </tr>

                            <?php
                                for ($i = 0; $i < $this->listarNumeroCategorias(true); $i++) {
                                    print("
                                        <tr>
                                            <td>".$datos[$i]['categoria']."</td>
                                            <td>".$this->cambiarFormatoFecha($datos[$i]['fecha_baja'])."</td>
                                            <td>
                                                <form method='POST'>
                                                    <input type='hidden' name='id_categoria' value='".$datos[$i]['id_categoria']."' />
                                                    <button class='btn btn-success' name='restaurarCategoria'
                                                    onclick=\"return confirm('¿Seguro que quieres restaurar esta categoría?')\">
                                                        <img src='../pictures/restaurar.png' class='boton' />
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    ");
                                }
                            ?>
                        </table>
                    </div>

                    <?php
            }
            else
                $this->mensaje("Permiso denegado.", "secondary");
        }

        function formModificarCategoria() {
            if (isset($_POST['modificarCategoria'])) {
                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                    ?>
                    <div class="container">
                        <table class="table table-bordered border-dark table-striped display-6 fs-4">
                            <tr>
                                <th>Categoría</th>
                                <th>Acciones</th>
                            </tr>

                            <tr>
                                <form method='POST'>
                                    <input type='hidden' name='id_categoria'
                                    value='<?php print($_POST['id_categoria']); ?>' />
                                    <td>
                                        <input type="text" class="form-control" name="categoria"
                                        value="<?php print($this->nombre_categoria($_POST['id_categoria'])); ?>" />
                                    </td>
                                    <td>
                                        <button class='btn btn-outline-success' name='guardarCategoria'>
                                            Guardar
                                        </button>
                                        <a href="categorias_alta.php">
                                            <button class='btn btn-outline-danger'>
                                                Cancelar
                                            </button>
                                        </a>
                                    </td>
                                </form>
                            </tr>
                        </table>
                    </div>
                <?php
                }
                else
                    $this->mensaje("Permiso denegado.", "secondary");
            }
        }

        function pintaCategorias($categoria = false) {
            $categorias = $this->listarCategorias(false);

            print("<select class='form-select' name='categoria'>");

            if (isset($_POST['categoria']))
                print("<option selected>".$_POST['categoria']."</option>");
            else if ($categoria)
                print("<option selected>".$categoria."</option>");

            for ($i = 0; $i < $this->listarNumeroCategorias(false); $i++) {
                    print("<option>".$categorias[$i]['categoria']."</option>");
            }
            print("</select>");
        }



        // Clientes

        function pintaTablaClientesAlta(string $dni) {
            if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador" ||
                $this->permisoUsuario($_COOKIE['dni']) == "Vendedor") {
                
                if (empty($dni))
                    $datos = $this->listarClientesAlta();

                else
                    $datos = $this->listarClientesAlta($dni);

                if (!$datos) {
                    if (!$this->listarNumeroClientesAlta())
                        return $this->mensaje("No existen clientes de alta.", "secondary");
                    if (!$this->existeDNI($dni))
                        return $this->mensaje("Este cliente no existe.", "secondary");
                    else
                        return;
                }
    
                if (!isset($_GET['modificarCliente'])) {
                    print("
                        <div class='container'>");
                            if (empty($dni))
                                print("<h1 class='h1 display-6 fs-2 fw-bold text-center'>Clientes de alta</h1>");
                            else {
                                print("
                                <h1 class='h1 display-6 fs-2 fw-bold text-center'>
                                    Datos del cliente con DNI: ".$datos['dni']."
                                </h1>
                            ");

                            }

                            print("
                            <table class='table table-bordered border-dark table-striped display-6 fs-5 mt-4'>
                                <tr>
                                    <th>DNI</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th> 
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Fecha de nacimiento</th>
                                    <th>Preferencias</th>
                                    <th>Fecha de alta</th>
                                    <th>Acción</th>
                                </tr>");

                                if (empty($dni)) {
                                    for ($i = 0; $i < $this->listarNumeroClientesAlta(); $i++) {
                                        print("
                                            <tr>
                                                <td>".$datos[$i]['dni']."</td>
                                                <td>".$datos[$i]['nombre']."</td>
                                                <td>".$datos[$i]['apellidos']."</td>
                                                <td>".$datos[$i]['direccion']."</td>
                                                <td>".$datos[$i]['telefono']."</td>
                                                <td>".$datos[$i]['email']."</td>");

                                                print("<td>");
                                                    if ($datos[$i]['fecha_nac'] != "0000-00-00")
                                                        print($this->cambiarFormatoFecha($datos[$i]['fecha_nac']));
                                                print("</td>");

                                                print("
                                                <td>".$datos[$i]['preferencias']."</td>
                                                <td>".$this->cambiarFormatoFecha($datos[$i]['fecha_alta'])."</td>
                                                <td>
                                                    <a href='?modificarCliente=".$datos[$i]['dni']."'>
                                                        <button class='btn btn-warning'>
                                                            <img src='../pictures/editar.png' class='boton' />
                                                        </button>
                                                    </a>");

                                                    if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                                                        print("
                                                            <form method='POST'>
                                                                <input type='hidden' value='".$datos[$i]['dni']."' name='dni' />
                                                                <button class='btn btn-danger' name='baja' onclick=\"return confirm('¿Seguro que quieres dar de baja a este cliente?')\">
                                                                    <img src='../pictures/x.png' class='boton' />
                                                                </button>
                                                            </form>
                                                        ");
                                                    }

                                                    print("
                                                </td>
                                            </tr>
                                        ");
                                    }
                                }
                                else {
                                    print("
                                        <tr>
                                        <td>".$datos['dni']."</td>
                                        <td>".$datos['nombre']."</td>
                                        <td>".$datos['apellidos']."</td>
                                        <td>".$datos['direccion']."</td>
                                        <td>".$datos['telefono']."</td>
                                        <td>".$datos['email']."</td>");

                                        if ($datos['fecha_nac'] != "0000-00-00")
                                            print("<td>".$this->cambiarFormatoFecha($datos['fecha_nac'])."</td>");

                                        print("
                                        <td>".$datos['preferencias']."</td>
                                        <td>".$this->cambiarFormatoFecha($datos['fecha_alta'])."</td>

                                        <td>
                                            <a href='?modificarCliente=".$datos['dni']."'>
                                                <button class='btn btn-warning'>
                                                    <img src='../pictures/editar.png' class='boton' />
                                                </button>
                                            </a>
                                        ");

                                        if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                                            print("
                                                <form method='POST'>
                                                    <input type='hidden' value='".$datos['dni']."' name='dni' />
                                                    <button class='btn btn-danger' name='baja' onclick=\"return confirm('¿Seguro que quieres dar de baja a este cliente?')\">
                                                        <img src='../pictures/x.png' class='boton' />
                                                    </button>
                                                </form>
                                            ");
                                        }

                                        print("
                                        </td>
                                    </tr>
                                    ");
                                }
                                print("
                            </table>
                        </div>
                    ");

                    $this->bajaLogicaCliente();
                }
            }
        }

        function pintaTablaClientesBaja(string $dni) {
            if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador" ||
                $this->permisoUsuario($_COOKIE['dni']) == "Vendedor") {
                
                if (empty($dni))
                    $datos = $this->listarClientesBaja();

                else
                    $datos = $this->listarClientesBaja($dni);

                if (!$datos) {
                    if (!$this->listarNumeroClientesBaja())
                        return $this->mensaje("No existen clientes de baja.", "secondary");
                    if (!$this->existeDNI($dni))
                        return $this->mensaje("Este cliente no existe.", "secondary");
                    else
                        return;
                }

                if (!isset($_GET['modificarCliente'])) {
                    print("
                    <h1 class='h1 display-6 fs-2 fw-bold text-center'>Clientes de baja</h1>
                        <div class='container'>
                            <table class='table table-bordered border-dark table-striped display-6 fs-5 mt-4'>
                                <tr>
                                    <th>DNI</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Fecha de nacimiento</th>
                                    <th>Preferencias</th>
                                    <th>Fecha de baja</th>
                                    <th>Restaurar</th>
                                </tr>");

                                if (empty($dni)) {
                                    for ($i = 0; $i < $this->listarNumeroClientesBaja(); $i++) {
                                        print("
                                            <tr>
                                                <td>".$datos[$i]['dni']."</td>
                                                <td>".$datos[$i]['nombre']."</td>
                                                <td>".$datos[$i]['apellidos']."</td>
                                                <td>".$datos[$i]['direccion']."</td>
                                                <td>".$datos[$i]['telefono']."</td>
                                                <td>".$datos[$i]['email']."</td>");

                                                print("<td>");
                                                    if ($datos[$i]['fecha_nac'] != "0000-00-00")
                                                        print($this->cambiarFormatoFecha($datos[$i]['fecha_nac']));
                                                print("</td>");

                                                print("
                                                <td>".$datos[$i]['preferencias']."</td>
                                                <td>".$this->cambiarFormatoFecha($datos[$i]['fecha_baja'])."</td>
                                                <td>
                                                    <form method='POST'>
                                                        <input type='hidden' value='".$datos[$i]['dni']."' name='dni' />
                                                        <button class='btn btn-success' name='restaurar' onclick=\"return confirm('¿Seguro que quieres restaurar a este cliente?')\">
                                                            <img src='../pictures/restaurar.png' class='boton' />
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        ");
                                    }
                                }
                                else {
                                    print("
                                        <tr>
                                        <td>".$datos['dni']."</td>
                                        <td>".$datos['nombre']."</td>
                                        <td>".$datos['apellidos']."</td>
                                        <td>".$datos['direccion']."</td>
                                        <td>".$datos['telefono']."</td>
                                        <td>".$datos['email']."</td>");

                                        if ($datos['fecha_nac'] != "0000-00-00")
                                            print("<td>".$this->cambiarFormatoFecha($datos['fecha_nac'])."</td>");

                                            print("
                                            <td>".$datos['preferencias']."</td>
                                            <td>".$this->cambiarFormatoFecha($datos['fecha_baja'])."</td>
                                            <td>
                                                <form method='POST'>
                                                    <input type='hidden' value='".$datos['dni']."' name='dni' />
                                                    <button class='btn btn-success' name='restaurar' onclick=\"return confirm('¿Seguro que quieres restaurar a este cliente?')\">
                                                        <img src='../pictures/restaurar.png' class='boton' />
                                                    </button>
                                                </form>
                                        </td>
                                    </tr>
                                    ");
                                }
                                print("
                            </table>
                        </div>
                    ");

                    $this->restaurarCliente();
                }
            }
        }

        function formModificarCliente() {
            if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador" ||
                $this->permisoUsuario($_COOKIE['dni']) == "Vendedor") {

                    $datos = $this->listarClientesAlta($_GET['modificarCliente']);
                    
                    print("
                        <div class='container'>
                            <form method='POST' action='?clientes.php'>
                                <table class='table table-bordered border-dark table-striped display-6 fs-5 mt-5'>
                                    <tr>
                                        <th>DNI</th>
                                        <th>Nombre</th>
                                        <th>Apellidos</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th>Email</th>
                                        <th>Fecha de nacimiento</th>
                                        <th>Preferencias</th>
                                        <th>Acción</th>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input type='hidden' value='".$datos['id_cliente']."' name='id_cliente' />
                                            <input type='text' value='".$datos['dni']."' name='dni' class='form-control' />
                                        </td>
                                        <td>
                                            <input type='text' value='".$datos['nombre']."' name='nombre'
                                            class='form-control' />
                                        </td>
                                        <td>
                                            <input type='text' value='".$datos['apellidos']."' name='apellidos'
                                            class='form-control' />
                                        </td>
                                        <td>
                                            <input type='text' value='".$datos['direccion']."' name='direccion'
                                            class='form-control' />
                                        </td>
                                        <td>
                                            <input type='tel' value='".$datos['telefono']."' name='telefono'
                                            class='form-control' size='11' />
                                        </td>
                                        <td>
                                            <input type='email' value='".$datos['email']."' name='email'
                                            class='form-control' />
                                        </td>
                                        <td>
                                            <input type='date' value='".$datos['fecha_nac']."'
                                            name='fecha_nac' class='form-control' />
                                        </td>
                                        <td>
                                            <input type='text' value='".$datos['preferencias']."' name='preferencias' class='form-control' />
                                        </td>
                                        <td>
                                            <button type='submit' class='btn btn-outline-success' name='guardarCliente'>
                                                Guardar
                                            </button>
                                            <a href='administrador.php?clientes&tablaClientes'>
                                                <input type='button' class='btn btn-outline-danger mt-1' value='Cancelar' />
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    ");
            }
        }


        // Usuarios

        function pintaPermisos($permiso = false) {
            print("<select class='form-select' name='permiso'>");

            if ($permiso)
                print("<option selected>".$permiso."</option>");

            print("
                <option>Administrador</option>
                <option>Vendedor</option>
            ");

            print("</select>");
        }

        function pintaTablaUsuariosAlta() {
            if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                
                $datos = $this->listarUsuarios(false);

                if (!$datos) {
                    if (!$this->listarNumeroUsuarios(false))
                        return $this->mensaje("No existen usuarios de alta.", "secondary");
                    else
                        return;
                }
    
                if (!isset($_POST['modificarUsuario'])) {
                    print("
                    <h1 class='h1 display-6 fs-2 fw-bold text-center'>Usuarios de alta</h1>
                        <div class='container'>
                            <table class='table table-bordered border-dark table-striped display-6 fs-5 mt-4'>
                                <tr>
                                    <th>DNI</th>
                                    <th>Iniciales</th>
                                    <th>Email</th>
                                    <th>Permiso</th>
                                    <th>Fecha de alta</th>
                                    <th>Acción</th>
                                </tr>");

                                    for ($i = 0; $i < $this->listarNumeroUsuarios(false); $i++) {
                                        print("
                                            <tr>
                                                <td>".$datos[$i]['dni']."</td>
                                                <td>".$datos[$i]['iniciales']."</td>
                                                <td>".$datos[$i]['email']."</td>
                                                <td>".$datos[$i]['permiso']."</td>

                                                <td>".$this->cambiarFormatoFecha($datos[$i]['fecha_alta'])."</td>

                                                <td>
                                                    <form method='POST'>
                                                        <input type='hidden' value='".$datos[$i]['dni']."' name='dni' />
                                                        <button class='btn btn-warning' name='modificarUsuario'>
                                                            <img src='../pictures/editar.png' class='boton' />
                                                        </button>

                                                        <button class='btn btn-danger' name='bajaUsuario' onclick=\"return confirm('¿Seguro que quieres dar de baja a este usuario?')\">
                                                            <img src='../pictures/x.png' class='boton' />
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        ");
                                    }
                                print("
                            </table>
                        </div>
                    ");
                }
            }
        }

        function formModificarUsuario() {
            if (isset($_POST['modificarUsuario'])) {
                if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador" ) {

                    $datos = $this->listarUsuarioAlta($_POST['dni']);
                    
                    ?>

                            <div class='container'>
                                <form method='POST'>
                                    <input type="hidden" value="<?php print($datos['id_usuario']); ?>"
                                    name="id_usuario" />
                                    <table class='table table-bordered border-dark table-striped display-6 fs-5 mt-5'>
                                        <tr>
                                            <th>DNI</th>
                                            <th>Iniciales</th>
                                            <th>Email</th>
                                            <th>Permiso</th>
                                            <th>Acción</th>
                                        </tr>

                                        <tr>
                                            <td>
                                                <input type='text' value='<?php print($datos['dni']); ?>' name='dni'
                                                class='form-control' />
                                            </td>
                                            <td>
                                                <input type='text' value='<?php print($datos['iniciales']); ?>' name='iniciales'
                                                class='form-control' />
                                            </td>
                                            <td>
                                                <input type='email' value='<?php print($datos['email']); ?>' name='email'
                                                class='form-control' />
                                            </td>
                                            <td>
                                                <?php $this->pintaPermisos($datos['permiso']); ?>
                                            </td>
                                            <td>
                                                <button type='submit' class='btn btn-outline-success' name='guardarUsuario'>
                                                    Guardar
                                                </button>
                                                <a href='usuarios_alta.php'>
                                                    <input type='button' class='btn btn-outline-danger' value='Cancelar' />
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                    <?php
                }
            }
        }

        function pintaTablaUsuariosBaja() {
            if ($this->permisoUsuario($_COOKIE['dni']) == "Administrador") {
                
                $datos = $this->listarUsuarios(true);

                if (!$datos) {
                    if (!$this->listarNumeroUsuarios(true))
                        return $this->mensaje("No existen usuarios de baja.", "secondary");
                    else
                        return;
                }
    
                    print("
                    <h1 class='h1 display-6 fs-2 fw-bold text-center'>Usuarios de baja</h1>
                        <div class='container'>
                            <table class='table table-bordered border-dark table-striped display-6 fs-5 mt-3'>
                                <tr>
                                    <th>DNI</th>
                                    <th>Iniciales</th>
                                    <th>Email</th>
                                    <th>Permiso</th>
                                    <th>Fecha de alta</th>
                                    <th>Acción</th>
                                </tr>");

                                    for ($i = 0; $i < $this->listarNumeroUsuarios(true); $i++) {
                                        print("
                                            <tr>
                                                <td>".$datos[$i]['dni']."</td>
                                                <td>".$datos[$i]['iniciales']."</td>
                                                <td>".$datos[$i]['email']."</td>
                                                <td>".$datos[$i]['permiso']."</td>

                                                <td>".$this->cambiarFormatoFecha($datos[$i]['fecha_alta'])."</td>

                                                <td>
                                                    <form method='POST'>
                                                        <input type='hidden' value='".$datos[$i]['dni']."' name='dni' />
                                                        <button class='btn btn-success' name='restaurarUsuario' onclick=\"return confirm('¿Seguro que quieres restaurar a este usuario?')\">
                                                            <img src='../pictures/restaurar.png' class='boton' />
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        ");
                                    }
                                print("
                            </table>
                        </div>
                    ");
            }

            else
                $this->mensaje("Permiso denegado", "secondary");
        }


        // Panel de control
        function formCliente() {
            $datos = $this->listarClientesAlta($_COOKIE['dni']);
            ?>

            <h1 class="h1 display-3 mb-5">Panel de control</h1>

            <h2 class="h2 display-6 fst-italic mb-5">¡Hola, <?php print($this->nombreLogeado()); ?>!</h2>

            <form method="POST" class="display-6 fs-4">
                <input type="hidden" name="panel" />
                <input type="hidden" name="id_cliente" value="<?php print($datos['id_cliente']); ?>" />
                <div class="row justify-content-center">
                    <div class="col-auto">
                        DNI:
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" size="10" name="dni"
                        value="<?php print($datos['dni']); ?>" required /><br>
                    </div>
                    <div class="col-auto">
                        Contraseña:
                    </div>
                    <div class="col-auto">
                        <input type="password" class="form-control" size="10" name="clave"
                        value="<?php print($datos['clave']); ?>" required /><br>
                    </div>
                    <div class="col-auto">
                        Nombre:
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" size="10" name="nombre"
                        value="<?php print($datos['nombre']); ?>" required /><br>
                    </div>
                    <div class="col-auto">
                        Apellidos:
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" size="20" name="apellidos"
                        value="<?php print($datos['apellidos']); ?>" required /><br>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-auto">
                        Dirección:
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" size="20" name="direccion"
                        value="<?php print($datos['direccion']); ?>" required /><br>
                    </div>
                    <div class="col-auto">
                        Teléfono:
                    </div>
                    <div class="col-auto">
                        <input type="tel" class="form-control" size="10" name="telefono"
                        value="<?php print($datos['telefono']); ?>" required /><br>
                    </div>
                    <div class="col-auto">
                        Email:
                    </div>
                    <div class="col-auto">
                        <input type="email" class="form-control" size="20" name="email"
                        value="<?php print($datos['email']); ?>" required /><br>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-auto">
                        Fecha de nacimiento:
                    </div>
                    <div class="col-auto">
                        <input type="date" class="form-control" size="10" name="fecha_nac"
                        value="<?php print($datos['fecha_nac']); ?>" /><br>
                    </div>
                </div>
                <div class="row justify-content-center align-items-center">
                    <div class="col-auto">
                        Preferencias:
                    </div>
                    <div class="col-auto">
                        <textarea class="form-control" cols="75" name="preferencias"><?php print($datos['preferencias']); ?></textarea>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-outline-success" type="submit" name="guardarCliente">Guardar</button>
                    </div>
                </div>
            </form>
            <?php
        }

        function formUsuario() {
            $datos = $this->listarUsuarioAlta($_COOKIE['dni']);
            ?>

            <h1 class="h1 display-3 mb-5">Panel de control</h1>

            <h2 class="h2 display-6 fst-italic mb-5">¡Hola, <?php print($this->nombreLogeado()); ?>!</h2>

            <form method="POST" class="display-6 fs-4">
                <input type="hidden" name="panel" />
                <input type="hidden" name="id_usuario" value="<?php print($datos['id_usuario']); ?>" />
                <div class="row justify-content-center">
                    <div class="col-auto">
                        DNI:
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" size="10" name="dni"
                        value="<?php print($datos['dni']); ?>" required /><br>
                    </div>

                    <div class="col-auto">
                        Iniciales:
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" size="10" name="iniciales"
                        value="<?php print($datos['iniciales']); ?>" required /><br>
                    </div>

                    <div class="col-auto">
                        Email:
                    </div>
                    <div class="col-auto">
                        <input type="email" class="form-control" size="20" name="email"
                        value="<?php print($datos['email']); ?>" required /><br>
                    </div>
                </div>
                
                <div class="row justify-content-center">
                    <div class="col-auto">
                        Contraseña:
                    </div>
                    <div class="col-auto">
                        <input type="password" class="form-control" size="10" name="clave"
                        value="<?php print($datos['clave']); ?>" required /><br>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-outline-success" type="submit" name="guardarUsuario">Guardar</button>
                    </div>
                </div>
            </form>
            <?php
        }
    }
?>