async function user() {
    let reposense = await fetch(URL_PATH + '/Usuario/table');
    let reposenseData = await reposense.json();
    
    if (reposenseData.success) {
         const cardUser = document.getElementById('divUser');
         cardUser.innerHTML = `
             <div style="padding: 3rem !important; display: flex; justify-content: center;">
                 <div style="width: 400px; background-color: #f0f0f0; border-radius: 10px; padding: 20px;">
                     <div style="text-align: center;">
                         <img src="${URL_PATH}/Assets/images/fotoPerfil/${reposenseData.result.fotoRostro}" style="border-radius: 50%; max-width: 100px;" alt="User-Profile-Image">
                         <h6 style="font-weight: 600; margin-top: 10px;">${reposenseData.result.nombreUsuario}</h6>
                     </div>
                     <div>
                         <h6 style="font-weight: 600; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px solid #ccc;">Información</h6>
                         <div>
                             <p style="font-weight: 600; margin-bottom: 10px;">Nombre Completo:</p>
                             <p style="font-weight: 400;">${reposenseData.result.nombreCompleto}</p>
                         </div>
                         <div>
                             <p style="font-weight: 600; margin-bottom: 10px;">Email:</p>
                             <p style="font-weight: 400;">${reposenseData.result.correo}</p>
                         </div>
                         <div>
                             <p style="font-weight: 600; margin-bottom: 10px;">Bio:</p>
                             <p style="font-weight: 400;">${reposenseData.result.bio}</p>
                         </div>
                         <h6 style="font-weight: 600; margin-top: 20px; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px solid #ccc;">Intereses</h6>
                         <div>
                             <p style="font-weight: 600; margin-bottom: 10px;">Intereses:</p>
                             <p style="font-weight: 400;">${reposenseData.result.contrasena}</p>
                         </div>
                     </div>
                     <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                         <a class="btn btn-info" href="${URL_PATH}/usuario/edit/?id=${reposenseData.result.usuarioID}" role="button">Editar perfil</a>
                         <a class="btn btn-info" href="${URL_PATH}/usuario/edit/?id=${reposenseData.result.usuarioID}" role="button">Agregar intereses</a>
                     </div>
                 </div>
             </div>
         `;
     }
 }
 
 user();
 

