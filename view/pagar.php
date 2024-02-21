<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Pagar / Floornaturate</title>
        <?php require("../controller/head.php"); ?>
    </head>

    <body class="bg-light">
        <?php
            require_once("header.php");
            require_once("menu.php");
            require_once("../controller/classes/Cesta.php");
            $instancia = new Cesta();
        ?>
        <div class="container text-center mt-4">
            <?php
                if (isset($_GET['pagado'])) {
                    $instancia->vaciarCesta();
                    $instancia->mensaje("Pedido tramitado.<br>Tu cesta se ha vaciado.", "success");
                    ?>
                        <h1 class="h1 display-3 mb-5">Próximas mejoras</h1>
                        <ul class="display-6 fs-4 text-start">
                            <h2 class="fw-bold">Formularios:</h2>
                                <li>
                                    En el panel de control y registro de usuarios añadir un botón para ver/ocultar
                                    la contraseña.
                                    <img src="../pictures/logos/js.png" class="icono" />
                                </li>
                                <li>
                                    Botones submit desactivados hasta que se cubran los datos obligatorios de los
                                    formularios.
                                    <img src="../pictures/logos/js.png" class="icono" />
                                </li>
                                <li>Añadir validación en servidor.
                                    <img src="../pictures/logos/php.png" class="icono" />
                                </li>
                                <li>Que nadie pueda cambiar su DNI en su panel de control; deberán contactar con el
                                    administrador.
                                    <img src="../pictures/logos/php.png" class="icono" />
                                </li>
                                <li>Añadir página con formulario para cuando un usuario olvide su contraseña.
                                    <img src="../pictures/logos/html.png" class="icono" />
                                    <img src="../pictures/logos/php.png" class="icono" />
                                </li>
                        </ul>
                        <br>                        
                        <ul class="display-6 fs-4 text-start">
                            <h2 class="fw-bold">Bases de datos:</h2>
                                <li>
                                    Que las claves se almacenen encriptadas en la BD.
                                    <img src="../pictures/logos/php.png" class="icono" />
                                </li>  
                                <li>
                                    Añadir un token a la BD para cada usuario que cambie con cada login y logout.
                                    <img src="../pictures/logos/php.png" class="icono" />
                                </li> 
                        </ul>
                        <br>
                        <ul class="display-6 fs-4 text-start">
                            <h2 class="fw-bold">Scroll infinito:</h2>
                                <li>
                                    Que todas las tablas muestren sus filas de 10 en 10.
                                    <img src="../pictures/logos/php.png" class="icono" />
                                </li>  
                        </ul>
                        <br>
                        <ul class="display-6 fs-4 text-start">
                            <h2 class="fw-bold">Varios:</h2>
                                <li>
                                    Diseñar un sistema de subida de imágenes al servidor para incluir nuevos productos.
                                    <img src="../pictures/logos/php.png" class="icono" />
                                </li> 
                                <li>
                                    Añadir un aviso de cookies.
                                    <img src="../pictures/logos/js.png" class="icono" />
                                </li> 
                                <li>
                                    Añadir un botón de volver arriba.
                                    <img src="../pictures/logos/js.png" class="icono" />
                                </li> 
                                <li>
                                    Añadir un botón de modo oscuro.
                                    <img src="../pictures/logos/js.png" class="icono" />  
                                </li> 
                                <li>
                                    Solucionar los problemas responsive.
                                    <img src="../pictures/logos/html.png" class="icono" />
                                </li>  
                                <li>
                                    Añadir un modo de mantenimiento.
                                    <img src="../pictures/logos/php.png" class="icono" />
                                </li> 
                                <li>
                                    Solucionar el problema de la barra desplazamiento horizontal.
                                    <img src="../pictures/logos/html.png" class="icono" />
                                </li> 
                                <li>
                                    Cambiar el texto de botones de secciones privadas por imágenes.
                                    <img src="../pictures/logos/html.png" class="icono" />
                                </li>  
                                <li>
                                    Conseguir que las páginas que ejecutan una consulta se actualicen solas.
                                    <img src="../pictures/logos/php.png" class="icono" />
                                </li> 
                                <li>
                                    Mejorar el login: moverlo del menu al header y que se entre con apodo y clave.
                                    <img src="../pictures/logos/php.png" class="icono" />
                                </li> 
                                <li>
                                    Mejorar el login: moverlo del menu al header y que se entre con apodo y clave.  
                                    <img src="../pictures/logos/php.png" class="icono" /> 
                                </li> 
                                <li>
                                    Hacer que si cambias tu dato del cual depende una cookie, se cierre la sesión.
                                    <img src="../pictures/logos/php.png" class="icono" />   
                                </li> 
                                <li>
                                    Hacer que en las tablas de usuario no puedas eliminarte a ti mismo.  
                                    <img src="../pictures/logos/php.png" class="icono" /> 
                                </li> 
                                <li>
                                    Hacer que las cookies sean apodo y token.
                                    <img src="../pictures/logos/php.png" class="icono" />
                                </li> 
                        </ul>

                    <?php
                }

                else {
                    ?>
                        <h1 class="h1 display-3 mb-5">Tramitar pedido</h1>
                        <h2 class="h2 display-6 fst-italic mb-5">Selecciona un método de pago:</h2>

                        <form action="pagar.php?pagado" method="POST">
                            <?php
                                print("
                                    <div class='form-check'>
                                        <div class='row justify-content-center align-items-center'>
                                            <div class='col-auto'>
                                                <input class='form-check-input' type='radio' name='flexRadioDefault'
                                                id='flexRadioDefault1' required />
                                            </div>
                                            <div class='col-auto'>
                                                <label for='flexRadioDefault1'>
                                                    <img src='../pictures/pago/mastercard.png' class='mastercard' />
                                                </label>
                                            </div>
                                            <div class='col-auto'>
                                                <label for='flexRadioDefault1'>
                                                    <img src='../pictures/pago/visa.png' class='pago' />
                                                </label>
                                            </div>
                                        </div>");

                                        $metodos = array("paypal.png", "bizum.png");

                                        for ($i = 0; $i < count($metodos); $i++) {
                                            print("
                                                <div class='row justify-content-center align-items-center'>
                                                    <div class='col-auto'>
                                                        <input class='form-check-input' type='radio' name='flexRadioDefault'
                                                        id='flexRadioDefault".($i+2)."' />
                                                    </div>
                                                    <div class='col-auto'>
                                                        <label for='flexRadioDefault".($i+2)."'>
                                                            <img src='../pictures/pago/".$metodos[$i]."' class='pago' />
                                                        </label>
                                                    </div>
                                                </div>
                                            ");
                                        }

                                        print("
                                    </div>
                                ");
                            ?>

                            <button class="btn btn-success mt-4" type="submit" name="vaciarCesta">Tramitar</button>
                        </form>
                    <?php
                }
            ?>
        </div>

        <?php require_once("footer.php"); ?>
    </body>
</html>
<?php ob_end_flush(); ?>