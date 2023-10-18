<div class="container">
    <div class="row">
        <div class="col-4"> </div>
        <div class="col-4">
            <br><br><br><br><br><br>
                <div class="card">
                    <div class="card-header"></div>
                        <div class="card-body">
                            <form action="" method="post" id="usuarioSignUp">
                                <!--input text-->
                                <label for="usuario" class="form-label">Usuario</label>
                                <input type="text" class="form-control" name="usuario" id="usuario" aria-describedby="helpId" placeholder="usuario">

                                <label for="correo" class="form-label">Correo</label>
                                <input type="text" class="form-control" name="correo" id="correo" aria-describedby="helpId" placeholder="Correo">

                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" name="password" id="password" aria-describedby="helpId" placeholder="Contraseña">
                                
                                <label for="passwordConfirm" class="form-label">Confirmar contraseña</label>
                                <input type="password" class="form-control" name="passwordConfirm" id="passwordConfirm" aria-describedby="helpId" placeholder="Confirmar contraseña">
                                <!-- button comun--><br>
                                <button type="submit" class="btn btn-success">Crear Usuario</button>
                                <!-- button ref -->
                                <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
                            </form>
                        </div>
                </div>
        </div>
    </div>
</div>

        
<script src="<?= URL_PATH?>/Assets/js/usuarioSignUp.js"></script>
