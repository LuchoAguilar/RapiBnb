<main class="container-fluid">
    <div class="container-fluid">
        <button
            onclick="crearOferta(<?= $parameters['esVerificado'] ?? 'false' ?>, <?= $parameters['cantOfertas'] ?? 0 ?>);"
            class="btn btn-info">Crear Oferta</button>
    </div>
    <div class="row">
        <div class="col" id="divCard">

        </div>
    </div>
</main>

<script src="<?= URL_PATH?>/Assets/js/publicarOferta.js"></script>