<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RapiBnB</title>
    <!--------------------Bootstrap 5.2------------------------------------->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
            <div class="nav navbar-nav">
                <a class="nav-item nav-link" href="<?=URL_PATH.'/Page/home/';?>">Home</a>
                <a class="nav-item nav-link" href="<?=URL_PATH.'/Usuario/Login/';?>">Iniciar Sesion</a>
                <a class="nav-item nav-link" href="<?=URL_PATH.'/Usuario/signUp/';?>">Crear cuenta</a>
            </div>
        </nav>
    </header>
    <main>
    <?php echo $content ?>
    </main>
    <footer>

    </footer>
</body>
</html>