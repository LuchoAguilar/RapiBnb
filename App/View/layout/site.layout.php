<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RapiBnB</title>

    <!--------------------Bootstrap 5.2------------------------------------->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!--------------------Bootstrap JavaScript------------------------------>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        var URL_PATH = '<?= URL_PATH ?>';
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!--------------------Bootstrap JavaScript------------------------------>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!--------------------Script Personalizado------------------------------>
    <script src="<?=URL_PATH?>/Assets/js/scripts.js"></script>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand navbar-ligth bg-light">
            <div class="nav navbar-nav" id="nav">
                <a class="nav-item nav-link active" href="#" aria-current="page" id="icon">RapiBnB<span class="visually-hidden">(current)</span></a>
                <a class="nav-item nav-link" href="<?=URL_PATH.'/Usuario/home/';?>">Usuario</a>
                <a class="nav-item nav-link" href="<?=URL_PATH.'/OfertaAlquiler/home';?>">Publicaciones</a>
                <a class="nav-item nav-link" href="<?=URL_PATH.'/Usuario/LogOut/';?>">Cerrar Sesion</a>
            </div>
        </nav>
    </header>

    <?php echo $content ?>

    <footer>
        <!-- footer -->
    </footer>

    <script src="<?= URL_PATH?>/Assets/js/nav.js"></script>
</body>
</html>