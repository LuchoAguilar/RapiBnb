<main class="container mt-5">
    <div class="row">
        <div class="card col-md-6" style="max-width: 600px; max-height: 800px; margin: auto;" id="ofertaDeAlquiler">
            <div class="card-header">
                <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            // Suponiendo que $parameters['galeriaFotos'] es una cadena con rutas de imágenes separadas por comas
                            $galeriaFotos = explode(', ', $parameters['oferta']['galeriaFotos']);
                            $activeClass = 'active'; // Clase activa para el primer elemento
                            foreach ($galeriaFotos as $foto) {
                                echo '<div class="carousel-item ' . $activeClass . '">';
                                echo    '<img src="'. URL_PATH . '/Assets/images/galeriaFotos/' . $foto . '" style="width: 600px; height: 400px;" alt="Imagen">';
                                echo '</div>';
                                $activeClass = ''; // Eliminar la clase activa después del primer elemento
                            }
                            ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>                                              
                </div>
            </div>  
            <div class="card-body">
                <h5 class="card-title"><?= $parameters['oferta']['titulo'] ?? '' ?></h5>
                <p class="card-text"><?= $parameters['oferta']['descripcion'] ?? '' ?></p>
                <p class="card-text"><small class="text-muted">Ubicación: <?= $parameters['oferta']['ubicacion'] ?? '' ?></small></p>
                <div class="row border rounded">
                    <div class="col-md-12 border rounded">
                        <label for="servicios">Servicios:</label>
                        <p class="card-text " id="servicios"><?= $parameters['oferta']['listServicios'] ?? '' ?></p>
                    </div>
                    <div class="col-md-6 border rounded">
                        <label for="costoPorDia">Costo por día:</label>
                        <p class="card-text " id="costoPorDia">$<?= $parameters['oferta']['costoAlquilerPorDia'] ?? '' ?></p>
                    </div>
                    <div class="col-md-6 border rounded">
                        <label for="cupoPersonas">Cupo de personas:</label>
                        <p class="card-text" id="cupoPersonas"><?= $parameters['oferta']['cupo'] ?? '' ?> personas</p>
                    </div>
                    <div class="col-md-6 border rounded">
                        <label for="tiempoMinPermanencia">Tiempo mínimo de permanencia:</label>
                        <p class="card-text" id="tiempoMinPermanencia"><?= $parameters['oferta']['tiempoMinPermanencia'] ?? '' ?> días</p>
                    </div>
                    <div class="col-md-6 border rounded">
                        <label for="tiempoMaxPermanencia">Tiempo máximo de permanencia:</label>
                        <p class="card-text " id="tiempoMaxPermanencia"><?= $parameters['oferta']['tiempoMaxPermanencia'] ?? '' ?> días</p>
                    </div>
                    <div class="col-md-6 border rounded d-none" id="fechaInicio">
                        <label for="tiempoMaxPermanencia">Fecha en que inicia la estadia:</label>
                        <p class="card-text" id="tiempoMaxPermanencia"><?= $parameters['oferta']['tiempoMaxPermanencia'] ?? '' ?> días</p>
                    </div>
                    <div class="col-md-6 border rounded d-none" id="fechaFin">
                        <label for="tiempoMaxPermanencia">Fecha en que finaliza la estadia:</label>
                        <p class="card-text" id="tiempoMaxPermanencia"><?= $parameters['oferta']['tiempoMaxPermanencia'] ?? '' ?> días</p>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="container" id="ofertar"></div>
                <div class="container" id="errores"></div>
            </div>
        </div>
        <div class="col-md-6" id="valoraciones">
            <div class="card" style="max-width: 600px; max-height: 800px; margin: auto; width: 600px; height: 732px;">
                <div class="card-body">
                    <div class="card" >
                        <div class="card-header"><h3>Valoraciones de la oferta</h3></div>
                        <div class="card-body">
                        <div class="row" id="reservas"></div>
                        </div>
                        <div class="card-footer text-end" id="btnPaginacion"></div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
    
</main>
<script>
    var idOferta = <?= $parameters['oferta']['ofertaID'] ?? '' ?>;
</script>
<script src="<?= URL_PATH?>/Assets/js/mostrarOferta.js"></script>

