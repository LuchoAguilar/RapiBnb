<main class="container mt-3">
        
        <div class="row">
            <div class="col" id="divCard">
            
            </div>
        </div>

        <div class="container">
        <button onclick="crearOferta(<?= $parameters['esVerificado'] ?? 'false' ?>, <?= $parameters['cantOfertas'] ?? 0 ?>);" class="btn btn-info">Crear Oferta</button>
        </div>
        
</main>

<script src="<?= URL_PATH?>/Assets/js/publicarOferta.js"></script>