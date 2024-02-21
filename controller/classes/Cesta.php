<?php
    require_once("Stock.php");

    class Cesta extends Stock {
        function pintaCesta() {
            if ($this->comprobarCliente($_COOKIE['dni'])) {

                if ($this->contarProductosCesta() > 0) {
                    print("
                        <h2 class='h2 display-6 fst-italic text-center mt-5 mb-3'>
                            Cesta de ".$this->nombreLogeado()."
                        </h2>

                        <form method='POST'>
                            <div class='row justify-content-center'>
                                <div class='col-auto'>
                                    <button type='submit' class='btn btn-outline-danger' name='vaciarCesta'
                                    onclick=\"return confirm('¿Seguro que quieres vaciar tu cesta?')\">
                                        Vaciar cesta
                                    </button>
                                </div>
                            </div>
                        </form>
                    ");
                }
                else
                    $this->mensaje("Tu cesta está vacía", "secondary");
                
                $datos = $this->listarProductosCesta();

                $categoria = array("rojo", "negro", "verde", "cafe", "chocolate", "flores");
                $categoriaCompleta = array("Té rojo", "Té negro", "Té verde", "Café",
                "Chocolate", "Flores");


    
                if ($this->contarProductosCesta() > 1) {

                    for ($i = 0; $i < $this->contarProductosCesta(); $i++) {

                        for ($j = 0; $j < count($categoriaCompleta); $j++) {
                            if ($categoriaCompleta[$j] == $datos[$i]['categoria'])
                                $eleccion = $categoria[$j];
                        } ?>

                        <form method="POST">
                            <div class='row justify-content-center align-items-center mt-5 mb-5'>
                                <div class='col-2'>
                                    <img src='../pictures/<?php print($eleccion); ?>/<?php print($datos[$i]['id_producto']); ?>.png'
                                    class='producto' />
                                </div>
                                <div class='col-md-4 me-5'>
                                    <span class='display-6 fs-4'>
                                        <b>Nombre</b>: <?php print($datos[$i]['nombre']); ?>
                                    </span>
                                    <form method="POST">
                                        <input type="hidden" name='id_producto'
                                        value="<?php print($datos[$i]['id_producto']); ?>" />

                                        <div class="row align-items-center mt-5">
                                            <div class="col-md-2 me-5">
                                                <button type="submit" class="btn btn-outline-danger"
                                                name="bajaProductoCesta">
                                                    Eliminar
                                                </button>
                                            </div>
                                            <div class="col-md-2 display-6 fs-5 me-2">
                                                Cantidad:
                                            </div>
                                            <div class="col-md-3 me-3">
                                                <input type='number' class='form-control cuadro_cantidad' min='0'
                                                value='<?php print($datos[$i]['cantidad']); ?>'
                                                name='cantidad' />
                                            </div>
                                            <div class="col-md-3">
                                                <button type='submit' class='form-control btn btn-outline-secondary'
                                                name='modificarProductoCesta'>
                                                    Cambiar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class='col-2 display-6 fs-4'>
                                    <span class="precio2"><?php print($datos[$i]['pvp']); ?></span> €/Ud.
                                </div>
                            </div>
                        </form>
                        <?php
                    }

                    ?>
                        <h2 class='h2 fw-bold text-center' id="total2"></h2> 

                        <script type="text/javascript">
                            let totales = 0;
                            let cuadros_cantidad = document.querySelectorAll('.cuadro_cantidad');
                            let precios = document.querySelectorAll('.precio2');

                            for (let i = 0; i < cuadros_cantidad.length; i++){
                                let cantidad = parseInt(cuadros_cantidad[i].value);
                                let precio = parseFloat(precios[i].textContent);
                                totales += cantidad * precio;
                            }

                            document.querySelector('#total2').innerHTML = 'Total cesta<br>(IVA incluido): ' + totales.toFixed(2) + '€';
                        </script>
                    <?php
                }

                else if ($this->contarProductosCesta() == 1) {
                
                    for ($i = 0; $i < count($categoriaCompleta); $i++) {
                        if ($categoriaCompleta[$i] == $datos[0]['categoria'])
                            $eleccion = $categoria[$i];
                    } ?>

                    <form method="POST">
                        <div class='row justify-content-center align-items-center mt-5 mb-5'>
                            <div class='col-2'>
                                <img src='../pictures/<?php print($eleccion); ?>/<?php print($datos[0]['id_producto']); ?>.png'
                                class='producto' />
                            </div>
                            <div class='col-md-4 me-5'>
                                <span class='display-6 fs-4'>
                                    <b>Nombre</b>: <?php print($datos[0]['nombre']); ?>
                                </span>
                                <form method="POST">
                                    <input type="hidden" name='id_producto'
                                    value="<?php print($datos[0]['id_producto']); ?>" />

                                    <div class="row align-items-center mt-5">
                                        <div class="col-md-2 me-5">
                                            <button type="submit" class="btn btn-outline-danger"
                                            name="bajaProductoCesta">
                                                Eliminar
                                            </button>
                                        </div>
                                        <div class="col-md-2 display-6 fs-5 me-2">
                                            Cantidad:
                                        </div>
                                        <div class="col-md-3 me-3">
                                            <input type='number' class='form-control' id="cuadro_cantidad" min='0'
                                            value='<?php print($datos[0]['cantidad']); ?>'
                                            name='cantidad' />
                                        </div>
                                        <div class="col-md-3">
                                            <button type='submit' class='form-control btn btn-outline-secondary'
                                            name='modificarProductoCesta'>
                                                Cambiar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class='col-2 display-6 fs-4'>
                            <span id="precio"><?php print($datos[0]['pvp']); ?></span> €/Ud.
                            </div>
                        </div>
                    </form>

                    <h2 class='h2 fw-bold text-center' id="total"></h2>

                    <script type="text/javascript">
                        let cantidad = parseInt(document.getElementById('cuadro_cantidad').value);
                        let precio = parseFloat(document.getElementById('precio').textContent);
                        let total = cantidad * precio;
                        document.getElementById('total').innerHTML = "Total cesta<br>(IVA incluido): " + total.toFixed(2) + " €";
                    </script>
                    <?php
                }

                if ($this->contarProductosCesta() > 0) {
                    print("
                        <div class='container text-center'>
                            <a href='pagar.php' class='text-center text-success'>
                                <button class='btn btn-success mt-5'>Pagar</button>
                            </a>
                        </div>
                    ");
                }
            }
            else
                $this->mensaje("Inicia sesión para ver tu cesta de productos", "secondary");
        }
    }
?>