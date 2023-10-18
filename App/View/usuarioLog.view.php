<div class="container">
    <div class="row">
        <div class="col-4"></div>
            <div class="col-4">
                <br><br><br><br><br><br>
                <div class="card">
                    <div class="card-header">
                        Login
                    </div>
                    <div class="card-body">
                        <form action="" method="post" id="usuarioLogin">
                            <div class="mb-3">
                                <label for="usuario" class="form-label" >Usuario</label>
                                <input type="text" class="form-control" name="usuario" id="usuario" aria-describedby="help">
                            </div>
                            <div class="mb-3">
                                <label for="contrasena" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" name="contrasena" id="contrasena" aria-describedby="helpId" placeholder="">
                            </div>
                                <button type="submit" class="btn btn-success">Ingresar</button>
                        </form>
                    </div>        
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= URL_PATH?>/Assets/js/usuarioLog.js"></script>           