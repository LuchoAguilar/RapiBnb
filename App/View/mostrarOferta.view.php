<main class="container mt-5">
    <div class="row">
        <div class="card col-md-6" style="max-width: 600px; max-height: 800px; margin: auto;" id="ofertaDeAlquiler">
            <div class="card-header" style="background: white;">
                <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $galeriaFotos = explode(', ', $parameters['oferta']['galeriaFotos']);
                        $activeClass = 'active';
                        foreach ($galeriaFotos as $foto) {
                            echo '<div class="carousel-item ' . $activeClass . '">';
                            echo    '<img src="'. URL_PATH . '/Assets/images/galeriaFotos/' . $foto . '" style="width: 600px; height: 400px;" alt="Imagen">';
                            echo '</div>';
                            $activeClass = '';
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
                    <div class="col-md-12 border">
                        <label for="servicios">Servicios:</label>
                        <p class="card-text " id="servicios"><?= $parameters['oferta']['listServicios'] ?? '' ?></p>
                    </div>
                    <div class="col-md-6 border">
                        <label for="costoPorDia">Costo por día:</label>
                        <p class="card-text " id="costoPorDia">$<?= $parameters['oferta']['costoAlquilerPorDia'] ?? '' ?></p>
                    </div>
                    <div class="col-md-6 border">
                        <label for="cupoPersonas">Cupo de personas:</label>
                        <p class="card-text" id="cupoPersonas"><?= $parameters['oferta']['cupo'] ?? '' ?> personas</p>
                    </div>
                    <div class="col-md-6 border">
                        <label for="tiempoMinPermanencia">Tiempo mínimo de permanencia:</label>
                        <p class="card-text" id="tiempoMinPermanencia"><?= $parameters['oferta']['tiempoMinPermanencia'] ?? '' ?> días</p>
                    </div>
                    <div class="col-md-6 border">
                        <label for="tiempoMaxPermanencia">Tiempo máximo de permanencia:</label>
                        <p class="card-text " id="tiempoMaxPermanencia"><?= $parameters['oferta']['tiempoMaxPermanencia'] ?? '' ?> días</p>
                    </div>
                    <div class="col-md-6 border d-none" id="fechaInicio">
                        <label for="tiempoMaxPermanencia">Fecha en que inicia la estadia:</label>
                        <p class="card-text" id="tiempoMaxPermanencia"><?= $parameters['oferta']['tiempoMaxPermanencia'] ?? '' ?> días</p>
                    </div>
                    <div class="col-md-6 border d-none" id="fechaFin">
                        <label for="tiempoMaxPermanencia">Fecha en que finaliza la estadia:</label>
                        <p class="card-text" id="tiempoMaxPermanencia"><?= $parameters['oferta']['tiempoMaxPermanencia'] ?? '' ?> días</p>
                    </div>
                </div>
                <div class="container mt-1 text-center">
                    <div class="container" id="ofertar"></div>
                    <div class="container" id="errores"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6" id="valoraciones">
  
            <div class="card" >
                <div class="card-header"><h3>Valoraciones de la oferta</h3></div>
                    <div class="card-body">
                        <div class="row" id="reservas"></div>
                    </div>
                <div class="card-footer text-end" id="btnPaginacion"></div>
            </div>

        </div>
          
    </div>
    <div class="container mt-2 text-center" id="contenedorForm">
            <div class="card col-md-12" style="max-width: 40%; margin: auto;">
                <div class="card-header">
                    Realizar Oferta:
                </div>
                <div class="card-body" id="formularioRentar">
                    <form action="" method="post" id="rentarForm">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="fecha-inicio">Fecha de inicio de alquiler:</label>
                                <input type="text" class="form-control" id="fecha-inicio" readonly>
                            </div>
                            <div class="col-md-12">
                                <label for="fecha-fin">Fecha de fin de alquiler:</label>
                                <input type="text" class="form-control" id="fecha-fin" readonly>
                            </div>
                        </div>
                        <button onclick="realizarRenta();"  class="btn confirmacion mt-2">Realizar Oferta</button> 
                    </form>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>
</main>
<script>
    var idOferta = <?= $parameters['oferta']['ofertaID'] ?? '' ?>;
</script>


<script>
  // Supongamos que recibes los rangos de fechas utilizados desde el backend en un formato adecuado
  var rangosUsados = [
    { inicio: '2023-11-05', fin: '2023-11-10' },
    { inicio: '2023-11-15', fin: '2023-11-20' }
    // Agrega más rangos utilizados según sea necesario
  ];

  $(document).ready(function () {
    $("#fecha-inicio, #fecha-fin").datepicker({
      dateFormat: 'yy-mm-dd',
      beforeShowDay: function (date) {
        var formattedDate = $.datepicker.formatDate('yy-mm-dd', date);
        var disableDate = false;

        for (var i = 0; i < rangosUsados.length; i++) {
          var rango = rangosUsados[i];
          if (date >= new Date(rango.inicio) && date <= new Date(rango.fin)) {
            disableDate = true;
            break;
          }
        }

        return [!disableDate];
      },
      onSelect: function (date, inst) {
        if (inst.id === "fecha-inicio") {
          // Si se selecciona la fecha de inicio, actualiza la fecha mínima de fecha fin
          var minDate = new Date(date);
          minDate.setDate(minDate.getDate() + 1); // Asegura que la fecha mínima de fecha fin sea al menos un día después
          $("#fecha-fin").datepicker("option", "minDate", minDate);
        }
      }
    });
  });
</script>






<script src="<?= URL_PATH?>/Assets/js/mostrarOferta.js"></script>

