<main class="container-fluid">
    <div class="container-fluid mt-2">
        <button
            onclick="crearOferta(<?= $parameters['esVerificado'] ?? 'false' ?>, <?= $parameters['cantOfertas'] ?? 0 ?>);"
            <button class="btn confirmacion">
                <i class="fas fa-plus"></i> Agregar
            </button>
    </div>
    <div class="row mt-2" id="divCard"></div>
</main>

<script src="<?= URL_PATH?>/Assets/js/publicarOferta.js"></script>