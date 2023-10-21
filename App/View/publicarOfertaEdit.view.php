<main class="container">
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="text-center">Editar Oferta de Alquiler</h3>
        </div>
        <div class="card-body g-3 mt-3">
            <form action="" method="post" enctype="multipart/form-data" id="ofertaAlquilerEdit">
                <div class="row">
                    <div class="col-md-6">
                        <label for="titulo" class="form-label">Título:</label>
                        <input type="text" class="form-control" name="titulo" id="titulo" value="<?= $parameters['oferta']['titulo'] ?? '' ?>" placeholder="Título" required>
                    </div>
                    <div class="col-md-6">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <input type text" class="form-control" name="descripcion" id="descripcion" value="<?= $parameters['oferta']['descripcion'] ?? '' ?>" placeholder="Descripción" required>
                    </div>
                    <div class="col-md-6">
                        <label for="ubicacion" class="form-label">Ubicación:</label>
                        <input type="text" class="form-control" name="ubicacion" id="ubicacion" value="<?= $parameters['oferta']['ubicacion'] ?? '' ?>" placeholder="Ubicación" required>
                    </div>
                    <div class="col-md-3">
                        <label for="servicios[]" class="form-label">Servicios:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="servicios[]" value="gas" id="Gas" <?= isset($parameters['oferta']['listServicios']) && in_array('gas', explode(', ', $parameters['oferta']['listServicios'])) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="gas">Gas</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="servicios[]" value="internet" id="Internet" <?= isset($parameters['oferta']['listServicios']) && in_array('internet', explode(', ', $parameters['oferta']['listServicios'])) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="internet">Internet</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="servicios[]" value="electricidad" id="Electricidad" <?= isset($parameters['oferta']['listServicios']) && in_array('electricidad', explode(', ', $parameters['oferta']['listServicios'])) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="electricidad">Electricidad</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="servicios[]" value="amoblado" id="Amoblado" <?= isset($parameters['oferta']['listServicios']) && in_array('amoblado', explode(', ', $parameters['oferta']['listServicios'])) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="amoblado">Amoblado</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="servicios[]" value="estacionamiento" id="Estacionamiento" <?= isset($parameters['oferta']['listServicios']) && in_array('estacionamiento', explode(', ', $parameters['oferta']['listServicios'])) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="estacionamiento">Estacionamiento</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="etiqueta" class="form-label">Etiquetas:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="etiqueta" value="casa" id="Casa" <?= isset($parameters['oferta']['etiquetas']) && $parameters['oferta']['etiquetas'] == 'casa' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="casa">Casa</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="etiqueta" value="hotel" id="Hotel" <?= isset($parameters['oferta']['etiquetas']) && $parameters['oferta']['etiquetas'] == 'hotel' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="hotel">Hotel</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="etiqueta" value="departamento" id="Departamento" <?= isset($parameters['oferta']['etiquetas']) && $parameters['oferta']['etiquetas'] == 'departamento' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="departamento">Departamento</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="etiqueta" value="cabana" id="Cabaña" <?= isset($parameters['oferta']['etiquetas']) && $parameters['oferta']['etiquetas'] == 'cabana' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="cabana">Cabaña</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="etiqueta" value="habitaciones" id="Habitaciones" <?= isset($parameters['oferta']['etiquetas']) && $parameters['oferta']['etiquetas'] == 'habitaciones' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="habitaciones">Habitaciones</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="galeriaFotos" class="form-label">Galería de Fotos:</label>
                        <input type="file" class="form-control" id="galeriaFotos" name="galeriaFotos[]" multiple accept="image/*" required>
                    </div>

                    <div class="col-md-6">
                        <label for="costoAlquilerPorDia" class="form-label">Costo de Alquiler por Día:</label>
                        <input type="number" step="0.01" class="form-control" name="costoAlquilerPorDia" value="<?= $parameters['oferta']['costoAlquilerPorDia'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tiempoMinPermanencia" class="form-label">Tiempo Mínimo de Permanencia:</label>
                        <input type="number" class="form-control" name="tiempoMinPermanencia" value="<?= $parameters['oferta']['tiempoMinPermanencia'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tiempoMaxPermanencia" class="form-label">Tiempo Máximo de Permanencia:</label>
                        <input type="number" class="form-control" name="tiempoMaxPermanencia" value="<?= $parameters['oferta']['tiempoMaxPermanencia'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="cupo" class="form-label">Cupo:</label>
                        <input type="number" class="form-control" name="cupo" value="<?= $parameters['oferta']['cupo'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="fechaInicio" class="form-label">Fecha de Inicio (Opcional):</label>
                        <input type="date" class="form-control" name="fechaInicio" value="<?= $parameters['oferta']['fechaInicio'] ?? '' ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="fechaFin" class="form-label">Fecha de Fin (Opcional):</label>
                        <input type="date" class="form-control" name="fechaFin" value="<?= $parameters['oferta']['fechaFin'] ?? '' ?>">
                    </div>


                    <div class="col-md-12 d-none">
                        <label for="textID" class="form-label">ID</label>
                        <input type="text" class="form-control" value="<?= $parameters['oferta']['ofertaID'] ?? '' ?>" name="textID" id="textID" placeholder="ID">
                    </div>
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-success">Modificar Oferta</button>
                    <a name="" id="" class="btn btn-primary" href="<?= URL_PATH.'/OfertaAlquiler/home/'; ?>" role="button">Cancelar</a>
                </div>
            </form>
        </div>
        <div class="card-footer"></div>
    </div>
</main>

<script src="<?= URL_PATH?>/Assets/js/publicarOfertaEdit.js"></script>