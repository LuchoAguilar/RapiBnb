<div class="card">
    <div class="card-header">
        <h3 class="text-center">Editar Usuario</h3>
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data" id="usuarioEdit">
            <div class="mb-3 d-none">
                <label for="textID" class="form-label">ID</label>
                <input type="text" class="form-control" value="<?= $parameters['usuario']['usuarioID'] ?? ''?>" name="textID" id="textID" placeholder="ID">
            </div>
            
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" class="form-control" value="<?= $parameters['usuario']['nombreUsuario'] ?? ''?>" name="usuario" id="usuario" placeholder="Usuario">
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre Completo</label>
                <input type="text" class="form-control" value="<?= $parameters['usuario']['nombreCompleto'] ?? ''?>" name="nombre" id="nombre" placeholder="Nombre Completo">
            </div>

            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="text" class="form-control" value="<?= $parameters['usuario']['correo'] ?? ''?>" name="correo" id="correo" placeholder="Correo">
            </div>

            <div class="mb-3">
                <label for="fotoPerfil" class="form-label">Foto de perfil</label>
                <img width="50" src="<?=URL_PATH.'/Assets/images/fotoPerfil/'.$parameters['usuario']['fotoRostro'] ?? ''?>" alt="fotoPerfil">
                <input class="form-control" name="fotoPerfil" id="fotoPerfil" type="file">
            </div>

            <div class="mb-3">
                <label for="bio" class="form-label">Pequeña Bio</label>
                <textarea class="form-control" name="bio" id="bio"><?= $parameters['usuario']['bio'] ?? ''?></textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success">Editar</button>
                <a id="" class="btn btn-primary" href="<?=URL_PATH.'/Usuario/home/';?>" role="button">Cancelar</a>
            </div>
        </form>
    </div>
    <div class="card-footer text-muted"></div>
</div>


<script src="<?= URL_PATH?>/Assets/js/usuarioUpdate.js"></script>      