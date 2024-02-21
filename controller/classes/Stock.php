<?php
    require_once("Consultas.php");

    class Stock extends Consultas {
        protected $db;

        function __construct() {
            $conn = new conexion();
            $this->db = $conn->get_ConexionBD();
        }

        function pintaProductos(string $categoria) {
            try {
                $datos = $this->listarStockAlta($categoria);

					print("
						<article class='mt-4 mb-4'>
					");

                    for ($i = 0; $i < $this->listarNumeroProductosAlta($categoria); $i++) {
                        print("
                            <div class='row justify-content-center align-items-center mb-4'>
                                <div class='col-2'>
                                    <img src='../pictures/".$_GET['cat']."/".$datos[$i]['id_producto'].".png' class='producto' />
                                </div>
                                <div class='col-4 display-6 fs-4 me-5'>
                                    <b>Nombre</b>: ". $datos[$i]['nombre'] ." <br>
                                    <b>Precio</b>: " . $datos[$i]['pvp'] . " € <br>
                                    <div class='col-1'>
                                        <a href='producto.php?cat=".$_GET['cat']."&id=".$datos[$i]['id_producto']."'>
                                            <button class='btn btn-outline-secondary'>
                                                +Info
                                            </button>
                                        </a>
                                    </div>
                                </div>");

                                if ($this->comprobarLogin() && $this->comprobarCliente($_COOKIE['dni'])) {
                                    print("
                                        <div class='col-2'>");

                                            if (!$this->comprobarExistenteCesta($datos[$i]['id_producto'])) {
                                                print("
                                                    <form method='POST'>
                                                        <input type='hidden' value='".$datos[$i]['id_producto']."'
                                                        name='id_producto' />
                                                        <div class='row'>
                                                            <div class='col-6'>
                                                                <input type='number' class='form-control' min='0' name='cantidad' />
                                                            </div>
                                                            <div class='col'>
                                                                <button type='submit' class='btn btn-warning' name='altaCesta'>
                                                                    <img src='../pictures/carro.png' class='boton' />
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                ");
                                            }
                                            else
                                                print("<span class='badge text-bg-secondary'>Añadido</span>");
                                            
                                            print("
                                        </div>
                                    ");
                                }
                                print("
                            </div>
                        ");
                    }

                    print("
						</article>
					");
            } catch (Exception $e) {
                exit("Error: " . $e->getMessage());
            } finally {
                $this->db = null;
            }

            /*
            
            if ($this->permisoUsuario($_COOKIE['usuario']) == "Administrador")
                print("columna del coste");

            */
        }

        function pintaDatosProducto(int $id_producto) {
            try {
                $this->__construct();
                $datos = $this->listarDatosProducto($id_producto);
                
                print("
                    <div class='row align-items-center'>
                        <div class='col'>
                            <img src='../pictures/".$_GET['cat']."/".$_GET['id'].".png' style='width: 500px;' />
                        </div>
                        <div class='col display-6 fs-4'>
                            <h1 class='h1 display-6 text-center fst-italic mb-5'>".$datos['nombre']."</h1>
                            <b>Descripción</b>: ".$datos['descripcion']."<br><br>
                            <b>Precio</b>: ".$datos['pvp']." €<br><br>");

                            if ($this->comprobarLogin() &&
                                $this->comprobarCliente($_COOKIE['dni']) &&
                                !$this->comprobarExistenteCesta($datos['id_producto'])) {
                                print("
                                    <form method='POST'>
                                        <input type='hidden' value='".$datos['id_producto']."' name='id_producto' />
                                        <div class='row align-items-center'>
                                            <div class='col-2'>
                                                <input type='number' name='cantidad' class='form-control' min='0' />
                                            </div>
                                            <div class='col'>
                                                <button type='submit' class='btn btn-warning' name='altaCesta'>
                                                    Añadir a la cesta
                                                </button>
                                            </div>
                                        </div>
                                ");
                            }
                            else if ($this->comprobarLogin() &&
                                    $this->comprobarCliente($_COOKIE['dni']) &&
                                    $this->comprobarExistenteCesta($datos['id_producto']))
                                print("<span class='badge text-bg-secondary'>Producto añadido a la cesta</span>");

                            print("
                        </div>
                    </div>
                ");
            } catch (Exception $e) {
                exit("Error: " . $e->getMessage());
            } finally {
                $this->db = null;
            }
        }
    }
?>