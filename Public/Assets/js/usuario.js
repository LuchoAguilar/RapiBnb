async function user() {
    let reposense = await fetch(URL_PATH + '/Usuario/table');
    let reposenseData = await reposense.json();

    if (reposenseData.success) {
        const cardUser = document.getElementById('divUser');
        const data = reposenseData.result.usuario;
        const intereses = reposenseData.result.intereses;
        const esVerificado = reposenseData.result.esVerificado;
        const foto = (data.fotoRostro != null) ? data.fotoRostro : 'user.png';
        const nombre = (data.nombreCompleto != null) ? data.nombreCompleto : 'Agregue su nombre';
        const bio = (data.bio != null) ? data.bio : 'Agregue su bio';
        const documentacionData = data.documentacionID;
        let interesesHTML = "";
        let documentacionHTML = "";

        if (intereses && intereses.length > 0) {
            interesesHTML = intereses.map(interes => {
                return `<p style="font-weight: 400;">${interes.nombresDeInteres}</p>`;
            }).join("");
        } else {
            interesesHTML = "Aún no ha agregado sus intereses";
        }
        
        if (documentacionData === null && esVerificado === null) {
            documentacionHTML = `
                    <a class="btn btn-info" href="${URL_PATH}/usuario/verificar/" role="button">Verificar Cuenta</a>
                `;
        } else {
            documentacionHTML = `
                    <a class="btn btn-info d-none" href="" role="button">no ver</a>
                `;
        }

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
                            <div>
                                <h6 style="font-weight: 600; margin-bottom: 10px;">Intereses:</h6>
                                ${interesesHTML}
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                            <a class="btn btn-info" href="${URL_PATH}/usuario/edit/?id=${data.usuarioID}" role="button">Editar perfil</a>
                            ${documentacionHTML}
                            <a class="btn btn-info" href="${URL_PATH}/usuario/interesesForm/" role="button">Agregar intereses</a>
                        </div>
                    </div>
                </div>
            `;

    } else {
        console.log(data.message);
    }
}

user();
