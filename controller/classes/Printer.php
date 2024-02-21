<?php
    require_once('Administrador.php');

    class Printer extends Administrador {

        function galeriaIndex() {
            $numeroImagenes = count($this->listarArchivosRuta("../pictures/tienda/"));

            print("
                <div id='carouselExampleIndicators' class='carousel slide mt-5 mb-5' data-bs-ride='true'>
                    <div class='carousel-indicators'>
                        <button type='button' data-bs-target='#carouselExampleIndicators'
                        data-bs-slide-to='0' class='active bg-dark' aria-current='true'
                        aria-label='Slide 1'></button>");

                        for ($i = 1; $i < $numeroImagenes; $i++) {
                            print("
                                <button type='button' data-bs-target='#carouselExampleIndicators'
                                data-bs-slide-to='".$i."' class='bg-dark' aria-label='Slide 2'></button>
                            ");
                        }
                        print("
                    </div>

                    <div class='carousel-inner'>
                        <div class='carousel-item active'>
                            <img src='../pictures/tienda/"
                            .$this->listarArchivosRuta("../pictures/tienda/")[0].
                            "'class='d-block w-100'>
                        </div>");

                        for ($i = 1; $i < $numeroImagenes; $i++) {
                            print("
                                <div class='carousel-item'>
                                    <img src='../pictures/tienda/"
                                    .$this->listarArchivosRuta("../pictures/tienda/")[$i].
                                    "'class='d-block w-100'>
                                </div>
                            ");
                        }
                        
                        print("
                    </div>

                    <button class='carousel-control-prev' type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide='prev'>
                        <span class='carousel-control-prev-icon bg-dark' aria-hidden='true'></span>
                        <span class='visually-hidden'>Previous</span>
                    </button>

                    <button class='carousel-control-next' type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide='next'>
                        <span class='carousel-control-next-icon bg-dark' aria-hidden='true'></span>
                        <span class='visually-hidden'>Next</span>
                    </button>
                </div>
            ");
        }

        function frasesComunicacion() {
            $frases = array(
                '¡No te olvides del Día de la Madre!',
                'Packs especiales',
                'Si te vas a casar, te esperamos.',
                'Cuenta con nosotras para las comuniones',
                'Ya es Halloween en Lola&Laura',
                '¡Feliz Navidad!'
            );

            $numFrases = count($frases);

            print("
                <div id='carouselExampleCaptions' class='carousel slide' data-bs-ride='false'>
                    <div class='carousel-indicators'>
                        <button type='button' data-bs-target='#carouselExampleCaptions' data-bs-slide-to='0'
                        class='active bg-dark' aria-current='true' aria-label='Slide 1'></button>");
                            
                        for ($i = 1; $i < $numFrases; $i++) {
                            print("<button type='button' data-bs-target='#carouselExampleCaptions'
                            data-bs-slide-to='".$i."' aria-label='Slide 2' class='bg-dark'></button>");
                        }
                        print("
                    </div>

                        <div class='carousel-inner'>
                            <div class='carousel-item active'>
                                <img src='../pictures/comunicacion/0.jpg' class='d-block w-100'>
                                <div class='carousel-caption d-none d-md-block'>
                                    <h5>".$frases[0]."</h5>
                                </div>
                            </div>");
                            
                            for ($i = 1; $i < $numFrases; $i++) {
                                print("
                                    <div class='carousel-item'>
                                        <img src='../pictures/comunicacion/$i.jpg' class='d-block w-100'>
                                        <div class='carousel-caption d-none d-md-block'>
                                            <h5>".$frases[$i]."</h5>
                                        </div>
                                    </div>
                                ");
                            }
                            print("
                        </div>

                        <button class='carousel-control-prev' type='button' data-bs-target='#carouselExampleCaptions' data-bs-slide='prev'>
                            <span class='carousel-control-prev-icon bg-dark' aria-hidden='true'></span>
                            <span class='visually-hidden'>Previous</span>
                        </button>

                        <button class='carousel-control-next' type='button' data-bs-target='#carouselExampleCaptions' data-bs-slide='next'>
                            <span class='carousel-control-next-icon bg-dark' aria-hidden='true'></span>
                            <span class='visually-hidden'>Next</span>
                        </button>
                </div>
            ");
        }
    }
?>