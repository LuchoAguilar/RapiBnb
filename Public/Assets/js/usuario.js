async function user() {
    let reposense = await fetch(URL_PATH + '/Usuario/table');
    let reposenseData = await reposense.json();
    
    if (reposenseData.success) {
         const cardUser = document.getElementById('divUser');
         const data =  reposenseData.result.usuario;
         if(data){
            const foto = (data.fotoRostro != null)? data.fotoRostro : 'user.png';
            const nombre = (data.nombreCompleto != null)? data.nombreCompleto: 'Agregue su nombre';
            const bio = (data.bio != null)? data.bio : 'Agregue su bio'; 
            cardUser.innerHTML = `
                <div style="padding: 3rem !important; display: flex; justify-content: center;">
                    <div style="width: 400px; background-color: #f0f0f0; border-radius: 10px; padding: 20px;">
                        <div style="text-align: center;">
                            <img src="${URL_PATH}/Assets/images/fotoPerfil/${foto}" style="border-radius: 50%; max-width: 100px;" alt="User-Profile-Image">
                            <h6 style="font-weight: 600; margin-top: 10px;">${data.nombreUsuario}</h6>
                        </div>
                        <div>
                            <h6 style="font-weight: 600; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px solid #ccc;">Información</h6>
                            <div>
                                <p style="font-weight: 600; margin-bottom: 10px;">Nombre Completo:</p>
                                <p style="font-weight: 400;">${nombre}</p>
                            </div>
                            <div>
                                <p style="font-weight: 600; margin-bottom: 10px;">Email:</p>
                                <p style="font-weight: 400;">${data.correo}</p>
                            </div>
                            <div>
                                <p style="font-weight: 600; margin-bottom: 10px;">Bio:</p>
                                <p style="font-weight: 400;">${bio}</p>
                            </div>
                            <h6 style="font-weight: 600; margin-top: 20px; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px solid #ccc;">Intereses</h6>
                            <div>
                                <p style="font-weight: 600; margin-bottom: 10px;">Intereses:</p>
                                <p style="font-weight: 400;">${reposenseData.result.intereses}</p>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                            <a class="btn btn-info" href="${URL_PATH}/usuario/edit/?id=${data.usuarioID}" role="button">Editar perfil</a>
                            <a class="btn btn-info" href="${URL_PATH}/usuario/interesesForm/" role="button">Agregar intereses</a>
                        </div>
                    </div>
                </div>
            `;
         }else{
            const foto = (reposenseData.result.fotoRostro != null)? reposenseData.result.fotoRostro : 'user.png';
            const nombre = (reposenseData.result.nombreCompleto != null)? reposenseData.result.nombreCompleto: 'Agregue su nombre';
            const bio = (reposenseData.result.bio != null)? reposenseData.result.bio : 'Agregue su bio';
            cardUser.innerHTML = `
                <div style="padding: 3rem !important; display: flex; justify-content: center;">
                    <div style="width: 400px; background-color: #f0f0f0; border-radius: 10px; padding: 20px;">
                        <div style="text-align: center;">
                            <img src="${URL_PATH}/Assets/images/fotoPerfil/${foto}" style="border-radius: 50%; max-width: 100px;" alt="User-Profile-Image">
                            <h6 style="font-weight: 600; margin-top: 10px;">${reposenseData.result.nombreUsuario}</h6>
                        </div>
                        <div>
                            <h6 style="font-weight: 600; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px solid #ccc;">Información</h6>
                            <div>
                                <p style="font-weight: 600; margin-bottom: 10px;">Nombre Completo:</p>
                                <p style="font-weight: 400;">${nombre}</p>
                            </div>
                            <div>
                                <p style="font-weight: 600; margin-bottom: 10px;">Email:</p>
                                <p style="font-weight: 400;">${reposenseData.result.correo}</p>
                            </div>
                            <div>
                                <p style="font-weight: 600; margin-bottom: 10px;">Bio:</p>
                                <p style="font-weight: 400;">${bio}</p>
                            </div>
                            <h6 style="font-weight: 600; margin-top: 20px; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px solid #ccc;">Intereses</h6>
                            <div>
                                <p style="font-weight: 400;">Aun no agrego sus intereses</p>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                            <a class="btn btn-info" href="${URL_PATH}/usuario/edit/?id=${reposenseData.result.usuarioID}" role="button">Editar perfil</a>
                            <a class="btn btn-info" href="${URL_PATH}/usuario/interesesForm/" role="button">Agregar intereses</a>
                        </div>
                    </div>
                </div>
            `;
         }
         
     }
 }
 
 user();