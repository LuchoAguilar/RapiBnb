<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RapiBnB</title>
    <!--------------------Bootstrap 5.2------------------------------------->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="<?=URL_PATH?>/Assets/css/styles.css">
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
        <div class="container-fluid">
            <div class="row">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="<?= URL_PATH ?>/Page/home/"><i class="fa-solid fa-earth-americas fa-2xl mt-4" style="color: #CCCCCC;"></i></a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                            <div class="collapse navbar-collapse RapiBnB-nav" id="navbarNav">
                                <ul class="navbar-nav">
                                    <li class="nav-item ml-auto">
                                        <a class="nav-link" style="color: #CCCCCC;" href="<?= URL_PATH ?>/Page/buscar/"><i class="fas fa-search"></i> Buscar</a>
                                    </li>
                                    <li class="nav-item ml-auto dropdown">
                                        <a class="nav-link dropdown-toggle" style="color: #CCCCCC;" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-user"></i> Usuario
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="<?=URL_PATH.'/Usuario/Login/';?>">Iniciar Sesion</a>
                                            <a class="dropdown-item" href="<?=URL_PATH.'/Usuario/signUp/';?>">Crear cuenta</a>
                                        </div>
                                    </li>
                                </ul>
                            </div> 
                    </div>                  
                </nav>
            </div>
        </div>
        

    </header>
    
    <main>
    <?php echo $content ?>
    </main>
    <footer>

    </footer>
</body>
</html>