<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-auto">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 display-6 fs-4">
                            <li class="nav-item">
                                <a class="nav-link active" href="productos.php?cat=rojo">Té rojo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="productos.php?cat=negro">Té negro</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="productos.php?cat=verde">Té verde</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="productos.php?cat=cafe">Café</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="productos.php?cat=chocolate">Chocolate</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="productos.php?cat=flores">Flores</a>
                            </li>

                            <?php
                                require_once("../controller/classes/Consultas.php");
                                $instancia = new Consultas();

                                if (isset($_COOKIE['dni']))
                                    $dni = $_COOKIE['dni'];
                                else
                                    $dni = "";

                                    // <img src='../pictures/panel.png' class='boton' />
                                    if ($instancia->comprobarLogin()) {
                                        print("
                                            <li class='nav-item'>
                                                <a class='nav-link active fw-bold' href='panel.php'>
                                                    <button class='btn btn-info'>
                                                        Panel
                                                    </button>
                                                </a>
                                            </li>
                                        ");
                                    }

                                // <img src='../pictures/carro.png' class='boton' />
                                if ($instancia->comprobarCliente($dni)) {
                                    print("
                                        <li class='nav-item'>
                                            <a class='nav-link active fw-bold' href='cesta.php'>
                                                <button type='button' class='btn btn-warning position-relative'>
                                                    Cesta");

                                                    if ($instancia->contarProductosCesta() > 0) {
                                                        print("
                                                            <span class='position-absolute top-0 start-100 translate-middle
                                                            badge rounded-pill bg-danger'>
                                                                ".$instancia->contarProductosCesta()."
                                                                <span class='visually-hidden'>unread messages</span>
                                                            </span>
                                                        ");
                                                    }
                                                    print("
                                                </button>
                                            </a>
                                        </li>
                                    ");
                                }

                                if (!empty($instancia->permisoUsuario($dni))) {

                                    if ($instancia->permisoUsuario($dni) == "Vendedor") {
                                        print("
                                            <li class='nav-item'>
                                                <a class='nav-link active fw-bold' href='vendedor.php'>
                                                    <button class='btn btn-warning'>Administración</button>
                                                </a>
                                            </li>
                                        ");
                                    }
                                    if ($instancia->permisoUsuario($dni) == "Administrador") {
                                        print("
                                            <li class='nav-item'>
                                                <a class='nav-link active fw-bold' href='administrador.php'>
                                                    <button class='btn btn-danger'>Administración</button>
                                                </a>
                                            </li>
                                        ");
                                    }
                                }

                            if (!$instancia->comprobarLogin()) { ?>
                                <li class="nav-item">
                                    <a class="ms-3" href="registro.php">
                                        <button class="btn btn-outline-secondary mt-2">Registrarse</button>
                                    </a>
                                </li>
                            <?php } ?>

                            <div class="ms-3 mt-2">
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                    <?php
                                        require_once("../controller/classes/Seguridad.php");
                                        $instancia = new Seguridad();
                                        print("Entrar/Salir");
                                    ?>
                                </button>
                            </div>

                            <?php
                                if ($instancia->comprobarLogin()) {
                                    if (isset($_COOKIE['nombre']))
                                        $nombre = $_COOKIE['nombre'];
                                    else if (isset($_COOKIE['iniciales']))
                                        $nombre = $_COOKIE['iniciales'];
                                
                                    print("
                                        <li class='nav-item fw-bold ms-5 mt-2'>
                                            ".$nombre."
                                        </li>
                                    ");
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        
        <div class="col-auto">
            <div class="collapse" id="collapseExample">
                <?php
                    print("<form method='POST'>");
                        if (!$instancia->comprobarLogin()) { ?>
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipo" id="cliente" value="Cliente">
                                            <label class="form-check-label" for="cliente">
                                                Cliente
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipo" id="staff" value="Staff">
                                            <label class="form-check-label" for="staff">
                                                Staff
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <input type="text" class="form-control mb-2 d-none" name="nombre" placeholder="Nombre" id="cuadro_nombre"  />
                                <input type="text" class="form-control mb-2 d-none" name="iniciales"
                                id="cuadro_iniciales" placeholder="Iniciales" />

                                <input type="text" class="form-control mb-2" name="dni" placeholder="DNI" required />
                                <input type="password" class="form-control mb-2" name="clave" placeholder="Contraseña" required />

                                <button type="submit" class="btn btn-outline-success" name="login">Entrar</button>
                            <?php
                        }
                        else { ?>
                                <button type="submit" class="btn btn-outline-danger mt-3" name="logout">Salir</button>
                            <?php
                        }
                    print("</form>");

                    $instancia->iniciarSesion();
                    $instancia->cerrarSesion();
                ?>

                <script type="text/javascript">
                    window.addEventListener("load", function() {
                        let cliente = document.getElementById("cliente");
                        let staff = document.getElementById("staff");

                        const cuadro_nombre = document.getElementById("cuadro_nombre");
                        const cuadro_iniciales = document.getElementById("cuadro_iniciales");

                        if (cuadro_nombre) {
                            cliente.addEventListener("click", function() {
                                cuadro_nombre.classList.remove("d-none");
                                cuadro_iniciales.classList.add("d-none");
                            });

                            staff.addEventListener("click", function() {
                                cuadro_iniciales.classList.remove("d-none");
                                cuadro_nombre.classList.add("d-none");
                            });
                        }
                    });
                </script>
            </div>
        </div>
    </div>
</div>