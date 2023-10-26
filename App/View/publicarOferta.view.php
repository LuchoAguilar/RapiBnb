<main class="container mt-3">
        <div class="container">
        <button onclick="crearOferta(<?= $parameters['esVerificado'] ?? 'false' ?>, <?= $parameters['cantOfertas'] ?? 0 ?>);" class="btn btn-info">Crear Oferta</button>
        </div>
        <div class="row">
            <div class="col" id="divCard">
            <h1 class="display-4">Bienvenido, aun no agrego su oferta?</h1>
            <p class="lead">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
            <hr class="my-4">
            <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
            <p class="lead">
                <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
            </p>
            </div>
        </div>
        
</main>

<script src="<?= URL_PATH?>/Assets/js/publicarOferta.js"></script>