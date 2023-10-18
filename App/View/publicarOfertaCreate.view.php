<main class="container">
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="text-center">Crear Oferta de Alquiler</h3>
        </div>
        <div class="card-body g-3 mt-3">
            <form action="" method="post" enctype="multipart/form-data" id="ofertaAlquilerCreate">
                <div class="row">
                    <div class="col-md-6">
                        <label for="titulo" class="form-label">Título:</label>
                        <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Título" required>
                    </div>
                    <div class="col-md-6">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <input type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Descripción" required>
                    </div>
                    <div class="col-md-6">
                        <label for="ubicacion" class="form-label">Ubicación:</label>
                        <input type="text" class="form-control" name="ubicacion" id="ubicacion" placeholder="Ubicación" required>
                    </div>
                    <div class="col-md-6">
                        <label for="etiquetas" class="form-label">Etiquetas</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="etiquetas[]" value="casa" id="casa">
                            <label class="form-check-label" for="casa">Casa</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="etiquetas[]" value="hotel" id="hotel">
                            <label class="form-check-label" for="hotel">Hotel</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="etiquetas[]" value="departamento" id="departamento">
                            <label class="form-check-label" for="departamento">Departamento</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="etiquetas[]" value="cabana" id="cabana">
                            <label class="form-check-label" for="cabana">Cabaña</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="galeriaFotos" class="form-label">Galería de Fotos:</label>
                        <input type="file" class="form-control" id="galeriaFotos" name="galeriaFotos[]" multiple accept="image/*" required>
                    </div>
                    <div class="col-md-6">
                        <label for="costoAlquilerPorDia" class="form-label">Costo de Alquiler por Día:</label>
                        <input type="number" step="0.01" class="form-control" name="costoAlquilerPorDia" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tiempoMinPermanencia" class="form-label">Tiempo Mínimo de Permanencia:</label>
                        <input type="number" class="form-control" name="tiempoMinPermanencia" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tiempoMaxPermanencia" class="form-label">Tiempo Máximo de Permanencia:</label>
                        <input type="number" class="form-control" name="tiempoMaxPermanencia" required>
                    </div>
                    <div class="col-md-3">
                        <label for="cupo" class="form-label">Cupo:</label>
                        <input type="number" class="form-control" name="cupo" required>
                    </div>
                    <div class="col-md-3">
                        <label for="fechaInicio" class="form-label">Fecha de Inicio (Opcional):</label>
                        <input type="date" class="form-control" name="fechaInicio">
                    </div>
                    <div class="col-md-3">
                        <label for="fechaFin" class="form-label">Fecha de Fin (Opcional):</label>
                        <input type="date" class="form-control" name="fechaFin">
                    </div>
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-success">Crear Oferta</button>
                    <a name="" id="" class="btn btn-primary" href="<?=URL_PATH.'/OfertaAlquiler/home/';?>" role="button">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="<?= URL_PATH?>/Assets/js/publicarOferta.js"></script>
